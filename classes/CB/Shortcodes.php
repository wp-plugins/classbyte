<?php
namespace CB;

if (!defined("ABSPATH")) exit;

class Shortcodes
{
    public function __construct()
    {
        add_shortcode('cb_class_listing', array($this, 'classListing'));
        add_shortcode('cb_class_schedule_login', array($this, 'scheduleLogin'));
        add_shortcode('cb_course_history', array($this, 'courseHistory'));
    }

    public function classListing($atts, $content = null)
    {
        PostsPages::deleteAll(true);

        API::post('course/listing')->jsonDecode()->insertCourseClasses();

        include_once CB_TEMPLATES . 'page-class-schedule.php';
    }

    public function scheduleLogin($atts, $content = null)
    {
        extract(shortcode_atts(array(
            'parent' => "yes",
            'reg_header' => "no"
        ), $atts));

        if ($parent == "yes") {
            echo '<div id="cb-form-area" class="clearfix">';
            echo '<form class="reg-page" id="cb_forms-only-ajax" method="post" name="cb_login_form">';
        }

        include_once CB_TEMPLATES . 'class-schedule-login.php';

        if ($parent == "yes") {
            echo '</form></div>';
        }
    }

    public function courseHistory($atts, $content = null)
    {
        $response = API::post('course/history')->jsonDecode()->getResponse();

        $course_history = array();

        if (isset($response['success'], $response['action']) && $response['success'] == true) {
            $course_history = $response['object'];
        }

        include_once CB_TEMPLATES . 'page-course-history.php';
    }
}