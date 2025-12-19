<?php
/**
 * Profile Model
 */

class Profile {
    public static function get() {
        $db = Database::getInstance();
        $profile = $db->fetchOne("SELECT * FROM profile LIMIT 1");
        
        if (!$profile) {
            // Return default if not exists
            return [
                'name' => 'PT. Hidro Dinamika Internasional',
                'bio' => 'Connecting Innovation & Excellence',
                'logo_url' => 'assets/images/logo-hdi.png',
                'theme' => 'dark'
            ];
        }
        
        return $profile;
    }
    
    public static function update($data) {
        $db = Database::getInstance();
        
        // Check if profile exists
        $existing = $db->fetchOne("SELECT id FROM profile LIMIT 1");
        
        if ($existing) {
            // Update existing
            return $db->execute(
                "UPDATE profile SET name = ?, bio = ?, logo_url = ?, theme = ? WHERE id = ?",
                [
                    $data['name'],
                    $data['bio'],
                    $data['logo_url'] ?? 'assets/images/logo-hdi.png',
                    $data['theme'] ?? 'dark',
                    $existing['id']
                ]
            );
        } else {
            // Insert new
            return $db->execute(
                "INSERT INTO profile (name, bio, logo_url, theme) VALUES (?, ?, ?, ?)",
                [
                    $data['name'],
                    $data['bio'],
                    $data['logo_url'] ?? 'assets/images/logo-hdi.png',
                    $data['theme'] ?? 'dark'
                ]
            );
        }
    }
}
