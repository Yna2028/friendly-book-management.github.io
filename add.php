<?php
include("db.php");
session_start();

// Require login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$error = "";

if (isset($_POST['add'])) {
    $title       = mysqli_real_escape_string($conn, $_POST['title']);
    $author      = mysqli_real_escape_string($conn, $_POST['author']);
    $year        = mysqli_real_escape_string($conn, $_POST['year']);
    $genre       = mysqli_real_escape_string($conn, $_POST['genre']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    if (!empty($_FILES['cover']['name'])) {
        $cover  = basename($_FILES['cover']['name']);
        $target = "uploads/" . $cover;

        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (in_array($_FILES['cover']['type'], $allowedTypes)) {
            if (move_uploaded_file($_FILES['cover']['tmp_name'], $target)) {
                $sql = "INSERT INTO books (title, author, year, genre, cover, description) 
                        VALUES ('$title','$author','$year','$genre','$cover','$description')";
                if (mysqli_query($conn, $sql)) {
                    header("Location: index.php?msg=Book added successfully");
                    exit();
                } else {
                    $error = "Database error: " . mysqli_error($conn);
                }
            } else {
                $error = "Failed to upload cover image.";
            }
        } else {
            $error = "Invalid file type. Only JPG and PNG allowed.";
        }
    } else {
        $error = "Please upload a book cover.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            background: url('uploads/book.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: "Segoe UI", Arial, sans-serif;
        }
        .overlay {
            background: rgba(255,255,255,0.9);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            width: 400px;
            text-align: center;
        }
    </style>
</head>
<body>
    <form method="POST" enctype="multipart/form-data" class="overlay">
        <h2>➕ Add Book</h2>
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

        <input type="text" name="title" placeholder="Book Title" required>
        <input type="text" name="author" placeholder="Author" required>
        <input type="number" name="year" placeholder="Year" required>
        <input type="text" name="genre" placeholder="Genre" required>
        <input type="file" name="cover" accept="image/*" required>
        <textarea name="description" placeholder="Short Description" rows="4" required></textarea>

        <button type="button" onclick="window.location.href='index.php'">⬅ Back</button>
        <button type="submit" name="add">Add Book</button>
    </form>
</body>
</html>