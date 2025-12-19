<?php
/**
 * Login Page
 */

require_once 'includes/config.php';
require_once 'includes/Database.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

startSession();

// If already logged in, redirect to admin
if (isAuthenticated()) {
    redirect('admin.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (login($username, $password)) {
        redirect('admin.php');
    } else {
        $error = 'Username atau password salah!';
    }
}

$theme = $_COOKIE['theme'] ?? 'dark';
?>
<!DOCTYPE html>
<html lang="id" data-theme="<?= escape($theme) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Hidrodinamika</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="admin-body">
    <!-- Theme Toggle -->
    <button class="theme-toggle" onclick="toggleTheme()" aria-label="Toggle theme">
        <svg class="sun-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="5"/>
            <line x1="12" y1="1" x2="12" y2="3"/>
            <line x1="12" y1="21" x2="12" y2="23"/>
            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
            <line x1="1" y1="12" x2="3" y2="12"/>
            <line x1="21" y1="12" x2="23" y2="12"/>
            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
        </svg>
        <svg class="moon-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
        </svg>
    </button>

    <div class="login-container">
        <div class="login-card">
            <!-- Logo -->
            <div class="login-header">
                <img src="assets/images/logo-hdi.png" alt="Hidrodinamika Logo" class="login-logo">
                <h1 class="login-title">Admin Login</h1>
                <p class="login-subtitle">Masuk ke Admin Panel Hidrodinamika</p>
            </div>

            <!-- Login Form -->
            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-with-icon">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <input type="text" id="username" name="username" class="form-input with-icon" placeholder="Masukkan username" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-with-icon">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0110 0v4"/>
                        </svg>
                        <input type="password" id="password" name="password" class="form-input with-icon" placeholder="Masukkan password" required>
                    </div>
                </div>

                <?php if ($error): ?>
                <div class="error-message show">
                    ❌ <?= escape($error) ?>
                </div>
                <?php endif; ?>

                <button type="submit" class="btn-login">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3"/>
                    </svg>
                    Masuk
                </button>
            </form>

            <!-- Info -->
            <div class="login-info">
                <p class="info-text">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="16" x2="12" y2="12"/>
                        <line x1="12" y1="8" x2="12.01" y2="8"/>
                    </svg>
                    Default credentials: <strong>admin</strong> / <strong>admin123</strong>
                </p>
            </div>

            <!-- Back to Home -->
            <div class="login-footer">
                <a href="index.php" class="back-link">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Kembali ke Halaman Utama
                </a>
            </div>
        </div>
    </div>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            html.setAttribute('data-theme', newTheme);
            document.cookie = `theme=${newTheme}; path=/; max-age=31536000`;
        }
    </script>
</body>
</html>
