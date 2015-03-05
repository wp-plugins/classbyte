<?php
namespace CB;

if (!defined("ABSPATH")) exit;

class Posttypes
{
    public static $post_type = "class-schedule";
    public static $taxonomy = "courses";


    public static $corp     = "corp-client";
    public static $tax_corp = "tax_corp";

    public function __construct()
    {
        add_action('init', array(__CLASS__, 'registerPostType'));
        add_action('init', array(__CLASS__, 'registerTaxonomy'));
        add_action('init', array(__CLASS__, 'registerCorpType'));

        add_filter( 'template_include', array($this, 'include_template_files'));
    }

    public static function registerPostType()
    {
        $labels = array(
            'name' => _x('Classes', self::$post_type),
            'singular_name' => _x('Class', self::$post_type),
            'add_new' => _x('Add Class', self::$post_type),
            'add_new_item' => __('Add Class'),
            'edit_item' => __('Edit Class'),
            'new_item' => __('New Class'),
            'view_item' => __('View Class'),
            'search_items' => __('Search Class'),
            'not_found' => __('No Class found'),
            'not_found_in_trash' => __('No Class found in Trash'),
            'parent_item_colon' => '',
            'menu_name' => __('Schedule Classes')
        );

        // Some arguments and in the last line 'supports', we say to WordPress what features are supported on the Project post type
        $shows_type = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true, #'edit.php?post_type=' . Children::$post_type,
            'capability_type' => 'post',
            'map_meta_cap' => false,
            'capabilities' => array(
                'create_posts' => false
            ),
            'rewrite' => array(
                'slug' => __('course'),
                'with_front' => false
            ),
            'has_archive' => true,
            'hierarchical' => true,
            'menu_icon' => ASSETS_URL . 'img/courses_icon.png',
            'supports' => array('title', 'editor', 'author', 'thumbnail'),
            'taxonomies' => array(self::$taxonomy),
        );

