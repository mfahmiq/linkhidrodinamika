<?php
/**
 * Public Link Profile Page
 */

require_once 'includes/config.php';
require_once 'includes/Database.php';
require_once 'includes/functions.php';
require_once 'models/Profile.php';
require_once 'models/Link.php';

// Get profile and links
$profile = Profile::get();
$links = Link::getAll(true); // Only active links

// Get theme from cookie or use default
$theme = $_COOKIE['theme'] ?? $profile['theme'] ?? 'dark';
?>
<!DOCTYPE html>
<html lang="id" data-theme="<?= escape($theme) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= escape($profile['name']) ?> - Link Profile</title>
    <meta name="description" content="<?= escape($profile['bio']) ?>">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
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

    <div class="container">
        <div class="profile-card">
            <!-- Profile Header -->
            <div class="profile-header">
                <div class="profile-image-wrapper">
                    <img src="<?= escape($profile['logo_url']) ?>" alt="Profile Picture" class="profile-image">
                </div>
                <h1 class="profile-name"><?= escape($profile['name']) ?></h1>
                <p class="profile-bio"><?= escape($profile['bio']) ?></p>
            </div>

            <!-- Links Container -->
            <div class="links-container">
                <?php foreach ($links as $link): ?>
                <a href="<?= escape($link['url']) ?>" class="link-item" target="_blank" rel="noopener noreferrer">
                    <div class="link-content">
                        <div class="link-icon">
                            <?= getIconSVG($link['icon']) ?>
                        </div>
                        <div class="link-text">
                            <div class="link-title"><?= escape($link['title']) ?></div>
                            <?php if (!empty($link['subtitle'])): ?>
                            <div class="link-subtitle"><?= escape($link['subtitle']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="link-arrow">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>

            <!-- Footer -->
            <div class="profile-footer">
                <p class="copyright">© <?= date('Y') ?> <?= escape($profile['name']) ?>. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            
            html.setAttribute('data-theme', newTheme);
            
            // Save to cookie
            document.cookie = `theme=${newTheme}; path=/; max-age=31536000`; // 1 year
        }
    </script>
</body>
</html>
