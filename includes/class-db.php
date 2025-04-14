<?php
class CariFlow_DB {
    public static function create_tables() {
        global $wpdb;
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        
        $charset = $wpdb->get_charset_collate() . ' ENGINE=InnoDB';
        
        // Müşteriler Tablosu
        dbDelta("CREATE TABLE {$wpdb->prefix}cariflow_musteriler (
            id INT NOT NULL AUTO_INCREMENT,
            ad VARCHAR(100) NOT NULL,
            soyad VARCHAR(100) NOT NULL,
            telefon VARCHAR(20),
            email VARCHAR(100),
            kayit_tarihi DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset");
        
        // İşlemler Tablosu
        dbDelta("CREATE TABLE {$wpdb->prefix}cariflow_islemler (
            id INT NOT NULL AUTO_INCREMENT,
            musteri_id INT NOT NULL,
            tur ENUM('borç', 'alacak') NOT NULL,
            tutar DECIMAL(10,2) NOT NULL,
            aciklama TEXT,
            tarih DATETIME DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            FOREIGN KEY (musteri_id) 
                REFERENCES {$wpdb->prefix}cariflow_musteriler(id) ON DELETE CASCADE,
            INDEX idx_musteri_id (musteri_id),
            INDEX idx_tarih (tarih)
        ) $charset");
        
        dbDelta("CREATE INDEX idx_musteri_id ON {$wpdb->prefix}cariflow_islemler (musteri_id)");
    }
}