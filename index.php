<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$genre = trim($_GET['genre'] ?? '');
$sort = $_GET['sort'] ?? '';
$search = trim($_GET['search'] ?? '');
$success = trim($_GET['msg'] ?? '');

if (!in_array($sort, ['asc', 'desc'], true)) {
    $sort = '';
}

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function filterUrl($changes = []) {
    $query = $_GET;

    foreach ($changes as $key => $value) {
        if ($value === null || $value === '') {
            unset($query[$key]);
        } else {
            $query[$key] = $value;
        }
    }

    return 'index.php' . (!empty($query) ? '?' . http_build_query($query) : '');
}

function getCoverPath($cover, $genre, $title) {
    $cover = trim((string)$cover);
    $genre = trim((string)$genre);
    $title = trim((string)$title);

    $pictures = [
        'Romance' => [
            'Pride and Prejudice' => [1, 'Pride_and_Prejudice'],
            'The Notebook' => [2, 'The_Notebook'],
            'Me Before You' => [3, 'Me_Before_You'],
            'The Love Hypothesis' => [4, 'The_Love_Hypothesis'],
            'Beach Read' => [5, 'Beach_Read'],
            'The Hating Game' => [6, 'The_Hating_Game'],
            'It Ends with Us' => [7, 'It_Ends_with_Us'],
            'The Fault in Our Stars' => [8, 'The_Fault_in_Our_Stars'],
            'Jane Eyre' => [9, 'Jane_Eyre'],
            "The Time Traveler's Wife" => [10, 'The_Time_Traveler_s_Wife'],
            'The Time Travelers Wife' => [10, 'The_Time_Traveler_s_Wife']
        ],

        'Fantasy' => [
            "Harry Potter and the Sorcerer's Stone" => [1, 'Harry_Potter_and_the_Sorcerer_s_Stone'],
            'Harry Potter and the Sorcerers Stone' => [1, 'Harry_Potter_and_the_Sorcerer_s_Stone'],
            'The Hobbit' => [2, 'The_Hobbit'],
            'The Fellowship of the Ring' => [3, 'The_Fellowship_of_the_Ring'],
            'A Game of Thrones' => [4, 'A_Game_of_Thrones'],
            'The Name of the Wind' => [5, 'The_Name_of_the_Wind'],
            'The Way of Kings' => [6, 'The_Way_of_Kings'],
            'The Golden Compass' => [7, 'The_Golden_Compass'],
            'The Lion, the Witch and the Wardrobe' => [8, 'The_Lion_the_Witch_and_the_Wardrobe'],
            'The Eye of the World' => [9, 'The_Eye_of_the_World'],
            'The Once and Future King' => [10, 'The_Once_and_Future_King']
        ],

        'Anime' => [
            'Demon Slayer Volume 1' => [1, 'Demon_Slayer_Volume_1'],
            'Demon Slayer: Kimetsu no Yaiba, Vol. 1' => [1, 'Demon_Slayer_Volume_1'],
            'Naruto Volume 1' => [2, 'Naruto_Volume_1'],
            'Naruto, Vol. 1' => [2, 'Naruto_Volume_1'],
            'One Piece Volume 1' => [3, 'One_Piece_Volume_1'],
            'One Piece, Vol. 1' => [3, 'One_Piece_Volume_1'],
            'Attack on Titan Volume 1' => [4, 'Attack_on_Titan_Volume_1'],
            'Attack on Titan, Vol. 1' => [4, 'Attack_on_Titan_Volume_1'],
            'My Hero Academia Volume 1' => [5, 'My_Hero_Academia_Volume_1'],
            'My Hero Academia, Vol. 1' => [5, 'My_Hero_Academia_Volume_1'],
            'Death Note Volume 1' => [6, 'Death_Note_Volume_1'],
            'Death Note, Vol. 1' => [6, 'Death_Note_Volume_1'],
            'Sailor Moon Volume 1' => [7, 'Sailor_Moon_Volume_1'],
            'Sailor Moon, Vol. 1' => [7, 'Sailor_Moon_Volume_1'],
            'Spy x Family Volume 1' => [8, 'Spy_x_Family_Volume_1'],
            'Spy x Family, Vol. 1' => [8, 'Spy_x_Family_Volume_1'],
            'Sword Art Online Volume 1' => [9, 'Sword_Art_Online_Volume_1'],
            'Sword Art Online, Vol. 1' => [9, 'Sword_Art_Online_Volume_1'],
            'Your Name' => [10, 'Your_Name'],
            'Your Name.' => [10, 'Your_Name']
        ]
    ];

    if (isset($pictures[$genre][$title])) {
        [$number, $filename] = $pictures[$genre][$title];

        return 'book_blossom_scene_pictures/' . $genre . '/' .
            sprintf('%02d', $number) . '_' . $filename . '.jpg';
    }

    if ($cover !== '' && preg_match('~^https?://~i', $cover)) {
        return $cover;
    }

    $cover = ltrim(str_replace('\\', '/', $cover), '/');

    if ($cover === '') {
        return 'uploads/book.jpg';
    }

    if (
        strpos($cover, 'uploads/') === 0 ||
        strpos($cover, 'book_blossom_book_covers/') === 0 ||
        strpos($cover, 'book_blossom_scene_pictures/') === 0
    ) {
        return $cover;
    }

    if (strpos($cover, '/') !== false) {
        return $cover;
    }

    return 'uploads/' . basename($cover);
}

