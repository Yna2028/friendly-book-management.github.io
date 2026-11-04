<?php
include("db.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $query = "DELETE FROM books WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        header("Location: index.php?msg=Book deleted successfully");
        exit();
    } else {
        echo "Error deleting book: " . mysqli_error($conn);
    }
} else {
    die("No book ID provided!");
}
?>