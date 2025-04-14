<?php
class CariFlow_Admin {
    public static function init() {
        add_menu_page(
            'CariFlow', 
            'CariFlow', 
            'manage_options', 
            'cariflow', 
            [__CLASS__, 'ana_sayfa'], 
            'dashicons-money-alt'
        );
        
        add_submenu_page(
            'cariflow',
            'Müşteriler',
            'Müşteriler',
            'manage_options',
            'cariflow-musteriler',
            [__CLASS__, 'musteri_listele']
        );
        
        add_submenu_page(
            'cariflow',
            'İşlem Ekle',
            'İşlem Ekle',
            'manage_options',
            'cariflow-islem-ekle',
            [__CLASS__, 'islem_ekle']
        );
        
        add_submenu_page(
            null, // parent_slug -> gizli menü
            'Müşteri Detay',
            'Müşteri Detay',
            'manage_options',
            'cariflow-musteri-detay',
            [__CLASS__, 'musteri_detay']
        );
    }

    public static function ana_sayfa() {
        echo '<div class="wrap"><h1>CariFlow Yönetim Paneli</h1></div>';
    }

    public static function musteri_listele() {
        global $wpdb;
        
        echo '<td>
    <a href="'.admin_url('admin.php?page=cariflow-musteri-detay&id='.$musteri->id).'" class="button">Detay</a>
    </td>';
        
        // Müşteri Ekleme
        if (isset($_POST['musteri_ekle'])) {
        $data = [
            'ad' => sanitize_text_field($_POST['ad']),
            'soyad' => sanitize_text_field($_POST['soyad']),
            'telefon' => sanitize_text_field($_POST['telefon']),
            'email' => sanitize_email($_POST['email'])
        ];
        
        $result = $wpdb->insert("{$wpdb->prefix}cariflow_musteriler", $data);
        
        if($result === false) {
            error_log("Müşteri ekleme hatası: ".$wpdb->last_error);
            echo '<div class="notice notice-error"><p>Kayıt başarısız oldu!</p></div>';
        } else {
            echo '<div class="notice notice-success"><p>Kayıt başarılı!</p></div>';
        }
    }
        
        // Müşteri Listesi
        $musteriler = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cariflow_musteriler");
        ?>
        <div class="wrap">
            <h1>Müşteri Yönetimi</h1>
            
            <form method="post" class="cariflow-form">
                <h2>Yeni Müşteri Ekle</h2>
                <div class="form-group">
                    <label>Ad:</label>
                    <input type="text" name="ad" required>
                </div>
                <div class="form-group">
                    <label>Soyad:</label>
                    <input type="text" name="soyad" required>
                </div>
                <div class="form-group">
                    <label>Telefon:</label>
                    <input type="text" name="telefon">
                </div>
                <div class="form-group">
                    <label>E-posta:</label>
                    <input type="email" name="email">
                </div>
                <button type="submit" name="musteri_ekle" class="button button-primary">Kaydet</button>
            </form>
            
            <h2>Müşteri Listesi</h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Ad</th>
                        <th>Soyad</th>
                        <th>Telefon</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($musteriler as $musteri): ?>
                    <tr>
                        <td><?= $musteri->id ?></td>
                        <td><?= esc_html($musteri->ad) ?></td>
                        <td><?= esc_html($musteri->soyad) ?></td>
                        <td><?= esc_html($musteri->telefon) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    public static function islem_ekle() {
        global $wpdb;
        
        if (isset($_POST['islem_ekle'])) {
            $wpdb->insert("{$wpdb->prefix}cariflow_islemler", [
                'musteri_id' => intval($_POST['musteri_id']),
                'tur' => sanitize_text_field($_POST['tur']),
                'tutar' => floatval($_POST['tutar']),
                'aciklama' => sanitize_textarea_field($_POST['aciklama'])
            ]);
            echo '<div class="notice notice-success"><p>İşlem eklendi!</p></div>';
        }
        
        $musteriler = $wpdb->get_results("SELECT id, ad, soyad FROM {$wpdb->prefix}cariflow_musteriler");
        ?>
        <div class="wrap">
            <h1>Yeni İşlem Ekle</h1>
            
            <form method="post" class="cariflow-form">
                <div class="form-group">
                    <label>Müşteri:</label>
                    <select name="musteri_id" required>
    <?php foreach ($musteriler as $musteri): ?>
    <option value="<?= $musteri->id ?>" <?= selected($selected_musteri, $musteri->id) ?>>
        <?= esc_html($musteri->ad . ' ' . $musteri->soyad) ?>
    </option>
    <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>İşlem Türü:</label>
                    <select name="tur" required>
                        <option value="borç">Borç</option>
                        <option value="alacak">Alacak</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Tutar (₺):</label>
                    <input type="number" name="tutar" step="0.01" min="0" required>
                </div>
                
                <div class="form-group">
                    <label>Açıklama:</label>
                    <textarea name="aciklama" rows="3"></textarea>
                </div>
                
                <button type="submit" name="islem_ekle" class="button button-primary">Kaydet</button>
            </form>
        </div>
        <?php
    }
    
        public static function musteri_detay() {
        global $wpdb;
        $musteri_id = intval($_GET['id']);
        error_log("Müşteri ID: ".$musteri_id); // Debug
    
        $musteri = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}cariflow_musteriler WHERE id = %d",
        $musteri_id
    ));
    
        $islemler = $wpdb->get_results($wpdb->prepare(
        "SELECT i.*, m.ad, m.soyad 
        FROM {$wpdb->prefix}cariflow_islemler i
        LEFT JOIN {$wpdb->prefix}cariflow_musteriler m ON i.musteri_id = m.id
        WHERE i.musteri_id = %d 
        ORDER BY i.tarih DESC",
        $musteri_id
    ));
            
        foreach ($islemler as $islem) {
        echo '<tr>
        <td>'.date('d.m.Y H:i', strtotime($islem->tarih)).'</td>
        <td>'.esc_html($islem->ad.' '.$islem->soyad).'</td>
        <td>'.($islem->tur == 'borç' ? '<span style="color:red">Borç</span>' : '<span style="color:green">Alacak</span>').'</td>
        <td>'.number_format($islem->tutar, 2).' ₺</td>
    </tr>';
    }
    
    include plugin_dir_path(__FILE__) . 'views/musteri-detay.php';
    if(!file_exists($template_path)) {
        wp_die('Template dosyası bulunamadı: '.$template_path);
    }
        include $template_path;
}