$genres = [];

$genreResult = $conn->query(
    "SELECT DISTINCT genre
     FROM books
     WHERE genre IS NOT NULL AND genre <> ''
     ORDER BY genre ASC"
);

if ($genreResult) {
    while ($g = $genreResult->fetch_assoc()) {
        $genres[] = $g['genre'];
    }
}

$sql = "SELECT * FROM books WHERE 1=1";
$params = [];
$types = '';

if ($genre !== '') {
    $sql .= " AND genre = ?";
    $params[] = $genre;
    $types .= 's';
}

if ($search !== '') {
    $sql .= " AND (title LIKE ? OR author LIKE ?)";
    $keyword = '%' . $search . '%';
    $params[] = $keyword;
    $params[] = $keyword;
    $types .= 'ss';
}

if ($sort === 'asc') {
    $sql .= " ORDER BY year ASC, title ASC";
} elseif ($sort === 'desc') {
    $sql .= " ORDER BY year DESC, title ASC";
} else {
    $sql .= " ORDER BY id DESC";
}

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Unable to prepare book query.");
}

if ($params) {
    $stmt->bind_param($types, ...$params);
}

if (!$stmt->execute()) {
    die("Unable to load books.");
}

$result = $stmt->get_result();
$totalBooks = $result->num_rows;

