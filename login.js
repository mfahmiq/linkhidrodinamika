// Admin credentials (in production, this should be handled server-side)
const ADMIN_CREDENTIALS = {
    username: 'admin',
    password: 'admin123'
};

// Theme toggle functionality
function toggleTheme() {
    const html = document.documentElement;
    const currentTheme = html.getAttribute('data-theme');
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';

    html.setAttribute('data-theme', newTheme);
    localStorage.setItem('theme', newTheme);
}

// Load saved theme
function loadTheme() {
    const savedTheme = localStorage.getItem('theme') || 'dark';
    document.documentElement.setAttribute('data-theme', savedTheme);
}

// Handle login
function handleLogin(event) {
    event.preventDefault();

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const errorMessage = document.getElementById('errorMessage');

    // Clear previous error
    errorMessage.textContent = '';
    errorMessage.classList.remove('show');

    // Validate credentials
    if (username === ADMIN_CREDENTIALS.username && password === ADMIN_CREDENTIALS.password) {
        // Set session
        const sessionToken = generateSessionToken();
        localStorage.setItem('adminSession', sessionToken);
        localStorage.setItem('adminSessionExpiry', Date.now() + (24 * 60 * 60 * 1000)); // 24 hours

        // Show success and redirect
        showSuccess();
        setTimeout(() => {
            window.location.href = 'admin.html';
        }, 1000);
    } else {
        // Show error
        errorMessage.textContent = '❌ Username atau password salah!';
        errorMessage.classList.add('show');

        // Shake animation
        const form = document.getElementById('loginForm');
        form.classList.add('shake');
        setTimeout(() => {
            form.classList.remove('shake');
        }, 500);
    }
}

// Generate session token
function generateSessionToken() {
    return 'admin_' + Math.random().toString(36).substring(2) + Date.now().toString(36);
}

// Show success message
function showSuccess() {
    const errorMessage = document.getElementById('errorMessage');
    errorMessage.textContent = '✅ Login berhasil! Mengalihkan...';
    errorMessage.classList.add('show', 'success');
}

// Check if already logged in
function checkExistingSession() {
    const session = localStorage.getItem('adminSession');
    const expiry = localStorage.getItem('adminSessionExpiry');

    if (session && expiry && Date.now() < parseInt(expiry)) {
        // Already logged in, redirect to admin
        window.location.href = 'admin.html';
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    loadTheme();
    checkExistingSession();
});
