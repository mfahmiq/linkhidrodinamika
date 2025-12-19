<?php
/**
 * Link Model
 */

class Link {
    public static function getAll($activeOnly = false) {
        $db = Database::getInstance();
        
        $sql = "SELECT * FROM links";
        if ($activeOnly) {
            $sql .= " WHERE is_active = TRUE";
        }
        // Order by ID: older links (lower ID) first, newer links (higher ID) last
        $sql .= " ORDER BY id ASC";
        
        return $db->fetchAll($sql);
    }
    
    public static function getById($id) {
        $db = Database::getInstance();
        return $db->fetchOne("SELECT * FROM links WHERE id = ?", [$id]);
    }
    
    public static function create($data) {
        $db = Database::getInstance();
        
        // No need to calculate display_order, we use ID for ordering
        // Just use provided display_order or default to 0
        $displayOrder = $data['display_order'] ?? 0;
        
        $db->execute(
            "INSERT INTO links (title, subtitle, url, icon, is_active, display_order) 
             VALUES (?, ?, ?, ?, ?, ?)",
            [
                $data['title'],
                $data['subtitle'] ?? '',
                $data['url'],
                $data['icon'] ?? 'link',
                $data['is_active'] ?? true,
                $displayOrder
            ]
        );
        
        return $db->lastInsertId();
    }
    
    public static function update($id, $data) {
        $db = Database::getInstance();
        
        $updates = [];
        $params = [];
        
        $allowedFields = ['title', 'subtitle', 'url', 'icon', 'is_active', 'display_order'];
        foreach ($allowedFields as $field) {
            if (isset($data[$field])) {
                $updates[] = "$field = ?";
                $params[] = $data[$field];
            }
        }
        
        if (empty($updates)) {
            return 0;
        }
        
        $params[] = $id;
        $sql = "UPDATE links SET " . implode(', ', $updates) . " WHERE id = ?";
        
        return $db->execute($sql, $params);
    }
    
    public static function delete($id) {
        $db = Database::getInstance();
        return $db->execute("DELETE FROM links WHERE id = ?", [$id]);
    }
    
    public static function toggleActive($id) {
        $db = Database::getInstance();
        return $db->execute("UPDATE links SET is_active = NOT is_active WHERE id = ?", [$id]);
    }
}
