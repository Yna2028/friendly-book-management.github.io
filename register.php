
<?php
session_start();

$error = "";

try {
    $pdo = new PDO(
        "mysql:host=localhost;dbname=book_blossom;charset=utf8mb4",
        "root",
        "",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    exit("Database connection failed. Check your XAMPP MySQL settings.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || $email === "" || $password === "") {
        $error = "Please fill in all fields.";
    } elseif (strlen($username) < 3 || strlen($username) > 50) {
        $error = "Username must be between 3 and 50 characters.";
    } elseif (strlen($email) > 254 ||
              !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } elseif (strlen($password) < 8) {
        $error = "Password must contain at least 8 characters.";
    } else {
        try {
            
            $check = $pdo->prepare(
                "SELECT id FROM users
                 WHERE username = :username OR email = :email
                 LIMIT 1"
            );

            $check->execute([
                "username" => $username,
                "email" => $email
            ]);

            if ($check->fetch()) {
                $error = "Username or email is already registered.";
            } else {
                
                $hashedPassword = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

                $stmt = $pdo->prepare(
                    "INSERT INTO users (username, email, password)
                     VALUES (:username, :email, :password)"
                );

                $stmt->execute([
                    "username" => $username,
                    "email" => $email,
                    "password" => $hashedPassword
                ]);

                header("Location: login.php?msg=registered");
                exit;
            }
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $error = "Registration failed. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Account | Book Blossom</title>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">

<style>
:root {
    --pink: #e979a3;
    --pink-dark: #d95f8e;
    --pink-light: #fff0f6;
    --pink-border: #f7d9e6;
    --cream: #fffaf6;
    --purple: #59465f;
    --muted: #a18ca1;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    min-height: 100vh;
    font-family: 'DM Sans', sans-serif;
    color: var(--purple);
    background:
        radial-gradient(circle at 15% 15%, #ffe2ed 0, transparent 30%),
        radial-gradient(circle at 85% 85%, #f0e5ff 0, transparent 30%),
        var(--cream);
    display: flex;
    flex-direction: column;
}


.navbar {
    min-height: 90px;
    width: 100%;
    padding: 0 7%;
    display: flex;
    align-items: center;
    background: rgba(255,255,255,.94);
    border-bottom: 1px solid var(--pink-border);
}

.brand {
    display: flex;
    align-items: center;
    gap: 14px;
    text-decoration: none;
}

.brand-icon {
    width: 54px;
    height: 54px;
    border-radius: 20px;
    background: #ffe5ef;
    display: grid;
    place-items: center;
    font-size: 28px;
}

.brand-name {
    color: var(--pink);
    font-size: 25px;
    font-weight: 700;
    letter-spacing: -.7px;
}

.brand-tagline {
    margin-top: 3px;
    color: var(--muted);
    font-size: 13px;
}

/* MAIN */
.main {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 35px 20px;
}

.register-card {
    width: 100%;
    max-width: 430px;
    background: rgba(255,255,255,.96);
    border: 1px solid #f7e0e9;
    border-radius: 28px;
    padding: 38px 35px;
    box-shadow: 0 18px 55px rgba(177,113,143,.12);
}

.card-heading {
    text-align: center;
    margin-bottom: 28px;
}

.card-logo {
    width: 68px;
    height: 68px;
    margin: 0 auto 17px;
    border-radius: 24px;
    display: grid;
    place-items: center;
    font-size: 32px;
    background: var(--pink-light);
}

.card-heading h1 {
    font-family: 'Playfair Display', serif;
    font-size: 30px;
    margin-bottom: 8px;
}

.card-heading p {
    color: var(--muted);
    font-size: 14px;
    line-height: 1.7;
}

/* FORM */
.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    font-size: 13px;
    font-weight: 700;
    margin-bottom: 8px;
}

.input-wrap {
    position: relative;
}

.form-group input {
    width: 100%;
    height: 50px;
    padding: 0 15px;
    border: 1px solid #ecdce5;
    border-radius: 13px;
    outline: none;
    font-family: inherit;
    font-size: 14px;
    color: var(--purple);
    background: #fffbfd;
    transition: .2s;
}

.form-group input:focus {
    border-color: var(--pink);
    box-shadow: 0 0 0 4px rgba(233,121,163,.12);
}

.form-group input::placeholder {
    color: #b8a7b7;
}

.password-input {
    padding-right: 48px !important;
}

.toggle-password {
    position: absolute;
    right: 13px;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    background: transparent;
    font-size: 17px;
    cursor: pointer;
}


.register-button {
    width: 100%;
    min-height: 51px;
    border: none;
    border-radius: 14px;
    background: linear-gradient(135deg, #ed8bb0, #dc6797);
    color: white;
    font-family: inherit;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 8px 18px rgba(221,103,151,.2);
    transition: transform .2s, box-shadow .2s;
    margin-top: 5px;
}

.register-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 22px rgba(221,103,151,.28);
}


.message {
    padding: 12px;
    border-radius: 12px;
    margin-bottom: 18px;
    text-align: center;
    font-size: 13px;
    line-height: 1.6;
}

.message.error {
    color: #b42355;
    background: #fff0f3;
    border: 1px solid #fbd5e0;
}


.divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 24px 0;
    color: #b4a1b3;
    font-size: 12px;
}

.divider::before,
.divider::after {
    content: "";
    flex: 1;
    height: 1px;
    background: #f0e2ea;
}

.login-text {
    text-align: center;
    font-size: 13px;
    color: #927e94;
    line-height: 1.8;
}

.login-text a {
    color: var(--pink-dark);
    font-weight: 700;
    text-decoration: none;
}

.login-text a:hover {
    text-decoration: underline;
}

.footer {
    text-align: center;
    padding: 20px;
    color: #b29eaf;
    font-size: 12px;
}

/* RESPONSIVE */
@media (max-width: 480px) {
    .navbar {
        min-height: 78px;
        padding: 0 20px;
    }

    .brand-name {
        font-size: 21px;
    }

    .main {
        padding: 25px 14px;
    }

    .register-card {
        padding: 28px 22px;
        border-radius: 23px;
    }

    .card-heading h1 {
        font-size: 27px;
    }
}
</style>
</head>

<body>

<header class="navbar">
    <a href="index.php" class="brand">
        <div class="brand-icon">📚</div>
        <div>
            <div class="brand-name">Book Blossom</div>
            <div class="brand-tagline">your little reading corner</div>
        </div>
    </a>
</header>

<main class="main">
    <section class="register-card">

        <div class="card-heading">
            <div class="card-logo">🌷</div>
            <h1>Create Account</h1>
            <p>Join our little reading community ♡</p>
        </div>

        <?php if ($error !== ""): ?>
            <div class="message error" role="alert">
                <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
            </div>
        <?php endif; ?>

        <form action="register.php" method="POST">

            <div class="form-group">
                <label for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Choose a username"
                    autocomplete="username"
                    minlength="3"
                    maxlength="50"
                    required
                >
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter your email address"
                    autocomplete="email"
                    maxlength="254"
                    required
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>

                <div class="input-wrap">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="password-input"
                        placeholder="Create a password (8+ characters)"
                        autocomplete="new-password"
                        minlength="8"
                        required
                    >

                    <button
                        type="button"
                        class="toggle-password"
                        id="togglePassword"
                        aria-label="Show password"
                    >👁️</button>
                </div>
            </div>

            <button type="submit" class="register-button">
                Create My Account ♡
            </button>

        </form>

        <div class="divider">Happy reading starts here</div>

        <p class="login-text">
            Already have an account?<br>
            <a href="login.php">Login to your library →</a>
        </p>

    </section>
</main>

<footer class="footer">
    Made with ♡ for book lovers everywhere
</footer>

<script>
const passwordInput = document.getElementById('password');
const toggleButton = document.getElementById('togglePassword');

toggleButton.addEventListener('click', function () {
    const isPassword = passwordInput.type === 'password';

    passwordInput.type = isPassword ? 'text' : 'password';
    toggleButton.textContent = isPassword ? '🙈' : '👁️';

    toggleButton.setAttribute(
        'aria-label',
        isPassword ? 'Hide password' : 'Show password'
    );
});
</script>

</body>
</html>
