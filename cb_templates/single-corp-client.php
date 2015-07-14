<?php 
namespace CB;

include_once('single-class/header.php');

$courses = Posttypes::queryPostsCorp();

$cb_course_Url_id       = get_post_meta($post->ID, 'cb_course_Url_id', true);

$cb_course_corp_id      = get_post_meta($post->ID, 'cb_course_corp_id', true);
    
$map = false;
if (get_df_data($fcd['lat'])
    && get_df_data($fcd['lon'])
    && is_numeric($fcd['lat'])
    && is_numeric($fcd['lon'])
) {
    $map = true;
}
?>
<div class="col-md-12">
    <form method="post" action="<?php echo get_permalink() . CB_ENDPOINT_REGISTER; ?>" id="cb_forms-only-ajax" name="cb_enroll_form">
        <div class="headline">
            <h3 class="text-center">
                <strong style="font-size:20px; color:slategray;"><?php echo get_df_data($fcd['agency']); ?></strong> -
                <?php echo $fcd['course']; ?> class in <?php echo get_df_data($fcd['location']); ?>
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
                                <p><?php format_course_date(get_df_data($fcd['coursedate']), get_df_data($fcd['coursetime']), get_df_data($fcd['courseendtime'])); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Location</strong></td>
                            <td><p><?php echo get_df_data($fcd['locationname']); ?></p>

                                <p><?php echo get_df_data($fcd['address']) . ','; ?></p>

                                <p><?php echo get_df_data($fcd['location']) . ', ' . get_df_data($fcd['locationzip']); ?></p></td>
                        </tr>
                        <?php if (get_df_data($fcd['notes'])) { ?>
                        <tr>
                            <td><strong>Notes</strong></td>
                            <td>
                                <?php echo $fcd['notes']; ?>
                            </td>
                        </tr>
                        <?php } ?>
                        
                        <?php $product_count=0;$addon_total="";?>
                        <?php if( !empty( $course_addon ) ) { $i = 0; ?>
                            <?php foreach($course_addon as $course_addons){
                                    $product_count++; if($course_addons['product_option']==1){
                                        $addon_total+=$course_addons['product_price'];
                                    }
                            ?>
                                        <tr id="course_addon">
                                        <td>&nbsp;</td>
                                        <td><p style="float:left;">
                                            <strong><?php echo $course_addons['product_name']." | ".$course_addons['product_description'];?></strong></p>
                                            <p style="float:right;">
                                                <input type="radio" 
                                                       name="courseaddon[<?php echo $i; ?>]" 
                                                       id="products_<?php echo $product_count;?>" 
                                                       onChange="load_option_math('products_<?php echo $product_count;?>','subtract');" 
                                                       value="0"  rel="<?php echo $course_addons['product_price'];?>" 
                                                       <?php if($course_addons['product_option']==0){?>checked="checked"<?php }?>/>
                                                        No &nbsp;
                                                <input type="radio" 
                                                name="courseaddon[<?php echo $i; ?>]"
                                                id="products_<?php echo $product_count;?>" 
                                                value="<?php echo $course_addons['product_id'];?>" 
                                                rel="<?php echo $course_addons['product_price'];?>" 
                                                <?php if($course_addons['product_option']==1){ ?>checked="checked"<?php }?> 
                                                onchange="load_option_math('products_<?php echo $product_count;?>','add');"/>Yes<br />
                                                <span>(Add $<?php echo $course_addons['product_price']?>)</span>
                                            </p>
                                        </td>
                                        </tr>
                                      <?php $i++; }?>
                            <?php 
                                }
                            ?>
                            
                            <tr>
                                <td><strong>Promo Code</strong></td>
                                <td>
                                    <input type="text" name="promo_code" id="promo_code" />
                                </td>
                            </tr>
                            
                                  <input type="hidden" 
                                         name="actual_cost" 
                                         id="actual_cost" 
                                         value="<?php echo (get_df_data($fcd['coursecost'])+$addon_total); ?>"/>
                               
                            
                                <tr>
                                    <td><strong>Total Cost</strong></td>
                                    <td>
                                        <h4>$
                                            <span id="total_cost"><?php echo (get_df_data($fcd['coursecost'])+$addon_total); ?>.00</span>
                                        </h4></td>
                                  </tr>
                            
                                <input type="hidden"  name="product_count" value="<?php echo count($course_addon);?>"/>
                            
                            
                        <?php /*if (get_df_data($fcd['coursecost'])) { ?>
                        <tr>
                            <td><strong>Cost</strong></td>
                            <td><h4><?php echo '&dollar;' . get_df_data($fcd['coursecost']); ?></h4></td>
                        </tr>
                        <?php }*/
                            if (get_df_data($fcd['remainingseats']) >= 0) {
                                $remaining_seats = $fcd['remainingseats'];
                                if ($remaining_seats < 6 && $remaining_seats > 0) {
                                    $seats_need = "<strong style='font-size: 20px;'>{$remaining_seats}</strong> seats are available.";
                                } else if ($remaining_seats == 0) {
                                    $seats_need = "Class is <strong>FULL</strong><p>Contact us for further details.</p>";
                                }

                                if (get_df_data($seats_need, false)) {
                        ?>
                        <tr>
                            <td><strong>Seats</strong></td>
                            <td valign="middle"><?php echo get_df_data($seats_need, '', false); ?></td>
                        </tr>
                        <?php
                                }
                            }
                        ?>
                        <tr id="action-enroll-btn">
                            <td></td>
                            <td>
                                <input type="submit" class="btn" value="ENROLL">
                                <input type="hidden" value="<?php echo wp_create_nonce(get_df_data($fcd['scheduledcoursesid'])); ?>" name="course_token">
                                <input type="hidden" value="<?php echo get_df_data($fcd['scheduledcoursesid']); ?>" name="course_id">
                                <input type="hidden" value="<?php echo wp_create_nonce($post->ID); ?>" name="class_token">
                                <input type="hidden" value="<?php echo $post->ID; ?>" name="class_id">
                                <input type="hidden" value="<?php echo wp_create_nonce('cb_forms-only-ajax'); ?>" name="_cb_nonce">
                                <input type="hidden" value="<?php echo $cb_course_corp_id; ?>" name="cb_course_corp_id">
                                <input type="hidden" value="<?php echo $cb_course_Url_id; ?>" name="cb_course_Url_id">
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
                                title: "<?php echo get_df_data($fcd['locationname']); ?>"
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
   
   
   
    function load_option_math(id,method){
     //alert("im here"); return false;
	   var price = jQuery('input[id='+id+']:checked').attr('rel');
	   var actual_price = jQuery("#actual_cost").val(); 
	   if(method=="add"){
		  var addon_price = parseFloat(parseFloat(price)+parseFloat(actual_price));
		  jQuery("#actual_cost").val(addon_price.toFixed(2));
		  jQuery("#total_cost").html(addon_price.toFixed(2));
	   }else{
		var addon_price = parseFloat(parseFloat(actual_price)-parseFloat(price));
		jQuery("#actual_cost").val(addon_price.toFixed(2));
		jQuery("#total_cost").html(addon_price.toFixed(2));
	
	   }
    }
         
      
    </script>
</div>
<?php include_once('single-class/footer.php'); ?>
