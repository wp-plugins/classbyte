<?php
namespace CB;

if (!defined("ABSPATH")) exit;

class Dashboard
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'dashboardMenus'));

        add_action('admin_init', array($this, 'register_cb_settings'));

        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
    }

    public function dashboardMenus()
    {
        add_menu_page('ClassByte', 'ClassByte', 'manage_options', 'classbyte', '', '', 99);

        add_submenu_page('classbyte', 'Welcome', 'Welcome', 'manage_options', 'classbyte', array($this, 'welcome'));
        add_submenu_page('classbyte', 'Settings', 'Settings', 'manage_options', 'settings', array($this, 'settings'));
    }

    public function welcome()
    {
        echo "Welcome Message";
    }

    public function settings()
    {
        include CB_VIEWS . 'Admin/settings.php';
    }

    public function register_cb_settings()
    {
        register_setting('cb-settings', 'cb_cb_username');
        register_setting('cb-settings', 'cb_cb_api');
        register_setting('cb-settings', 'cb_cb_api_url');
        register_setting('cb-settings', 'cb_custom_css');
        register_setting('cb-settings', 'cb_accordion_tab');
        register_setting('cb-settings', 'cb_circle_steps');
        register_setting('cb-settings', 'cb_circle_active_steps');
        register_setting('cb-settings', 'cb_circle_straight_line');
        register_setting('cb-settings', 'cb_button_color');
        register_setting('cb-settings', 'cb_button_hover_color');
    }

    public function admin_scripts()
    {
        wp_enqueue_style('jquery-ui-tabs-css', '//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css');
        wp_enqueue_style('colorpicker-css', ASSETS_URL . 'css/colorpicker.css');
        wp_enqueue_style('cb-admin-css', ASSETS_URL . 'css/admin-style.css');

        wp_register_script('colorpicker-js', ASSETS_URL . 'js/colorpicker.js', array('jquery'), false, true);

        wp_enqueue_script('cbAdmin', ASSETS_URL . 'js/cb_admin.js', array('jquery', 'jquery-ui-tabs', 'colorpicker-js'), false, true);

        wp_localize_script('cbAdmin', 'cbConfig', array(
            'site_url' => site_url(),
            'admin_url' => admin_url('admin-ajax.php')
        ));
    }
}
