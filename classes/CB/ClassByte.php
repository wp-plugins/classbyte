<?php
namespace CB;

if (!defined("ABSPATH")) exit;

class ClassByte
{
    public function __construct()
    {
        if (is_admin()) {
            new Dashboard();
        }

        new Widgets();
        new Shortcodes();
        new Posttypes();
        new Ajax();

        $this->corp_rewrite_rule();

        add_action('wp_enqueue_scripts', array($this, 'scripts'), 9999);
        
        add_action('admin_enqueue_scripts', array($this, 'scripts'), 9999);
        
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'), 9999);

        add_action('template_redirect', array($this, 'customCss'));
    }

    public static function activation()
    {
        Posttypes::registerPostType();

        if ( ! current_user_can( 'activate_plugins' ) )
            return;

        if (!PostsPages::exists()) {
            PostsPages::add();
        } else {
            PostsPages::unTrashAll();
        }

        register_endpoints();

        flush_rewrite_rules();
    }

    public static function deactivation()
    {

        if ( ! current_user_can( 'activate_plugins' ) )
            return;

        PostsPages::trashAll();

        flush_rewrite_rules();
    }

    public static function uninstall()
    {
        if ( ! current_user_can( 'activate_plugins' ) )
            return;

        global $wpdb;

        // Remove all the form settings
        $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'cb_%'" );

        // Delete all posts/pages
        PostsPages::deleteAll();
    }

    public function scripts()
    {
        wp_enqueue_script('bootstrap-js', ASSETS_URL . 'js/bootstrap.min.js', array('jquery'), false, true);
        wp_enqueue_script('jquery-cookie-js', ASSETS_URL . 'js/jquery.cookie.js', array('jquery'), false, true);
        wp_enqueue_script('cb', ASSETS_URL . 'js/cb.js', array('jquery'), false, true);
		wp_enqueue_script('mask', 'http://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js', array('jquery'), false, true);

        wp_enqueue_style('bootstrap-css', ASSETS_URL . 'css/cb_style.css');
		wp_enqueue_style('jquery-ui-css', ASSETS_URL . 'css/jquery-ui.css');
        wp_enqueue_style('main-css', ASSETS_URL . 'css/style.css');

        wp_localize_script('cb', 'cbConfig', array(
            'site_url' => site_url(),
            'ajax_url' => admin_url('admin-ajax.php'),
            'assets_url' => ASSETS_URL,
            'COOKIEPATH' => COOKIEPATH,
            'CB_COOKIE_NAME' => CB_COOKIE_NAME
        ));
    }
    
    
    public function admin_scripts()
    {
        wp_enqueue_script('bootstrap-js', ASSETS_URL . 'js/bootstrap.min.js', array('jquery'), false, true);
        wp_enqueue_script('jquery-cookie-js', ASSETS_URL . 'js/jquery.cookie.js', array('jquery'), false, true);
        wp_enqueue_script('cb', ASSETS_URL . 'js/cb.js', array('jquery'), false, true);
		wp_enqueue_script('mask', 'http://igorescobar.github.io/jQuery-Mask-Plugin/js/jquery.mask.min.js', array('jquery'), false, true);
        wp_enqueue_style('main-css', ASSETS_URL . 'css/style.css');

        wp_localize_script('cb', 'cbConfig', array(
            'site_url' => site_url(),
            'ajax_url' => admin_url('admin-ajax.php'),
            'assets_url' => ASSETS_URL,
            'COOKIEPATH' => COOKIEPATH,
            'CB_COOKIE_NAME' => CB_COOKIE_NAME
        ));
    }

    public function customCss()
    {
        if (!is_singular(Posttypes::$post_type) && is_recursive_page(get_option('cb_post_page_ids')) == false)
            return;

        $custom_css = get_option('cb_custom_css');
        $accordion_tab = get_option('cb_accordion_tab');
        $circle_steps = get_option('cb_circle_steps');
        $circle_active_steps = get_option('cb_circle_active_steps');
        $circle_straight_line = get_option('cb_circle_straight_line');
        $button_color = get_option('cb_button_color');
        $button_hover_color = get_option('cb_button_hover_color');

        if (!empty($accordion_tab))
            //$custom_css .= ".panel-default > .panel-heading { background-color: {$accordion_tab}; }";
			$custom_css .= ".panel-heading { background-color: {$accordion_tab}; }";

        if (!empty($circle_steps))
            $custom_css .= "#progressbar li:before { background-color: {$circle_steps}; }";

        if (!empty($circle_active_steps))
            $custom_css .= "#progressbar li.active:before, #progressbar li.active:after { background-color: {$circle_active_steps}; }";

        if (!empty($circle_straight_line))
            $custom_css .= "#progressbar li:after { background-color: {$circle_straight_line}; }";

        if (!empty($button_color))
            $custom_css .= 'button, input[type="submit"], input[type="button"], input[type="reset"] { background: '.$button_color.'; border: none; }';

        if (!empty($button_color))
            $custom_css .= 'button:hover, button:focus, input[type="submit"]:hover, input[type="button"]:hover, input[type="reset"]:hover, input[type="submit"]:focus, input[type="button"]:focus, input[type="reset"]:focus { background: '.$button_hover_color.'; border: none; }';

        if ($custom_css) {
            add_action('wp_head', function () use (&$custom_css) {
                echo '<style type="text/css" id="cb_custom_css">'.$custom_css.'</style>';
            });
        }
    }
    
    public function corp_rewrite_rule()
    {
        add_action('init', function() {

            add_rewrite_rule(
                'corp/(.+?)(/[0-9]+)?/?$',
                'index.php?corp_id=$matches[1]&corp_user=$matches[2]',
                'bottom'
            );
            
            add_rewrite_tag('%corp_id%' ,'([/d]+)');
            flush_rewrite_rules();

        });



    }


}