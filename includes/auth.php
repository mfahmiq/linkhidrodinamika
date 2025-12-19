<?php
/**
 * Authentication Functions
 */

function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_name(SESSION_NAME);
        session_set_cookie_params([
            'lifetime' => SESSION_LIFETIME,
            'path' => '/',
            'secure' => false, // Set to true in production with HTTPS
            'httponly' => true,
            'samesite' => 'Lax'
        ]);
        session_start();
    }
}

function login($username, $password) {
    require_once MODELS_PATH . '/User.php';
    
    $user = User::authenticate($username, $password);
    
    if ($user) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();
        
        // Update last login
        User::updateLastLogin($user['id']);
        
        return true;
    }
    
    return false;
}

function logout() {
    startSession();
    $_SESSION = [];
    session_destroy();
    setcookie(SESSION_NAME, '', time() - 3600, '/');
}

function isAuthenticated() {
    startSession();
    
    if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
        return false;
    }
    
    // Check session timeout
    if (isset($_SESSION['login_time'])) {
        if (time() - $_SESSION['login_time'] > SESSION_LIFETIME) {
            logout();
            return false;
        }
    }
    
    return true;
}

function requireAuth() {
    if (!isAuthenticated()) {
        header('Location: login.php');
        exit;
    }
}

function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

function getCurrentUsername() {
    return $_SESSION['username'] ?? null;
}
