
<?php
session_start();

$error = "";
$success = "";


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
    exit("Database connection failed. Please check your XAMPP MySQL settings.");
}

if (isset($_GET["msg"]) && $_GET["msg"] === "registered") {
    $success = "Account created successfully! Please log in.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($username === "" || $password === "") {
        $error = "Please enter your username and password.";
    } else {
        $stmt = $pdo->prepare(
            "SELECT id, username, email, password
             FROM users
             WHERE username = :username OR email = :email
             LIMIT 1"
        );

        $stmt->execute([
            "username" => $username,
            "email" => $username
        ]);

        $user = $stmt->fetch();

        if ($user && password_verify($password, $user["password"])) {
            session_regenerate_id(true);

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["email"] = $user["email"];

            
            header("Location: index.php");
            exit;
        } else {
            $error = "Invalid username/email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | Book Blossom</title>

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

/* HEADER */
.navbar {
    min-height: 90px;
    width: 100%;
    padding: 0 7%;
    display: flex;
    align-items: center;
    justify-content: space-between;
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

/* MAIN LAYOUT */
.main {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 45px 20px;
}

.login-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) 430px;
    gap: 65px;
    align-items: center;
    width: 100%;
    max-width: 1200px;
}

/* WELCOME SECTION */
.welcome {
    padding: 25px 0;
}

.eyebrow {
    color: var(--pink-dark);
    text-transform: uppercase;
    font-weight: 700;
    font-size: 12px;
    letter-spacing: 2px;
    margin-bottom: 20px;
}

.welcome h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(38px, 4vw, 55px);
    line-height: 1.2;
    margin-bottom: 20px;
    color: var(--purple);
}

.welcome h1 span {
    color: var(--pink);
}

.welcome p {
    color: #947f96;
    font-size: 16px;
    line-height: 1.9;
    max-width: 480px;
}

.book-art {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-top: 40px;
    padding: 30px;
    width: fit-content;
    border: 1px solid var(--pink-border);
    border-radius: 28px;
    background: linear-gradient(135deg, #ffe4ef, #f2e9ff);
}

.book {
    width: 65px;
    height: 100px;
    border-radius: 5px 10px 10px 5px;
    box-shadow: 4px 7px 12px #b88da333;
    transform: rotate(-7deg);
}

.book.one {
    background: #e8467c;
}

.book.two {
    background: #9bd96e;
    transform: rotate(5deg) translateY(-12px);
}

.book.three {
    background: #56c4e9;
    transform: rotate(9deg);
}

/* LOGIN CARD */
.login-card {
    width: 100%;
    background: rgba(255,255,255,.96);
    border: 1px solid #f7e0e9;
    border-radius: 30px;
    padding: 42px 38px;
    box-shadow: 0 18px 55px rgba(177,113,143,.12);
}

.card-heading {
    text-align: center;
    margin-bottom: 30px;
}

.card-logo {
    width: 78px;
    height: 78px;
    margin: 0 auto 20px;
    border-radius: 26px;
    display: grid;
    place-items: center;
    font-size: 35px;
    background: var(--pink-light);
}

.card-heading h2 {
    font-family: 'Playfair Display', serif;
    font-size: 32px;
    margin-bottom: 8px;
}

.card-heading p {
    color: var(--muted);
    font-size: 14px;
    line-height: 1.6;
}

/* MESSAGES */
.message {
    font-size: 13px;
    padding: 13px;
    border-radius: 13px;
    margin-bottom: 20px;
    text-align: center;
    line-height: 1.6;
}

.message.error {
    color: #b42355;
    background: #fff0f3;
    border: 1px solid #fbd5e0;
}

.message.success {
    color: #28764b;
    background: #eaf9f0;
    border: 1px solid #ccebd9;
}

/* FORM */
.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-size: 13px;
    font-weight: 700;
    margin-bottom: 9px;
}

.input-wrap {
    position: relative;
}

.form-group input {
    width: 100%;
    height: 54px;
    padding: 0 16px;
    border: 1px solid #ecdce5;
    border-radius: 15px;
    outline: none;
    font-family: inherit;
    font-size: 14px;
    color: var(--purple);
    background: #fffbfd;
    transition: border-color .2s, box-shadow .2s;
}

.form-group input:focus {
    border-color: var(--pink);
    box-shadow: 0 0 0 4px rgba(233,121,163,.12);
}

.form-group input::placeholder {
    color: #b8a7b7;
}

.password-input {
    padding-right: 50px !important;
}

.toggle-password {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    background: transparent;
    font-size: 17px;
    cursor: pointer;
}

/* FORM OPTIONS */
.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    margin: 4px 0 25px;
    font-size: 12px;
}

.remember {
    display: flex;
    align-items: center;
    gap: 7px;
    color: #8e7b91;
    cursor: pointer;
}

