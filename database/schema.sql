-- ============================================
-- Database Schema for Hidrodinamika Link Profile
-- ============================================

-- Create Database
CREATE DATABASE IF NOT EXISTS hidrodinamika_links
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE hidrodinamika_links;

-- ============================================
-- Table: users (Admin Users)
-- ============================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    full_name VARCHAR(100),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    INDEX idx_username (username),
    INDEX idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: profile (Profile Information)
-- ============================================
CREATE TABLE profile (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    bio TEXT,
    logo_url VARCHAR(500),
    theme VARCHAR(20) DEFAULT 'dark',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: links (Link Items)
-- ============================================
CREATE TABLE links (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    subtitle VARCHAR(300),
    url VARCHAR(1000) NOT NULL,
    icon VARCHAR(50) DEFAULT 'link',
    is_active BOOLEAN DEFAULT TRUE,
    display_order INT DEFAULT 0,
    click_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_active (is_active),
    INDEX idx_order (display_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: sessions (Admin Sessions)
-- ============================================
CREATE TABLE sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    session_token VARCHAR(255) NOT NULL UNIQUE,
    ip_address VARCHAR(45),
    user_agent TEXT,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_token (session_token),
    INDEX idx_expires (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: analytics (Link Click Analytics)
-- ============================================
CREATE TABLE analytics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    link_id INT NOT NULL,
    clicked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent TEXT,
    referrer VARCHAR(500),
    country VARCHAR(100),
    city VARCHAR(100),
    FOREIGN KEY (link_id) REFERENCES links(id) ON DELETE CASCADE,
    INDEX idx_link_id (link_id),
    INDEX idx_clicked_at (clicked_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Table: settings (Application Settings)
-- ============================================
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    description VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- Insert Default Data
-- ============================================

-- Default Admin User (password: admin123)
-- Password hash generated using bcrypt with cost 10
INSERT INTO users (username, password_hash, email, full_name) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@hidrodinamika.com', 'Administrator');

-- Default Profile
INSERT INTO profile (name, bio, logo_url, theme) VALUES
('PT. Hidro Dinamika Internasional', 'Connecting Innovation & Excellence', 'logo-hdi.png', 'dark');

-- Default Links
INSERT INTO links (title, subtitle, url, icon, is_active, display_order) VALUES
('Website', 'Visit our official website', 'https://hidrodinamika.com', 'globe', TRUE, 1),
('Database Gudang', 'Management Database Gudang', 'https://overflorid-kaliyah-nondecisive.ngrok-free.dev/gudang_hdi/auth/login.php', 'logo-hdi', TRUE, 2),
('Project Management Plasmalisis', 'Project Monitoring', 'https://overflorid-kaliyah-nondecisive.ngrok-free.dev/web_plasmalisis/?action=login', 'logo-hdi', TRUE, 3);

-- Default Settings
INSERT INTO settings (setting_key, setting_value, description) VALUES
('site_title', 'Hidrodinamika Link Profile', 'Website title'),
('maintenance_mode', 'false', 'Enable/disable maintenance mode'),
('analytics_enabled', 'true', 'Enable/disable analytics tracking'),
('session_timeout', '86400', 'Session timeout in seconds (24 hours)');

-- ============================================
-- Views for Easy Querying
-- ============================================

-- View: Active Links with Analytics
CREATE VIEW v_active_links AS
SELECT 
    l.id,
    l.title,
    l.subtitle,
    l.url,
    l.icon,
    l.display_order,
    l.click_count,
    COUNT(a.id) as total_clicks_today,
    l.created_at,
    l.updated_at
FROM links l
LEFT JOIN analytics a ON l.id = a.link_id 
    AND DATE(a.clicked_at) = CURDATE()
WHERE l.is_active = TRUE
ORDER BY l.display_order ASC;

-- View: Session Summary
CREATE VIEW v_active_sessions AS
SELECT 
    s.id,
    s.session_token,
    u.username,
    u.email,
    s.ip_address,
    s.expires_at,
    s.created_at
FROM sessions s
JOIN users u ON s.user_id = u.id
WHERE s.expires_at > NOW();

-- ============================================
-- Stored Procedures
-- ============================================

-- Procedure: Clean Expired Sessions
DELIMITER //
CREATE PROCEDURE sp_clean_expired_sessions()
BEGIN
    DELETE FROM sessions WHERE expires_at < NOW();
END //
DELIMITER ;

-- Procedure: Get Link Analytics
DELIMITER //
CREATE PROCEDURE sp_get_link_analytics(IN link_id_param INT, IN days_param INT)
BEGIN
    SELECT 
        DATE(clicked_at) as date,
        COUNT(*) as clicks
    FROM analytics
    WHERE link_id = link_id_param
        AND clicked_at >= DATE_SUB(CURDATE(), INTERVAL days_param DAY)
    GROUP BY DATE(clicked_at)
    ORDER BY date DESC;
END //
DELIMITER ;

-- ============================================
-- Triggers
-- ============================================

-- Trigger: Update link click count
DELIMITER //
CREATE TRIGGER tr_update_click_count
AFTER INSERT ON analytics
FOR EACH ROW
BEGIN
    UPDATE links 
    SET click_count = click_count + 1 
    WHERE id = NEW.link_id;
END //
DELIMITER ;

-- ============================================
-- Indexes for Performance
-- ============================================

-- Additional composite indexes
CREATE INDEX idx_analytics_link_date ON analytics(link_id, clicked_at);
CREATE INDEX idx_sessions_user_expires ON sessions(user_id, expires_at);

-- ============================================
-- Database Complete
-- ============================================
