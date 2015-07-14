<?php 
namespace CB;
include_once('single-class/header.php'); ?>

<div class="col-md-12">
    <?php

    $corpRequest = get_query_var('corp_id');
    $id_client_name = explode('/',get_query_var('corp_id') );

    if ( !empty($id_client_name['0']) AND $id_client_name['1'] ) {
    	$courseAgainstCorpUrl = API::post("course/corp")->jsonDecode()->getResponse();
        $map = false;
        if ($courseAgainstCorpUrl[0]['classes'][0]['lat']
            && $courseAgainstCorpUrl[0]['classes'][0]['lon']
            && is_numeric($courseAgainstCorpUrl[0]['classes'][0]['lat'])
            && is_numeric($courseAgainstCorpUrl[0]['classes'][0]['lon'])
        )   
        {
            $map = true;
        }   
    	/*echo "<pre>";
    	print_r($courseAgainstCorpUrl);
    	echo "</pre>";
    	exit;*/
    ?>
    <form method="post" action="<?php echo get_permalink() . CB_ENDPOINT_REGISTER; ?>" id="cb_forms-only-ajax" name="cb_enroll_form">
        <div class="headline">
            <h3 class="text-center">
                <strong style="font-size:20px; color:slategray;"><?php echo $courseAgainstCorpUrl[0]['classes'][0]['agency']; ?></strong> -
                <?php echo $courseAgainstCorpUrl[0]['course']['course_name']; ?> class in <?php echo $courseAgainstCorpUrl[0]['classes'][0]['location']; ?>
            </h3>
        </div>

        <div class="row">
            <div class="col-md-6 col-md-offset-3" id="course_information">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <tbody>
                        <tr>
                            <td><strong>Date/Time</strong></td>
                            <td>
                            <p>
                            	<?php echo $courseAgainstCorpUrl[0]['classes'][0]['coursedate']; ?>,
                            	<?php echo $courseAgainstCorpUrl[0]['classes'][0]['coursetime']; ?>,
                            	<?php echo $courseAgainstCorpUrl[0]['classes'][0]['courseendtime']; ?>
                            </p>
                            </td>
                            </tr>
                        <tr>
                            <td><strong>Location</strong></td>
                            <td><p><?php echo $courseAgainstCorpUrl[0]['classes'][0]['locationname']; ?></p>

                                <p><?php echo $courseAgainstCorpUrl[0]['classes'][0]['address'] . ','; ?></p>

                                <p><?php echo $courseAgainstCorpUrl[0]['classes'][0]['location'] . ', ' . $courseAgainstCorpUrl[0]['classes'][0]['locationzip']; ?></p>
                            </td>
                        </tr>

                        <?php if ($courseAgainstCorpUrl[0]['classes'][0]['notes']) { ?>
                        <tr>
                            <td><strong>Notes</strong></td>
                            <td>
                                <?php echo $courseAgainstCorpUrl[0]['classes'][0]['notes']; ?>
                            </td>
                        </tr>
                        <?php } ?>

                        <tr id="action-enroll-btn">
                            <td></td>
                            <td>
                                <input type="submit" class="btn" value="ENROLL">
                                <input type="hidden" value="<?php echo wp_create_nonce($courseAgainstCorpUrl[0]['classes'][0]['scheduledcoursesid']); ?>" name="course_token">
                                <input type="hidden" value="<?php echo get_df_data($courseAgainstCorpUrl[0]['classes'][0]['scheduledcoursesid']); ?>" name="course_id">
                                <input type="hidden" value="<?php echo wp_create_nonce($courseAgainstCorpUrl[0]['classes'][0]['scheduledcoursesid']); ?>" name="class_token">
                                <input type="hidden" value="<?php echo $courseAgainstCorpUrl[0]['classes'][0]['scheduledcoursesid']; ?>" name="class_id">
                                <input type="hidden" value="<?php echo wp_create_nonce('cb_forms-only-ajax'); ?>" name="_cb_nonce">
                            </td>
                        </tr>
                        </tbody>

                        
                    </table>
                </div>
            </div>
            <?php if ($map) { ?>
            <div class="col-md-6 col-md-offset-3" id="course_geo_map">
                <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false"></script>
                <script>
                    (function() {
                        var map;
                        function initialize() {
                            var cb_form_loading = document.getElementById('cb-form-loading'),
                                latLng = new google.maps.LatLng(<?php echo get_df_data($fcd['lat']) . ', ' . get_df_data($fcd['lon']); ?>);

                            var mapOptions = {
                                zoom: 12,
                                center: latLng
                            };
                            map = new google.maps.Map(document.getElementById('map-canvas'),
                                mapOptions);

                            var marker = new google.maps.Marker({
                                position: latLng,
                                map: map,
                                title: "<?php echo get_df_data($courseAgainstCorpUrl[0]['classes'][0]); ?>"
                            });

                            cb_form_loading.parentNode.removeChild(cb_form_loading);
                        }
                        google.maps.event.addDomListener(window, 'load', initialize);
                    })();
                </script>
                <div id="map-canvas"><div id="cb-form-loading"></div></div>
            </div>
            <?php } ?>
        </div>
    </form>
    <script type="text/javascript">
        
    <?php

    } else {
    	echo 'id not found';
    }
    ?>
</div>
<?php include_once('single-class/footer.php'); ?>
