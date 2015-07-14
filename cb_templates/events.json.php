<?php

require('../../../../wp-load.php');

$data   = query_posts( array( 
            'post_type' => array( 
                'class-schedule'
            )));
$out    = array();

foreach ( $data as $d )
{
    $bg = array(
        'event-important', 
        'event-success', 
        'event-warning', 
        'event-info', 
        'event-inverse'
    );
    
    $i              = rand(0, count($bg)-1);
    
    $selectedBg     = "$bg[$i]";

    $full_object    = get_post_meta($d->ID, 'cb_course_full_object', true);
    
    $out[] = array(
        
        'id'    => $d->ID,
        
        'title' => $d->post_title,
        
        'url'   => $d->guid,
        
        'class' => $selectedBg,
        
        "start" => strtotime( $full_object['coursedate'] ) . '000',
        
    );
}

echo json_encode(array('success' => 1, 'result' => $out));
exit;


