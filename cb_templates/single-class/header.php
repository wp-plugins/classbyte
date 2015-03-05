<?php
namespace CB;
/**
 * Single Post Template file for Class Schedule post type
 */
get_header(); the_post();

global $wp_query;

$fcd = get_post_meta($post->ID, 'cb_course_full_object', true);


/* Rendering On single-class-schedule.php */

$course_addon   = get_post_meta($post->ID, 'cb_course_addon', true);

/* End Course Addon */


$active = 'class="active"';

if (isset($wp_query->query_vars['register'])) {
    $register = $active;
} else if (isset($wp_query->query_vars['payment'])) {
    $payment = $active;
}
?>

<div id="form-steps">
    <!-- progressbar -->
    <ul id="progressbar">
        <li class="active">Course Selection</li>
        <li <?php echo (isset($register) || isset($payment)) ? 'class="active"' : ''; ?>>Register / Login</li>
        <li <?php echo (isset($payment)) ? 'class="active"' : ''; ?>>Payment</li>
    </ul>
    <!-- fieldsets -->
</div>

<div id="cb-form-area" class="clearfix">
    <?php echo sign_out_link(); ?>
