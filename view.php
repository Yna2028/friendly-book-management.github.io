
<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header("Location: index.php?msg=" . urlencode("Invalid book ID."));
    exit();
}


$stmt = $conn->prepare("SELECT * FROM books WHERE id = ?");

if (!$stmt) {
    die("Unable to prepare query.");
}

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$book = $result->fetch_assoc();
$stmt->close();


if (!$book) {
    header("Location: index.php?msg=" . urlencode("Book not found."));
    exit();
}


function e($value) {
    return htmlspecialchars(
        (string) $value,
        ENT_QUOTES,
        'UTF-8'
    );
}

function getCoverPath($cover) {
    $cover = trim((string) $cover);

    if ($cover === '') {
        return 'uploads/book.jpg';
    }

    if (preg_match('~^https?://~i', $cover)) {
        return $cover;
    }

    $cover = str_replace('\\', '/', $cover);
    $cover = ltrim($cover, '/');

    if (
        strpos($cover, 'uploads/') === 0 ||
        strpos($cover, 'book_blossom_book_covers/') === 0
    ) {
        return $cover;
    }

    
    if (strpos($cover, '/') !== false) {
        return $cover;
    }

    
    return 'uploads/' . basename($cover);
}

$coverSrc = getCoverPath($book['cover'] ?? '');

