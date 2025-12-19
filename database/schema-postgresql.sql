-- ============================================
-- PostgreSQL Schema for Hidrodinamika Link Profile
-- ============================================

-- Create Database
CREATE DATABASE hidrodinamika_links
    WITH 
    ENCODING = 'UTF8'
    LC_COLLATE = 'en_US.UTF-8'
    LC_CTYPE = 'en_US.UTF-8';

\c hidrodinamika_links;

-- ============================================
-- Table: users (Admin Users)
-- ============================================
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    full_name VARCHAR(100),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL
);

CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_users_email ON users(email);

-- ============================================
-- Table: profile (Profile Information)
-- ============================================
CREATE TABLE profile (
    id SERIAL PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    bio TEXT,
    logo_url VARCHAR(500),
    theme VARCHAR(20) DEFAULT 'dark',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- Table: links (Link Items)
-- ============================================
CREATE TABLE links (
    id SERIAL PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    subtitle VARCHAR(300),
    url VARCHAR(1000) NOT NULL,
    icon VARCHAR(50) DEFAULT 'link',
    is_active BOOLEAN DEFAULT TRUE,
    display_order INTEGER DEFAULT 0,
    click_count INTEGER DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_links_active ON links(is_active);
CREATE INDEX idx_links_order ON links(display_order);

-- ============================================
-- Table: sessions (Admin Sessions)
-- ============================================
CREATE TABLE sessions (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    session_token VARCHAR(255) NOT NULL UNIQUE,
    ip_address VARCHAR(45),
    user_agent TEXT,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_sessions_token ON sessions(session_token);
CREATE INDEX idx_sessions_expires ON sessions(expires_at);

-- ============================================
-- Table: analytics (Link Click Analytics)
-- ============================================
CREATE TABLE analytics (
    id SERIAL PRIMARY KEY,
    link_id INTEGER NOT NULL REFERENCES links(id) ON DELETE CASCADE,
    clicked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent TEXT,
    referrer VARCHAR(500),
    country VARCHAR(100),
    city VARCHAR(100)
);

CREATE INDEX idx_analytics_link_id ON analytics(link_id);
CREATE INDEX idx_analytics_clicked_at ON analytics(clicked_at);
CREATE INDEX idx_analytics_link_date ON analytics(link_id, clicked_at);

-- ============================================
-- Table: settings (Application Settings)
-- ============================================
CREATE TABLE settings (
    id SERIAL PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    description VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_settings_key ON settings(setting_key);

-- ============================================
-- Insert Default Data
-- ============================================

-- Default Admin User (password: admin123)
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
-- Views
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
    COUNT(a.id) FILTER (WHERE DATE(a.clicked_at) = CURRENT_DATE) as total_clicks_today,
    l.created_at,
    l.updated_at
FROM links l
LEFT JOIN analytics a ON l.id = a.link_id
WHERE l.is_active = TRUE
GROUP BY l.id
ORDER BY l.display_order ASC;

-- View: Active Sessions
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
-- Functions
-- ============================================

-- Function: Clean Expired Sessions
CREATE OR REPLACE FUNCTION fn_clean_expired_sessions()
RETURNS INTEGER AS $$
DECLARE
    deleted_count INTEGER;
BEGIN
    DELETE FROM sessions WHERE expires_at < NOW();
    GET DIAGNOSTICS deleted_count = ROW_COUNT;
    RETURN deleted_count;
END;
$$ LANGUAGE plpgsql;

-- Function: Update timestamp
CREATE OR REPLACE FUNCTION fn_update_timestamp()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- ============================================
-- Triggers
-- ============================================

-- Trigger: Update timestamp on users
CREATE TRIGGER tr_users_updated_at
    BEFORE UPDATE ON users
    FOR EACH ROW
    EXECUTE FUNCTION fn_update_timestamp();

-- Trigger: Update timestamp on profile
CREATE TRIGGER tr_profile_updated_at
    BEFORE UPDATE ON profile
    FOR EACH ROW
    EXECUTE FUNCTION fn_update_timestamp();

-- Trigger: Update timestamp on links
CREATE TRIGGER tr_links_updated_at
    BEFORE UPDATE ON links
    FOR EACH ROW
    EXECUTE FUNCTION fn_update_timestamp();

-- Trigger: Update timestamp on settings
CREATE TRIGGER tr_settings_updated_at
    BEFORE UPDATE ON settings
    FOR EACH ROW
    EXECUTE FUNCTION fn_update_timestamp();

-- Trigger: Update link click count
CREATE OR REPLACE FUNCTION fn_update_click_count()
RETURNS TRIGGER AS $$
BEGIN
    UPDATE links 
    SET click_count = click_count + 1 
    WHERE id = NEW.link_id;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER tr_update_click_count
    AFTER INSERT ON analytics
    FOR EACH ROW
    EXECUTE FUNCTION fn_update_click_count();

-- ============================================
-- Database Complete
-- ============================================