$genreIcons = [
    'Horror' => '👻',
    'Motivational' => '🌟',
    'Novel' => '📖',
    'Sci-Fi' => '🚀',
    'Romance' => '💕',
    'Fantasy' => '🦄',
    'Anime' => '🎌',
    'Thriller' => '🕵️',
    'Mystery' => '🔎',
    'Adventure' => '🗺️',
    'Historical Fiction' => '🏰',
    'Biography' => '👤',
    'Poetry' => '🌷',
    'Crime' => '🔍',
    "Children's Books" => '🧸'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Blossom | Book Management</title>
    <link rel="stylesheet" href="style.css">
</head>

<body class="dashboard">

<header class="topbar">
    <a href="index.php" class="brand">
        <span class="brand-icon">📚</span>
        <span>
            <strong>Book Blossom</strong>
            <small>your little reading corner</small>
        </span>
    </a>

    <nav class="main-nav">
        <a href="index.php" class="nav-link active">🏡 Home</a>
        <a href="add.php" class="nav-link">✨ Add Book</a>
        <a href="logout.php" class="nav-link logout-link">💗 Logout</a>
    </nav>
</header>

<div class="page-layout">
    <aside class="sidebar">
        <div class="sidebar-heading">
            <span>🌷</span>
            <div>
                <h2>My Library</h2>
                <p>Find your next favorite</p>
            </div>
        </div>

        <a href="index.php" class="all-books">
            <span>📚</span>
            <span>All Books</span>
            <span class="arrow">›</span>
        </a>

        <section class="sidebar-section">
            <h3>🎨 Filter by Genre</h3>
            <div class="filter-list">
                <?php foreach ($genres as $item): ?>
                    <a
                        href="<?= e(filterUrl(['genre' => $item])) ?>"
                        class="filter-link <?= $genre === $item ? 'selected' : '' ?>"
                    >
                        <span><?= e($genreIcons[$item] ?? '🌸') ?></span>
                        <span><?= e($item) ?></span>
                        <?php if ($genre === $item): ?>
                            <span class="filter-check">✓</span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>

                <?php if (!$genres): ?>
                    <p class="muted-text">No genres available yet.</p>
                <?php endif; ?>
            </div>
        </section>

        <section class="sidebar-section">
            <h3>📅 Sort by Year</h3>
            <div class="filter-list">
                <a
                    href="<?= e(filterUrl(['sort' => 'asc'])) ?>"
                    class="filter-link <?= $sort === 'asc' ? 'selected' : '' ?>"
                >
                    <span>⬆️</span><span>Oldest First</span>
                </a>

                <a
                    href="<?= e(filterUrl(['sort' => 'desc'])) ?>"
                    class="filter-link <?= $sort === 'desc' ? 'selected' : '' ?>"
                >
                    <span>⬇️</span><span>Newest First</span>
                </a>
            </div>
        </section>

        <div class="library-note">
            <span class="note-icon">🧸</span>
            <h3>Reading time!</h3>
            <p>Every book takes you on a new adventure.</p>
        </div>
    </aside>

    <main class="content">
        <section class="welcome-banner">
            <div class="welcome-text">
                <span class="eyebrow">♡ WELCOME TO YOUR LIBRARY ♡</span>
                <h1>A little shelf of <span>happiness</span> 🌸</h1>
                <p>
                    Discover stories, collect your favorites,
                    and let your imagination bloom.
                </p>
                <a href="add.php" class="primary-button">
                    <span>＋</span> Add a New Book
                </a>
            </div>

            <div class="banner-decoration" aria-hidden="true">
                <span class="deco-book book-one">📕</span>
                <span class="deco-book book-two">📗</span>
                <span class="deco-book book-three">📘</span>
                <span class="deco-flower">🌼</span>
                <span class="deco-sparkle">✦</span>
            </div>
        </section>

        <?php if ($success !== '' && $success !== 'Login successful'): ?>
            <div class="success-message" id="successMsg">
                <span>🌷</span>
                <?= e($success) ?>
                <button type="button" class="dismiss-message"
                        onclick="this.parentElement.remove()"
                        aria-label="Dismiss message">×</button>
            </div>
        <?php endif; ?>

        <section class="library-toolbar">
            <div>
                <span class="section-kicker">YOUR COLLECTION</span>
                <h2>
                    <?php if ($genre !== ''): ?>
                        <?= e($genre) ?> Books 🌸
                    <?php elseif ($search !== ''): ?>
                        Search Results 🔎
                    <?php else: ?>
                        My Lovely Books 💗
                    <?php endif; ?>
                </h2>
                <p>
                    <?= (int)$totalBooks ?>
                    <?= $totalBooks === 1 ? 'book' : 'books' ?>
                    found in your collection
                </p>
            </div>

            <form class="search-form" method="GET" action="index.php">
                <?php if ($genre !== ''): ?>
                    <input type="hidden" name="genre" value="<?= e($genre) ?>">
                <?php endif; ?>
                <?php if ($sort !== ''): ?>
                    <input type="hidden" name="sort" value="<?= e($sort) ?>">
                <?php endif; ?>

                <span class="search-icon">🔎</span>
                <input type="search" name="search"
                       placeholder="Search books or authors..."
                       value="<?= e($search) ?>"
                       aria-label="Search books or authors">
                <button type="submit">Search</button>
            </form>
        </section>

        <div class="active-filters">
            <?php if ($genre !== '' || $sort !== '' || $search !== ''): ?>
                <span>Showing:</span>
                <?php if ($genre !== ''): ?>
                    <span class="filter-chip">🌸 <?= e($genre) ?></span>
                <?php endif; ?>
                <?php if ($sort === 'asc'): ?>
                    <span class="filter-chip">⬆️ Oldest first</span>
                <?php elseif ($sort === 'desc'): ?>
                    <span class="filter-chip">⬇️ Newest first</span>
                <?php endif; ?>
                <?php if ($search !== ''): ?>
                    <span class="filter-chip">🔎 <?= e($search) ?></span>
                <?php endif; ?>
                <a href="index.php" class="clear-filters">Clear all ×</a>
            <?php endif; ?>
        </div>

        <?php if ($totalBooks > 0): ?>
            <section class="cards">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <?php
                    $coverSrc = getCoverPath(
                        $row['cover'] ?? '',
                        $row['genre'] ?? '',
                        $row['title'] ?? ''
                    );
                    ?>

                    <a href="view.php?id=<?= (int)$row['id'] ?>" class="card">
                        <div class="cover-wrap">
                            <img
                                src="<?= e($coverSrc) ?>"
                                alt="Picture related to <?= e($row['title']) ?>"
                                class="book-cover"
                                loading="lazy"
                                onerror="this.onerror=null;this.src='uploads/book.jpg';"
                            >
                            <span class="cover-heart">♡</span>
                            <span class="cover-badge"><?= e($row['genre']) ?></span>
                        </div>

                        <div class="card-details">
                            <h3><?= e($row['title']) ?></h3>
                            <p class="book-author">
                                <span>By</span> <?= e($row['author']) ?>
                            </p>
                            <div class="book-meta">
                                <span>📅 <?= e($row['year']) ?></span>
                                <span class="read-more">View book ›</span>
                            </div>
                        </div>
                    </a>
                <?php endwhile; ?>
            </section>
        <?php else: ?>
            <section class="empty-state">
                <div class="empty-illustration">📚🌷</div>
                <h2>Oops! No books found.</h2>
                <p>
                    Try another search or genre, or add
                    a new book to your lovely collection.
                </p>
                <div class="empty-actions">
                    <a href="index.php" class="secondary-button">View All Books</a>
                    <a href="add.php" class="primary-button">＋ Add a Book</a>
                </div>
            </section>
        <?php endif; ?>

        <footer class="page-footer">
            Made with 💗 for book lovers everywhere
        </footer>
    </main>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const message = document.getElementById("successMsg");

    if (message) {
        setTimeout(function () {
            message.classList.add("fade-out");
            setTimeout(function () {
                message.remove();
            }, 500);
        }, 3000);
    }
});
</script>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
