<?php
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login">
    <form method="POST" class="login-form">
        <h2>🔒 Forgot Password</h2>
        <p>Enter your email to reset your password.</p>
        <input type="email" name="email" placeholder="Email Address" required>
        <button type="submit" name="reset">Send Reset Link</button>
        <p class="signup"><a href="login.php">Back to Login</a></p>
    </form>
</body>
</html>