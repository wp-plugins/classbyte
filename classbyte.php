<?php
/**
 * Plugin Name: ClassByte
 * Plugin URI: http://www.classbyte.com
 * Description: The ClassByte Plugin allows you to connect your instance of ClassByte to your WordPress Website.
 * Version: 2.0
 * Author: CloudScope, Inc.
 * Author URI: http://www.classbyte.com/
 * License: GPL2
 */

namespace CB;

// Directory
define('CB_DIR', plugin_dir_path(__FILE__));
define('CB_VIEWS', trailingslashit(CB_DIR . 'views'));
define('CB_TEMPLATES', trailingslashit(CB_DIR . 'cb_templates'));

// URLS
define('CB_URL', trailingslashit(plugins_url('', __FILE__)));
define('ASSETS_URL', trailingslashit(CB_URL . 'assets'));

// Cookies name
define('CB_COOKIE_NAME', '__cbapi');
define('CB_COOKIE_ENROLL', '__cbapi_enroll');

// Endpoints
define('CB_ENDPOINT_PAYMENT', 'payment');
define('CB_ENDPOINT_REGISTER', 'register');

include_once CB_DIR . 'autoload.php';
include_once CB_DIR . 'functions.php';
include_once CB_DIR . 'hooks.php';

$cb = new ClassByte();

register_activation_hook(__FILE__, array(__NAMESPACE__ . '\ClassByte', 'activation'));
register_deactivation_hook(__FILE__, array(__NAMESPACE__ . '\ClassByte', 'deactivation'));
register_uninstall_hook(__FILE__, array(__NAMESPACE__ . '\ClassByte', 'uninstall' ) );
