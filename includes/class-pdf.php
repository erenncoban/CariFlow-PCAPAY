<?php
require_once(plugin_dir_path(__FILE__) . '../vendor/autoload.php');

class CariFlow_PDF {
    public static function generate($musteri_id, $islem_ids) {
        global $wpdb;
        
        // Verileri çek
        $musteri = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}cariflow_musteriler WHERE id = $musteri_id");
        $islemler = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cariflow_islemler WHERE id IN (" . implode(',', $islem_ids) . ")");
        
        // PDF oluştur
        $mpdf = new \Mpdf\Mpdf();
        ob_start();
        include(plugin_dir_path(__FILE__) . '../assets/invoice-template.php');
        $html = ob_get_clean();
        
        $mpdf->WriteHTML($html);
        $mpdf->Output("fatura-{$musteri_id}-" . date('Ymd') . ".pdf", 'D');
    }
}