.remember input {
    accent-color: var(--pink);
}

a {
    color: var(--pink-dark);
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

/* LOGIN BUTTON */
.login-button {
    width: 100%;
    min-height: 54px;
    border: none;
    border-radius: 15px;
    background: linear-gradient(135deg, #ed8bb0, #dc6797);
    color: white;
    font-family: inherit;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 8px 18px rgba(221,103,151,.2);
    transition: transform .2s, box-shadow .2s;
}

.login-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 22px rgba(221,103,151,.28);
}

.divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 26px 0;
    color: #b4a1b3;
    font-size: 12px;
    white-space: nowrap;
}

.divider::before,
.divider::after {
    content: "";
    flex: 1;
    height: 1px;
    background: #f0e2ea;
}

.register-text {
    text-align: center;
    font-size: 13px;
    color: #927e94;
    line-height: 1.9;
}

.register-text a {
    font-weight: 700;
    color: var(--pink-dark);
}

/* FOOTER */
.footer {
    text-align: center;
    padding: 20px;
    color: #b29eaf;
    font-size: 12px;
}

/* RESPONSIVE */
@media (max-width: 900px) {
    .login-layout {
        grid-template-columns: 1fr 400px;
        gap: 30px;
    }

    .welcome h1 {
        font-size: 40px;
    }

    .login-card {
        padding: 35px 28px;
    }

    .book-art {
        gap: 14px;
        padding: 22px;
    }

    .book {
        width: 48px;
        height: 76px;
    }
}

@media (max-width: 700px) {
    .navbar {
        min-height: 78px;
        padding: 0 20px;
    }

    .brand-name {
        font-size: 21px;
    }

    .brand-icon {
        width: 48px;
        height: 48px;
    }

    .main {
        padding: 30px 16px;
    }

    .login-layout {
        grid-template-columns: 1fr;
        max-width: 430px;
        gap: 25px;
    }

    .welcome {
        text-align: center;
        padding: 0;
    }

    .welcome h1 {
        font-size: 37px;
    }

    .welcome p {
        margin: auto;
        font-size: 14px;
    }

    .book-art {
        display: none;
    }

    .eyebrow {
        margin-bottom: 12px;
    }

    .login-card {
        padding: 30px 24px;
    }

    .card-heading h2 {
        font-size: 29px;
    }
}

@media (max-width: 400px) {
    .main {
        padding: 22px 12px;
    }

    .login-card {
        padding: 26px 18px;
    }

    .welcome h1 {
        font-size: 31px;
    }

    .form-options {
        font-size: 11px;
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
    <div class="login-layout">

        <section class="welcome">
            <div class="eyebrow">♡ Welcome back, book lover ♡</div>

            <h1>
                Your next chapter<br>
                starts <span>here.</span> 🌸
            </h1>

            <p>
                Step back into your little reading corner.
                Discover beautiful stories, organize your collection,
                and let your imagination bloom.
            </p>

            <div class="book-art" aria-label="Colorful books">
                <div class="book one"></div>
                <div class="book two"></div>
                <div class="book three"></div>
                <span style="font-size:27px">🌸</span>
            </div>
        </section>

        <section class="login-card">

            <div class="card-heading">
                <div class="card-logo">🌷</div>
                <h2>Welcome Back!</h2>
                <p>Sign in to your lovely library</p>
            </div>

            <?php if ($error !== ""): ?>
                <div class="message error" role="alert">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <?php if ($success !== ""): ?>
                <div class="message success" role="status">
                    <?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form action="login.php" method="POST">

                <div class="form-group">
                    <label for="username">Username or Email</label>

                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Enter your username or email"
                        autocomplete="username"
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
                            placeholder="Enter your password"
                            autocomplete="current-password"
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

                <div class="form-options">
                    <label class="remember">
                        <input type="checkbox" name="remember">
                        Remember me
                    </label>

                    <a href="forgot_password.php">Forgot password?</a>
                </div>

                <button type="submit" class="login-button">
                    Login to My Library ♡
                </button>

            </form>

            <div class="divider">
                A little more reading awaits
            </div>

            <p class="register-text">
                New to Book Blossom?<br>
                <a href="register.php">Create your account →</a>
            </p>

        </section>

    </div>
</main>

<footer class="footer">
    Made with ♡ for book lovers everywhere
</footer>

<script>
const passwordInput = document.getElementById('password');
const toggleButton = document.getElementById('togglePassword');

toggleButton.addEventListener('click', function () {
    const showPassword = passwordInput.type === 'password';

    passwordInput.type = showPassword ? 'text' : 'password';

    toggleButton.textContent = showPassword ? '🙈' : '👁️';

    toggleButton.setAttribute(
        'aria-label',
        showPassword ? 'Hide password' : 'Show password'
    );
});
</script>

</body>
</html>
