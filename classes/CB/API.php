<?php
namespace CB;

if (!defined("ABSPATH")) exit;

class API
{
    private static $email = "";
    private static $apikey = "";
    private static $apiURL = "";

    public static $responses = array();

    public function getResponse()
    {
        return self::$responses;
    }

    public static function post($url, $data = array())
    {
        self::$email = get_option('cb_cb_username');
        self::$apikey = get_option('cb_cb_api');

        $url = self::site_url($url);

        if (!self::$email || !self::$apikey) {
            $url = self::site_url('no');
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERPWD, self::$email . ":" . self::$apikey);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_SSL_CIPHER_LIST, 'SSLv3');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if (isset($_COOKIE[CB_COOKIE_NAME])) {
            curl_setopt($ch, CURLOPT_COOKIE, CB_COOKIE_NAME . '=' . $_COOKIE[CB_COOKIE_NAME]);
        }

        $response = curl_exec($ch);
        //var_dump($response);
        curl_close($ch);
        self::$responses = $response;

        return new self;
    }

    public function jsonDecode()
    {
        
        $responses = self::$responses;
        /*echo "<pre>";
        //print_r(json_decode($responses, true));
        print_r($responses);
        echo "<pre>";
        exit;*/
        self::$responses = json_decode($responses, true);
        return $this;
    }

    public static function site_url($param = "")
    {
        self::$apiURL = get_option('cb_cb_api_url');
        return rtrim(self::$apiURL, '/') . '/' . ltrim($param, '/');
    }

    public function insertCourseClasses()
    {
        if (!self::$responses || isset(self::$responses['code'])) return;

        foreach (self::$responses as $course) {
            if (!isset($course['classes'])) return;

            
            
            foreach ($course['classes'] as $class) {
                $title = $class['coursetypename'] . ' ' . date("F-d-Y", strtotime($class['coursedate'])) . ' ' . $class['location'] . ' Class ' . $class['scheduledcoursesid'];

                $my_post = array(
                    'post_title' => $title,
                    'post_name' => sanitize_title($title),
                    'post_status' => 'publish',
                    'post_author' => 1,
                    'post_type' => Posttypes::$post_type,
                    'comment_status' => 'closed'
                );

                $cur_post_id = wp_insert_post($my_post);

                if ($cur_post_id) {
                    update_post_meta($cur_post_id, 'cb_zip', $class['locationzip']);

                    update_post_meta($cur_post_id, 'cb_course_schedule_id', $class['scheduledcoursesid']);

                    update_post_meta($cur_post_id, 'cb_course_id', $class['scheduledcoursesid']);

                    update_post_meta($cur_post_id, 'cb_agency', $course['classes'][0]['agency']);

                    update_post_meta($cur_post_id, 'cb_course_location', $class['location']);

                    update_post_meta($cur_post_id, 'cb_course_date_time', date("l, F d, Y", strtotime($class['coursedate'])) . ' at ' . date("g:i a", strtotime($class['coursetime'])));

                    update_post_meta($cur_post_id, 'cb_course_full_object', $class);
                    
                    if( count( $class['rsCoursesAddon'] ) > 0 ) {
                        update_post_meta($cur_post_id, 'cb_course_addon', $class['rsCoursesAddon']);
                    }

                    $cat = \wp_insert_term($course['course']['course_name'], Posttypes::$taxonomy);

                    if (is_wp_error($cat) && array_key_exists('term_exists', $cat->errors))
                        $cat_ID = absint($cat->error_data['term_exists']);
                    else
                        $cat_ID = $cat['term_id'];

                    wp_set_post_terms($cur_post_id, $cat_ID, Posttypes::$taxonomy);
                }
            }
        }
    }


    public function insertCorpCourse()
    {       
        if (!self::$responses || isset(self::$responses['code'])) return;
        
        foreach (self::$responses as $course) {
            if (!isset($course['classes'])) return;

            foreach ($course['classes'] as $class) {
                $title = $class['coursetypename'] . ' ' . date("F-d-Y", strtotime($class['coursedate'])) . ' ' . $class['location'] . ' Class ' . $class['scheduledcoursesid'].'_'.$course['course']['corp_id'];

                $my_post = array(
                    'post_title' => $title,
                    'post_name' => sanitize_title($title),
                    'post_status' => 'publish',
                    'post_author' => 1,
                    'post_type' => Posttypes::$corp,
                    'comment_status' => 'closed'
                );

                $cur_post_id = wp_insert_post($my_post);

                

                if ($cur_post_id) {
                    update_post_meta($cur_post_id, 'cb_zip', $class['locationzip']);

                    update_post_meta($cur_post_id, 'cb_course_schedule_id', $class['scheduledcoursesid']);

                    update_post_meta($cur_post_id, 'cb_course_id', $class['scheduledcoursesid']);

                    update_post_meta($cur_post_id, 'cb_agency', $course['classes'][0]['agency']);

                    update_post_meta($cur_post_id, 'cb_course_location', $class['location']);

                    update_post_meta($cur_post_id, 'cb_course_corp_id', $course['course']['corp_id']);

                    update_post_meta($cur_post_id, 'cb_course_Url_id', $course['course']['Url_id']);

                    update_post_meta($cur_post_id, 'cb_course_date_time', date("l, F d, Y", strtotime($class['coursedate'])) . ' at ' . date("g:i a", strtotime($class['coursetime'])));

                    update_post_meta($cur_post_id, 'cb_course_full_object', $class);
                    
                    if( count( $class['rsCoursesAddon'] ) > 0 ) {
                        update_post_meta($cur_post_id, 'cb_course_addon', $class['rsCoursesAddon']);
                    }

                    $cat = \wp_insert_term($course['course']['course_name'], Posttypes::$tax_corp);

                    if (is_wp_error($cat) && array_key_exists('term_exists', $cat->errors))
                        $cat_ID = absint($cat->error_data['term_exists']);
                    else
                        $cat_ID = $cat['term_id'];

                    wp_set_post_terms($cur_post_id, $cat_ID, Posttypes::$tax_corp);
                }
            }
        }

    }
}
