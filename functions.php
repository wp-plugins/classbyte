<?php
if (!defined("ABSPATH")) exit;

/***
 * @param $id
 */
function store_post_page_ids($id)
{
    $id = intval(trim($id));

    if (empty($id)) {
        return;
    }

    $cb_post_page_ids = get_option('cb_post_page_ids');

    if (empty($cb_post_page_ids)) $cb_post_page_ids = array();

    $cb_post_page_ids[] = $id;
    update_option('cb_post_page_ids', $cb_post_page_ids);
}

/**
 * Alias of array_search except it searches recursively
 * @param $needle string
 * @param $haystack array
 * @return bool|int|string
 */
function recursive_array_search($needle,$haystack)
{
    foreach($haystack as $key=>$value) {
        $current_key=$key;
        if($needle === $value OR (is_array($value) && recursive_array_search($needle, $value) !== false)) {
            return $current_key;
        }
    }
    return false;
}

/**
 * Delete custom terms and their taxonomy
 * @param string $taxonomy
 * @return void
 */
function delete_custom_terms($taxonomy)
{
    /**
     * @var wpdb $wpdb
     */
    global $wpdb;

    $query = 'SELECT t.name, t.term_id
            FROM ' . $wpdb->terms . ' AS t
            INNER JOIN ' . $wpdb->term_taxonomy . ' AS tt
            ON t.term_id = tt.term_id
            WHERE tt.taxonomy = "' . $taxonomy . '"';

    $terms = $wpdb->get_results($query);

    foreach ($terms as $term) {
        wp_delete_term( $term->term_id, $taxonomy );
    }
}

/**
 * Simplify deserialized url encoded data coming from jQuery Ajax method $.serialize() to Array
 * @param $array string
 * @return void
 */
function simplify_serialize_data(&$array)
{
    parse_str($array, $array);
}

/**
 * Validate Names either first or lastm
 * @param string $str
 * @return bool
 */
function validate_name($str = '')
{
    return (preg_match('#^[a-zA-Z\s?]+$#', $str) === 0) ? false : true;
}

/**
 * Alias of include construct except it returns instead of output
 * @param $template string
 * @param array $data
 * @return string
 */
function return_include_once($template, $data = array())
{
    ob_start();

    if (!empty($data) && (is_array($data) || is_object($data) )) extract($data);

    include CB_TEMPLATES . $template;
    return ob_get_clean();
}

/**
 * Check wether user is logged into API
 * @return bool
 */
function is_student_logged_in()
{
    $response = CB\API::post('auth/userin')->jsonDecode()->getResponse();

    if (isset($response['success'], $response['action'])
        && $response['success'] == true
        && $response['action'] == 3
    ) {
        return true;
    }

    return false;
}

/**
 * Signs out student from API
 * @return bool
 */
function cb_sign_out()
{
    $response = CB\API::post('sign/out')->jsonDecode()->getResponse();
    if (isset($response['success'], $response['action'])
        && $response['success'] == true
        && $response['action'] == 1
    ) {
        return true;
    }

    return false;
}

function sign_out_link()
{
    $tmpl = '<div class="text-right"><a href="#" class="mini-request" id="cb_sign_out">Sign out</a></div>';
    return (is_student_logged_in()) ? $tmpl : '';
}

function get_df_data(&$key, $default = '', $sanitize = true)
{
    $_key = (isset($key)) ? $key : $default;

    if ($sanitize)
        return sanitize_text_field($_key);
    else
        return $_key;
}

/**
* Retrieve permalink by slug
*/
function get_permalink_by_slug($slug)
{
    return get_permalink(get_page_by_path($slug));
}

function format_course_date($date, $time, $endtime)
{
    $datetime = new DateTime();
    $datetime->setTimestamp(strtotime($date));
    $new_date = $datetime->format('l, M j, Y');

    $datetime->setTimestamp(strtotime($time));
    $new_time = $datetime->format('g:i a');

    $datetime->setTimestamp(strtotime($endtime));
    $new_end_time = $datetime->format('g:i a');

    return $new_date . ' ' . $new_time . ' - ' . $new_end_time;
}

function is_recursive_page(array $pages)
{
    if (!empty($pages)) {
        foreach ($pages as $page) {
            if ( is_page((int) $page) )
                return true;
        }
    }

    return false;
}
function after_login_redirect()
{
    if( is_page( 'student-login' ) && is_student_logged_in() )
    {
        wp_redirect( home_url( '/class-schedule/' ) );
        exit();
    }
}
add_action( 'template_redirect', 'after_login_redirect' );
function history_login_redirect()
{
    if( is_page( 'course-history' ) && ! is_student_logged_in() )
    {

		home_url( '/student-login/' );
        wp_redirect( home_url( '/student-login/' ) );
        exit();
    }
}
add_action( 'template_redirect', 'history_login_redirect' );
function add_menu_icons_styles(){
?>

<style>
#adminmenu .toplevel_page_classbyte .dashicons-admin-generic:before {
  content: '\f176';
}
</style>

<?php
}
add_action( 'admin_head', 'add_menu_icons_styles' );
