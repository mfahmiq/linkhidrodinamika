<?php
/**
 * User Model
 */

class User {
    public static function authenticate($username, $password) {
        $db = Database::getInstance();
        
        $user = $db->fetchOne(
            "SELECT * FROM users WHERE username = ? AND is_active = TRUE",
            [$username]
        );
        
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        
        return false;
    }
    
    public static function getById($id) {
        $db = Database::getInstance();
        return $db->fetchOne("SELECT * FROM users WHERE id = ?", [$id]);
    }
    
    public static function updateLastLogin($id) {
        $db = Database::getInstance();
        return $db->execute("UPDATE users SET last_login = NOW() WHERE id = ?", [$id]);
    }
    
    public static function changePassword($id, $newPassword) {
        $db = Database::getInstance();
        $hash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => BCRYPT_COST]);
        return $db->execute("UPDATE users SET password_hash = ? WHERE id = ?", [$hash, $id]);
    }
}
