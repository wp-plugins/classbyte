<?php
namespace CB;

if (!defined("ABSPATH")) exit;

class PostsPages
{
    public static $post_pages = array(
        0 => array(
            'title' => "Class Schedule",
            'content' => "[cb_class_listing]"
        ),
        1 => array(
            'title' => "Student Login",
            'content' => "[cb_class_schedule_login]"
        ),
        2 => array(
            'title' => "Course History",
            'content' => "[cb_course_history]"
        ),
    );

    /**
     * Add all the (posts) classes from API
     */
    public static function add($page_details = array())
    {
        if (empty($page_details)) $page_details = self::$post_pages;

        foreach ($page_details as $page) {
            $my_post = array(
                'post_title'    => $page['title'],
                'post_content'  => $page['content'],
                'post_status'   => 'publish',
                'post_author'   => 1,
                'post_type'     => 'page',
                'comment_status' => 'closed'
            );

            $post_id = wp_insert_post( $my_post );

            if ($post_id)
                store_post_page_ids($post_id);
        }
    }

    /**
     * Check if page exists or not
     * @return mixed
     */
    public static function exists()
    {
        $cb_post_page_ids = get_option('cb_post_page_ids');

        if (!$cb_post_page_ids) {
            return;
        }

        $re_add = null;

        foreach ($cb_post_page_ids as $post_page_id) {
            $found = recursive_array_search($post_page_id, self::$post_pages);

            if ($found !== false) {
                $re_add[] = $found;
            }
        }

        if (!is_null($re_add)) {
            return $re_add;
        } else {
            return true;
        }

    }

    /**
     * Trash all the pages
     * @return void
     */
    public static function trashAll()
    {
        $cb_post_page_ids = get_option('cb_post_page_ids');

        if (!$cb_post_page_ids) {
            return;
        }

        foreach ($cb_post_page_ids as $id) {
            wp_trash_post($id);
        }
    }

    /**
     * @param bool $only_posts. Optional if false will delete classes from courses and all pages, otherwise just the classes
     * @return void
     */
    public static function deleteAll($only_posts = false)
    {
        if ($only_posts === false) {
            self::unTrashAll();
            $cb_post_page_ids = get_option('cb_post_page_ids');

            if (is_array($cb_post_page_ids)) {
                foreach ($cb_post_page_ids as $id) {
                    wp_delete_post($id, true);
                }
            }
        }

        // delete custom post type classes(posts)
        $posts = get_posts(array(
            'post_type' => Posttypes::$post_type,
            'numberposts' => -1
        ));

        if (is_array($posts)) {
            foreach($posts as $post) {
                wp_delete_post($post->ID, true);
            }
        }

        // delete custom post type terms
        delete_custom_terms(Posttypes::$taxonomy);
    }

    /**
     * Restore all pages from trash
     * @return void
     */
    public static function unTrashAll()
    {
        $cb_post_page_ids = get_option('cb_post_page_ids');

        if ($cb_post_page_ids && empty($cb_post_page_ids))
            return;

        foreach ($cb_post_page_ids as $id) {
            wp_update_post(array(
                'ID' => $id,
                'post_status' => 'publish'
            ));
        }
    }

    public static function deleteAllCorp($only_posts = false)
    {
        if ($only_posts === false) {
            self::unTrashAll();
            $cb_post_page_ids = get_option('cb_post_page_ids');

            if (is_array($cb_post_page_ids)) {
                foreach ($cb_post_page_ids as $id) {
                    wp_delete_post($id, true);
                }
            }
        }

        // delete custom post type classes(posts)
        $posts = get_posts(array(
            'post_type' => Posttypes::$corp,
            'numberposts' => -1
        ));

        if (is_array($posts)) {
            foreach($posts as $post) {
                wp_delete_post($post->ID, true);
            }
        }

        // delete custom post type terms
        delete_custom_terms(Posttypes::$tax_corp);
    }
}