$year = (int) ($book['year'] ?? 0);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title><?= e($book['title']) ?> | Book Blossom</title>

    <link rel="stylesheet" href="style.css">

    <style>
        
        body.book-detail-page {
            margin: 0;
            min-height: 100vh;
            background: #fff9f6;
            color: #60475e;
            font-family: 'DM Sans', Arial, sans-serif;
        }

        .detail-topbar {
            min-height: 82px;
            padding: 15px 5%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            box-sizing: border-box;
            background: rgba(255, 255, 255, 0.96);
            border-bottom: 1px solid #f5dce7;
        }

        .detail-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #76506d;
        }

        .detail-brand-icon {
            width: 46px;
            height: 46px;
            border-radius: 15px;
            display: grid;
            place-items: center;
            background: #fdebf3;
            font-size: 25px;
        }

        .detail-brand strong {
            display: block;
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 23px;
        }

        .detail-brand small {
            display: block;
            margin-top: 3px;
            color: #b17f9c;
            font-size: 12px;
        }

        .detail-nav {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .detail-nav a {
            padding: 11px 17px;
            border-radius: 25px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            color: #8b637f;
            background: #fff5f8;
            border: 1px solid #f6dce8;
            transition: 0.2s ease;
        }

        .detail-nav a:hover {
            background: #fce3ee;
            transform: translateY(-1px);
        }

        .detail-main {
            width: min(1050px, 90%);
            margin: 45px auto;
        }

        .detail-breadcrumb {
            margin-bottom: 22px;
            color: #b18aa2;
            font-size: 14px;
        }

        .detail-breadcrumb a {
            color: #dc719b;
            text-decoration: none;
        }

        .detail-card {
            display: grid;
            grid-template-columns: 330px minmax(0, 1fr);
            gap: 40px;
            padding: 35px;
            border: 1px solid #f3dce7;
            border-radius: 28px;
            background: #fff;
            box-shadow: 0 16px 45px rgba(153, 96, 126, 0.09);
        }

        .detail-cover-panel {
            min-height: 410px;
            padding: 22px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            border-radius: 22px;
            background: linear-gradient(
                145deg,
                #fff0f7,
                #f0eaff
            );
        }

        .detail-cover-panel::before {
            content: "✿";
            position: absolute;
            top: 10px;
            left: 15px;
            color: #e7a6c4;
            font-size: 28px;
            opacity: 0.65;
        }

        .detail-cover-panel::after {
            content: "♡";
            position: absolute;
            right: 14px;
            bottom: 5px;
            color: #dba6c4;
            font-size: 45px;
            opacity: 0.5;
        }

        .detail-cover {
            position: relative;
            z-index: 1;
            display: block;
            width: 100%;
            max-width: 260px;
            height: 350px;
            object-fit: contain;
            border-radius: 8px;
            filter: drop-shadow(0 10px 14px rgba(70, 45, 70, 0.15));
        }

        .detail-cover-placeholder {
            width: 190px;
            height: 285px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 15px;
            padding: 20px;
            box-sizing: border-box;
            text-align: center;
            border-radius: 12px;
            color: #fff;
            background: linear-gradient(145deg, #c77ba1, #8e729e);
            box-shadow: 0 10px 25px rgba(80, 50, 90, 0.2);
        }

        .detail-cover-placeholder .placeholder-icon {
            font-size: 42px;
        }

        .detail-cover-placeholder strong {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 19px;
            line-height: 1.4;
            overflow-wrap: anywhere;
        }

        .detail-cover-placeholder small {
            font-size: 12px;
            opacity: 0.9;
        }

        .detail-information {
            min-width: 0;
            padding: 10px 0;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .detail-eyebrow {
            margin-bottom: 13px;
            color: #d36f9c;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .detail-title {
            margin: 0 0 15px;
            color: #684c70;
            font-family: 'Playfair Display', Georgia, serif;
            font-size: clamp(28px, 3.3vw, 39px);
            line-height: 1.25;
            overflow-wrap: anywhere;
        }

        .detail-subtitle {
            margin: 0 0 25px;
            color: #b07d9b;
            font-size: 15px;
        }

        .detail-genre {
            display: inline-flex;
            align-items: center;
            padding: 8px 15px;
            margin-bottom: 24px;
            border: 1px solid #f5d8e5;
            border-radius: 25px;
            background: #fff5f9;
            color: #ce6694;
            font-size: 13px;
            font-weight: 700;
        }

        .detail-info-list {
            width: 100%;
            margin: 0;
        }

        .detail-info-row {
            padding: 15px 0;
            display: grid;
            grid-template-columns: 115px minmax(0, 1fr);
            gap: 12px;
            border-bottom: 1px solid #f5e7ed;
            font-size: 14px;
            line-height: 1.7;
        }

        .detail-info-row dt {
            color: #956e8d;
            font-weight: 700;
        }

        .detail-info-row dd {
            margin: 0;
            color: #68596b;
            overflow-wrap: anywhere;
        }

        .detail-description {
            white-space: pre-line;
        }

        .detail-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 28px;
        }

        .detail-button {
            min-height: 44px;
            padding: 12px 20px;
            box-sizing: border-box;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: 1px solid transparent;
            border-radius: 12px;
            text-decoration: none;
            font-family: inherit;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .detail-button:hover {
            transform: translateY(-2px);
        }

        .detail-button-back {
            background: #fff4f8;
            color: #986783;
            border-color: #f3d8e5;
        }

        .detail-button-edit {
            background: #d96f9b;
            color: #fff;
        }

        .detail-button-delete {
            background: #fff;
            color: #c95b6c;
            border-color: #f0cbd2;
        }

        .detail-footer {
            padding: 10px 0 35px;
            color: #b28ba4;
            text-align: center;
            font-size: 13px;
        }

        @media (max-width: 720px) {
            .detail-topbar {
                align-items: flex-start;
                flex-direction: column;
                padding: 16px 5%;
            }

            .detail-nav {
                width: 100%;
            }

            .detail-nav a {
                padding: 9px 12px;
            }

            .detail-main {
                margin: 25px auto;
            }

            .detail-card {
                grid-template-columns: 1fr;
                gap: 24px;
                padding: 18px;
                border-radius: 22px;
            }

            .detail-cover-panel {
                min-height: 340px;
            }

            .detail-cover {
                height: 290px;
            }

            .detail-information {
                padding: 0 4px 8px;
            }

            .detail-info-row {
                grid-template-columns: 90px minmax(0, 1fr);
            }

            .detail-actions {
                width: 100%;
            }

            .detail-button {
                flex: 1;
            }
        }
    </style>
</head>

<body class="book-detail-page">

<header class="detail-topbar">

    <a href="index.php" class="detail-brand">
        <span class="detail-brand-icon">📚</span>
        <span>
            <strong>Book Blossom</strong>
            <small>your little reading corner</small>
        </span>
    </a>

    <nav class="detail-nav">
        <a href="index.php">🏡 Home</a>
        <a href="add.php">✨ Add Book</a>
        <a href="logout.php">💗 Logout</a>
    </nav>

</header>

<main class="detail-main">

    <div class="detail-breadcrumb">
        <a href="index.php">My Library</a>
        <span> / </span>
        <a href="index.php?genre=<?= urlencode($book['genre']) ?>">
            <?= e($book['genre']) ?>
        </a>
        <span> / Book Details</span>
    </div>

    <article class="detail-card">

        
        <div class="detail-cover-panel">

            <img
                src="<?= e($coverSrc) ?>"
                alt="Cover of <?= e($book['title']) ?>"
                class="detail-cover"
                onerror="this.style.display='none';this.nextElementSibling.style.display='flex';"
            >

            <div class="detail-cover-placeholder" style="display:none;">
                <span class="placeholder-icon">📖</span>
                <strong><?= e($book['title']) ?></strong>
                <small><?= e($book['author']) ?></small>
            </div>

        </div>

        <div class="detail-information">

            <span class="detail-eyebrow">
                ♡ YOUR BOOK COLLECTION ♡
            </span>

            <h1 class="detail-title">
                <?= e($book['title']) ?>
            </h1>

            <p class="detail-subtitle">
                Written by <?= e($book['author']) ?>
            </p>

            <span class="detail-genre">
                🌸 <?= e($book['genre']) ?>
            </span>

            <dl class="detail-info-list">

                <div class="detail-info-row">
                    <dt>Author</dt>
                    <dd><?= e($book['author']) ?></dd>
                </div>

                <div class="detail-info-row">
                    <dt>Genre</dt>
                    <dd><?= e($book['genre']) ?></dd>
                </div>

                <div class="detail-info-row">
                    <dt>Published</dt>
                    <dd>
                        <?= $year > 0 ? e($year) : 'Not specified' ?>
                    </dd>
                </div>

                <div class="detail-info-row">
                    <dt>Description</dt>
                    <dd class="detail-description">
                        <?= trim((string)($book['description'] ?? '')) !== ''
                            ? nl2br(e($book['description']))
                            : 'No description has been added yet.'
                        ?>
                    </dd>
                </div>

            </dl>

            <div class="detail-actions">

                <a
                    href="index.php"
                    class="detail-button detail-button-back"
                >
                    ← Back to Library
                </a>

                <a
                    href="edit.php?id=<?= (int) $book['id'] ?>"
                    class="detail-button detail-button-edit"
                >
                    ✏️ Edit Book
                </a>

                <a
                    href="delete.php?id=<?= (int) $book['id'] ?>"
                    class="detail-button detail-button-delete"
                    onclick="return confirm('Are you sure you want to delete this book?');"
                >
                    🗑 Delete
                </a>

            </div>

        </div>

    </article>

</main>

<footer class="detail-footer">
    Made with 💗 for book lovers everywhere
</footer>

</body>
</html>

<?php
$conn->close();
?>
