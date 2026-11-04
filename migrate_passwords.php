<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("db.php");

$result = $conn->query("SELECT id, username, password FROM users");

while ($row = $result->fetch_assoc()) {
    $id       = $row['id'];
    $username = $row['username'];
    $password = $row['password'];

    
    if (strlen($password) < 60) {
        
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        
        $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
        $stmt->bind_param("si", $hashed, $id);
        if ($stmt->execute()) {
            echo "Updated user {$username} (ID: {$id})\n";
        } else {
            echo "Failed to update user {$username} (ID: {$id})\n";
        }
    } else {
        echo "User {$username} (ID: {$id}) already has a hashed password.\n";
    }
}

echo "Migration complete.";
?>