        register_post_type(self::$post_type, $shows_type);
    }

    public static function registerTaxonomy()
    {
        $labels = array(
            'name' => _x('Courses', self::$taxonomy),
            'singular_name' => _x('Courses', self::$taxonomy),
            'search_items' => __('Search Courses'),
            'popular_items' => __('Popular Courses'),
            'all_items' => __('All Courses'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __('Edit Course'),
            'update_item' => __('Update Course'),
            'add_new_item' => __('Add New Course'),
            'new_item_name' => __('New Course Name'),
            'menu_name' => __('Courses'),
        );

        // Now register the non-hierarchical taxonomy like tag
        register_taxonomy(self::$taxonomy, self::$post_type, array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'map_meta_cap' => false,
            'capabilities' => array(
                'manage_terms' => 'read', // Using 'edit_users' cap to keep this simple.
                'assign_terms' => 'read',
            ),
            'update_count_callback' => '_update_post_term_count',
            'taxonomies' => array(self::$taxonomy),
            'query_var' => true,
        ));
        /*corp*/
        $labels = array(
            'name' => _x('Corp Courses', self::$tax_corp),
            'singular_name' => _x('Corp Courses', self::$tax_corp),
            'search_items' => __('Search Corp Courses'),
            'popular_items' => __('Popular Corp Courses'),
            'all_items' => __('All Corp Courses'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __('Edit Corp Course'),
            'update_item' => __('Update Corp Course'),
            'add_new_item' => __('Add New Corp Course'),
            'new_item_name' => __('New Corp Course'),
            'menu_name' => __('Corp Courses'),
        );

        // Now register the non-hierarchical taxonomy like tag
        register_taxonomy(self::$tax_corp, self::$corp, array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'map_meta_cap' => false,
            'capabilities' => array(
                'manage_terms' => 'read', // Using 'edit_users' cap to keep this simple.
                'assign_terms' => 'read',
            ),
            'update_count_callback' => '_update_post_term_count',
            'taxonomies' => array(self::$tax_corp),
            'query_var' => true,
        ));
    }

    public function include_template_files($template)
    {
        if (is_singular(self::$post_type)) {
            $templatefilename = 'single-'. self::$post_type .'.php';
            $template = CB_TEMPLATES . $templatefilename;
            return $template;
        }

        return $template;
    }

    public static function postExists($title)
    {
        if (empty($title)) return;

        $post = get_page_by_title($title, OBJECT, self::$post_type);

        if ($post)
            return true;

        return false;
    }

    public static function havePosts()
    {
        $new_query = new \WP_Query(array(
            'post_type' => Posttypes::$post_type
        ));

        if ($new_query->have_posts()) {
            return true;
        }

        return false;
    }

    public static function queryPosts()
    {
        if (!self::havePosts()) return;

        $new_query = new \WP_Query(array(
            'post_type' => Posttypes::$post_type
        ));

        $all_listing = array();

        while($new_query->have_posts()) : $new_query->the_post();
            $cat = get_the_terms(get_the_ID(), Posttypes::$taxonomy);
            $cat_key = array_slice(array_keys($cat), 0, 1);
            $cat = $cat[$cat_key[0]];

            $full_object = get_post_meta(get_the_ID(), 'cb_course_full_object', true);
            $comment = '';
            if ($full_object['comments']) {
                $comment = $full_object['comments'];
                unset($full_object['comments']);
            }

            $result = array_merge(array (
                'title' => get_the_title(),
                'url' => get_permalink(),
                'datetime' => get_post_meta(get_the_ID(), 'cb_course_date_time', true),
                'location' => get_post_meta(get_the_ID(), 'cb_course_location', true),
                'cb_course_corpparent' => get_post_meta(get_the_ID(), 'cb_course_corpparent', true),
            ), $full_object);

            $key = recursive_array_search($cat->term_id, $all_listing);

            if ($key !== false) {
                $all_listing[$key]['classes'][] = $result;
            } else {
                $all_listing[] = array(
                    'category' => array(
                        'cat_name' => $cat->name,
                        'cat_id' => $cat->term_id,
                        'cat_comment' => ($comment) ? $comment : ''
                    ),
                    'classes' => array($result)
                );
            }
        endwhile;

        return $all_listing;
    }

    public static function registerCorpType()
    {
        $labels = array(
            'name' => _x('Corp', self::$corp),
            'singular_name' => _x('Corp', self::$corp),
            'add_new' => _x('Add Class', self::$corp),
            'add_new_item' => __('Add Corp'),
            'edit_item' => __('Edit Corp'),
            'new_item' => __('New Corp'),
            'view_item' => __('View Corp'),
            'search_items' => __('Search Corp'),
            'not_found' => __('No Corp found'),
            'not_found_in_trash' => __('No Corp found in Trash'),
            'parent_item_colon' => '',
            'menu_name' => __('Courses')
        );

        // Some arguments and in the last line 'supports', we say to WordPress what features are supported on the Project post type
        $shows_type = array(
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'show_ui' => true,
            'show_in_menu' => true, #'edit.php?post_type=' . Children::$post_type,
            'capability_type' => 'post',
            'map_meta_cap' => false,
            'capabilities' => array(
                'create_posts' => false
            ),
            'rewrite' => array(
                'slug' => __('corp-client'),
                'with_front' => true
            ),
            'has_archive' => true,
            'hierarchical' => true,
            'menu_icon' => ASSETS_URL . 'img/courses_icon.png',
            'supports' => array('title', 'editor', 'author', 'thumbnail'),
            'taxonomies' => array(self::$tax_corp),
        );

        register_post_type(self::$corp, $shows_type);
    }

    public static function queryPostsCorp()
    {
        if (!self::havePosts()) return;

        $new_query = new \WP_Query(array(
            'post_type' => Posttypes::$corp
        ));

        $all_listing = array();

        while($new_query->have_posts()) : $new_query->the_post();
            $cat = get_the_terms(get_the_ID(), Posttypes::$tax_corp);
            $cat_key = array_slice(array_keys($cat), 0, 1);
            $cat = $cat[$cat_key[0]];

            $full_object = get_post_meta(get_the_ID(), 'cb_course_full_object', true);
            $comment = '';
            if ($full_object['comments']) {
                $comment = $full_object['comments'];
                unset($full_object['comments']);
            }

            $result = array_merge(array (
                'title' => get_the_title(),
                'url' => get_permalink(),
                'datetime' => get_post_meta(get_the_ID(), 'cb_course_date_time', true),
                'location' => get_post_meta(get_the_ID(), 'cb_course_location', true),
                'corp_id'   => get_post_meta(get_the_ID(), 'cb_course_corp_id', true),
                'Url_id'    => get_post_meta(get_the_ID(), 'cb_course_Url_id', true),
            ), $full_object);

            $key = recursive_array_search($cat->term_id, $all_listing);

            if ($key !== false) {
                $all_listing[$key]['classes'][] = $result;
            } else {
                $all_listing[] = array(
                    'category' => array(
                        'cat_name' => $cat->name,
                        'cat_id' => $cat->term_id,
                        'cat_comment' => ($comment) ? $comment : ''
                    ),
                    'classes' => array($result)
                );
            }
        endwhile;

        return $all_listing;
    }
}