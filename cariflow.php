<?php
/**
Plugin Name: CariFlow FİNANS CRM MODÜLÜ
Plugin URI: https://www.pancreative.co
Description: Bu modül müşterilerinize ait carii borç takibi yapabilmenizi sağlar.
Version: 2.0.1
Author: Pan Creative Agency, Eren Çoban
Author URI: https://www.pancreative.co
License: GNU
*/

defined('ABSPATH') || exit;

require_once plugin_dir_path(__FILE__) . 'includes/class-db.php';

register_activation_hook(__FILE__, ['CariFlow_DB', 'create_tables']);

add_action('admin_menu', function() {
    require_once plugin_dir_path(__FILE__) . 'admin/admin-functions.php';
    CariFlow_Admin::init();
});

// CSS Ekleme
add_action('admin_enqueue_scripts', function() {
    wp_enqueue_style('cariflow-css', plugins_url('admin/admin-style.css', __FILE__));
});