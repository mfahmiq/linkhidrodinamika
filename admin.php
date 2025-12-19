<?php
/**
 * Admin Panel
 */

// Prevent caching
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

require_once 'includes/config.php';
require_once 'includes/Database.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';
require_once 'models/Profile.php';
require_once 'models/Link.php';

// Require authentication
requireAuth();

// Handle AJAX requests
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    $action = $_POST['action'];
    $response = ['success' => false];
    
    try {
        switch ($action) {
            case 'update_profile':
                Profile::update([
                    'name' => $_POST['name'],
                    'bio' => $_POST['bio'],
                    'logo_url' => 'assets/images/logo-hdi.png'
                ]);
                $response = ['success' => true, 'message' => 'Profile updated successfully'];
                break;
                
            case 'create_link':
                $id = Link::create([
                    'title' => $_POST['title'],
                    'subtitle' => $_POST['subtitle'] ?? '',
                    'url' => $_POST['url'],
                    'icon' => $_POST['icon'] ?? 'link',
                    'is_active' => isset($_POST['is_active']) ? (bool)$_POST['is_active'] : true,
                    'display_order' => (int)($_POST['display_order'] ?? 0)
                ]);
                $response = ['success' => true, 'id' => $id, 'message' => 'Link created successfully'];
                break;
                
            case 'update_link':
                Link::update($_POST['id'], [
                    'title' => $_POST['title'],
                    'subtitle' => $_POST['subtitle'] ?? '',
                    'url' => $_POST['url'],
                    'icon' => $_POST['icon'] ?? 'link',
                    'is_active' => isset($_POST['is_active']) ? (bool)$_POST['is_active'] : true,
                    'display_order' => (int)($_POST['display_order'] ?? 0)
                ]);
                $response = ['success' => true, 'message' => 'Link updated successfully'];
                break;
                
            case 'delete_link':
                Link::delete($_POST['id']);
                $response = ['success' => true, 'message' => 'Link deleted successfully'];
                break;
                
            case 'toggle_link':
                Link::toggleActive($_POST['id']);
                $response = ['success' => true, 'message' => 'Link status toggled'];
                break;
        }
    } catch (Exception $e) {
        $response = ['success' => false, 'message' => $e->getMessage()];
    }
    
    echo json_encode($response);
    exit;
}

// Get data for display
$profile = Profile::get();
$links = Link::getAll();
$theme = $_COOKIE['theme'] ?? 'dark';
?>
<!DOCTYPE html>
<html lang="id" data-theme="<?= escape($theme) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Hidrodinamika</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="admin-body">
    <!-- Theme Toggle -->
    <button class="theme-toggle" onclick="toggleTheme()" aria-label="Toggle theme">
        <svg class="sun-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/>
            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
            <line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/>
            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
        </svg>
        <svg class="moon-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
        </svg>
    </button>

    <div class="admin-container">
        <!-- Header -->
        <div class="admin-header">
            <div class="admin-header-left">
                <img src="assets/images/logo-hdi.png" alt="Logo" class="admin-logo">
                <h1 class="admin-title">Admin Panel</h1>
            </div>
            <div class="admin-header-right">
                <button class="btn-secondary" onclick="window.location.href='index.php'">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    View Public Page
                </button>
                <button class="btn-logout" onclick="window.location.href='logout.php'">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/>
                    </svg>
                    Logout
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <div class="admin-content">
            <!-- Profile Settings -->
            <div class="admin-section">
                <div class="section-header">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <h2>Profile Settings</h2>
                </div>
                
                <form id="profileForm" class="profile-settings">
                    <div class="profile-image-section">
                        <img src="assets/images/logo-hdi.png" alt="Profile" class="admin-profile-image">
                        <div class="image-upload-info">
                            <p class="image-note">Logo Hidrodinamika</p>
                            <p class="image-hint">Menggunakan logo-hdi.png</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="profileName">Display Name</label>
                        <input type="text" id="profileName" name="name" class="form-input" value="<?= escape($profile['name']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="profileBio">Bio</label>
                        <textarea id="profileBio" name="bio" class="form-textarea" rows="3" maxlength="200"><?= escape($profile['bio']) ?></textarea>
                        <span class="char-count"><span id="bioCount"><?= strlen($profile['bio']) ?></span>/200</span>
                    </div>
                    
                    <button type="submit" class="btn-save">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Profile
                    </button>
                </form>
            </div>

            <!-- Links Management -->
            <div class="admin-section">
                <div class="section-header">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    <h2>Links</h2>
                </div>

                <button class="btn-add-link" onclick="addNewLink()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 4v16m8-8H4"/>
                    </svg>
                    Add New Link
                </button>

                <div id="linksContainer">
                    <?php foreach ($links as $index => $link): ?>
                    <div class="admin-link-item" data-id="<?= $link['id'] ?>">
                        <div class="admin-link-header">
                            <div class="drag-handle">
                                <svg class="drag-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/>
                                </svg>
                                <span style="color: var(--text-secondary); font-size: 14px; font-weight: 600;">Link <?= $index + 1 ?></span>
                            </div>
                            <div class="link-status">
                                <div class="toggle-switch <?= $link['is_active'] ? 'active' : '' ?>" onclick="toggleLink(<?= $link['id'] ?>)"></div>
                                <span class="status-label"><?= $link['is_active'] ? 'Active' : 'Inactive' ?></span>
                                <button class="btn-delete" onclick="deleteLink(<?= $link['id'] ?>)">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 01-2 2H7a2 2 0 01-2-2V6m3 0V4a2 2 0 012-2h4a2 2 0 012 2v2"/>
                                        <line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="admin-link-fields">
                            <input type="hidden" name="link_id" value="<?= $link['id'] ?>">
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" name="title" class="form-input" value="<?= escape($link['title']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Subtitle (Optional)</label>
                                <input type="text" name="subtitle" class="form-input" value="<?= escape($link['subtitle']) ?>">
                            </div>
                            <div class="form-group">
                                <label>URL</label>
                                <input type="url" name="url" class="form-input" value="<?= escape($link['url']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Icon</label>
                                <select name="icon" class="form-input">
                                    <?php
                                    $icons = ['globe' => '🌐 Globe', 'linkedin' => '💼 LinkedIn', 'instagram' => '📸 Instagram', 
                                              'email' => '✉️ Email', 'whatsapp' => '💬 WhatsApp', 'link' => '🔗 Link', 'logo-hdi' => '🏢 Logo HDI'];
                                    foreach ($icons as $value => $label):
                                    ?>
                                    <option value="<?= $value ?>" <?= $link['icon'] === $value ? 'selected' : '' ?>><?= $label ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <button type="button" class="btn-save" onclick="updateLink(<?= $link['id'] ?>)">Update Link</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Save Status -->
            <div id="saveStatus" class="save-status"></div>
        </div>
    </div>

    <script src="assets/js/admin.js"></script>
</body>
</html>
