<?php
include("db.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = mysqli_query($conn, "SELECT * FROM books WHERE id=$id");
    $book = mysqli_fetch_assoc($result);

    if (!$book) {
        die("Book not found!");
    }
} else {
    die("No book ID provided!");
}

if (isset($_POST['update'])) {
    $title       = mysqli_real_escape_string($conn, $_POST['title']);
    $author      = mysqli_real_escape_string($conn, $_POST['author']);
    $genre       = mysqli_real_escape_string($conn, $_POST['genre']);
    $year        = intval($_POST['year']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    $cover = $book['cover']; 

    if (!empty($_FILES['cover']['name'])) {
        $newCover = basename($_FILES['cover']['name']);
        $target   = "uploads/" . $newCover;

        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (in_array($_FILES['cover']['type'], $allowedTypes)) {
            if (move_uploaded_file($_FILES['cover']['tmp_name'], $target)) {
                $cover = $newCover;
            } else {
                echo "Failed to upload new cover image.";
            }
        } else {
            echo "Invalid file type. Only JPG and PNG allowed.";
        }
    }

    $query = "UPDATE books 
              SET title='$title', author='$author', genre='$genre', year='$year', description='$description', cover='$cover' 
              WHERE id=$id";

    if (mysqli_query($conn, $query)) {
        header("Location: index.php?msg=Book updated successfully");
        exit();
    } else {
        echo "Error updating book: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Book</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <form method="POST" action="edit.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
        <h2>Edit Book</h2>

        <input type="text" name="title" value="<?php echo htmlspecialchars($book['title']); ?>" required>
        <input type="text" name="author" value="<?php echo htmlspecialchars($book['author']); ?>" required>
        <input type="text" name="genre" value="<?php echo htmlspecialchars($book['genre']); ?>" required>
        <input type="number" name="year" value="<?php echo htmlspecialchars($book['year']); ?>" required>
        <textarea name="description" rows="4"><?php echo htmlspecialchars($book['description']); ?></textarea>

        <div class="current-cover">
            <p><strong>Current Cover:</strong></p>
            <img src="uploads/<?php echo htmlspecialchars($book['cover']); ?>" alt="Cover" style="width:150px;border-radius:8px;">
        </div>

        <p><strong>Upload New Cover (optional):</strong></p>
        <input type="file" name="cover" accept="image/*">

        <button type="submit" name="update">Update Book</button>
        <button type="button" onclick="window.location.href='index.php'">Back</button>
    </form>
</body>
</html>