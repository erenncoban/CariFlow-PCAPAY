<?php
class CariFlow_Public {
    public static function init() {
        add_shortcode('cari_panel', [__CLASS__, 'render_panel']);
    }
    
    public static function render_panel() {
        ob_start();
        include plugin_dir_path(__FILE__) . '../public/views/panel.php';
        return ob_get_clean();
    }
}

add_action('init', ['CariFlow_Public', 'init']);
?>