<?php

namespace CB;



include_once('single-class/header.php');



$course_id          = $fcd['scheduledcoursesid'];



$selectedAddon      = $fcd['courseaddon'];



$paid               = API::post("course/paid/{$course_id}")->jsonDecode()->getResponse();



$stripePublicKey    = API::post("payment/stripePublicKey")->jsonDecode()->getResponse();



$addonTotal         = 0;



$promoCodeTotal     = 0;



$originalAmount     = $fcd['coursecost'];



$amount             = $fcd['coursecost'];



$original_amount    = $fcd['coursecost'];









if (isset($paid['success'], $paid['action']) && $paid['success'] == true) {

?>

<div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2" style="min-width: 520px !important;">

    <form class="reg-page" id="cb_forms-only-ajax" method="post" name="cb_payment_form">

        <p class="text-center">

            <img src="<?php echo ASSETS_URL . 'img/thumbs_up.png'; ?>" alt=""><br><br>

            <?php echo $paid['message']; ?>

        </p>

    </form>

</div>

<?php

} else {

    

    $user_data = API::post('users/info')->jsonDecode()->getResponse();

    

    $user_data = $user_data['object'];

    

    $data = array (

        'id'        => $user_data['id'],

        'course_id' => $course_id

    );

    

    /*

    * Student Who Selected Couse Addon

    */

    

    $getStudentCourseAddons = API::post("users/getaddon",$data)->jsonDecode()->getResponse();

    

    $getStudentCourseAddons = $getStudentCourseAddons['object'];









    

    if ( count( $getStudentCourseAddons ) > 0 ) {

        

        foreach( $getStudentCourseAddons as $getStudentCourseAddon ) {

            

            $addonTotal += $getStudentCourseAddon['product_price'];

        

        }

    

    }



    $amount = $amount + $addonTotal;

    

    /* End Student Course Addon */

    $mode = API::post("payment/mode")->jsonDecode()->getResponse();

    if (isset($mode['success'], $mode['action'])) {

        if ($mode['message'] === "stripe") {

            echo '<script type="text/javascript" src="https://js.stripe.com/v2/"></script>';

            echo '<script type="text/javascript">Stripe.setPublishableKey("'.$stripePublicKey['object']['key'].'");</script>';

        }

    }

?>

<div class="col-md-8 col-md-offset-2 col-sm-8 col-sm-offset-2">

    <form class="reg-page" id="cb_forms-only-ajax" method="post" name="cb_payment_form">

        <div class="pull-left col-md-5">

            <input type="hidden" value="" name="paymentType">

            <div style="float:left; width:100%;" class="reg-header">

                <h2 style="float:left; margin-right:20px;">Payment</h2>

            </div>

            <div class="form-group-cb">

                <label for="firstName">First Name <span class="color-red">*</span></label>

                <input class="form-control-cb" type="text" class="span4" value="<?php echo get_df_data($user_data['studentsname']); ?>" id="firstName" name="firstName">

            </div>



            <div class="form-group-cb">

                <label for="lastName">Last Name <span class="color-red">*</span></label>

                <input class="form-control-cb" type="text" class="span4" value="<?php echo get_df_data($user_data['studentlastname']); ?>" id="lastName" name="lastName">

            </div>



            <div class="form-group-cb">

                <label for="creditCardType">Card Type <span class="color-red">*</span></label>

                <select  class="form-control span4" name="creditCardType" id="creditCardType">

                    <option selected="selected" value="">-- Select --</option>

                    <option value="Visa">Visa</option>

                    <option value="MasterCard">MasterCard</option>

                    <option value="Discover">Discover</option>

                    <option value="Amex">American Express</option>

                </select>

            </div>



            <div class="form-group-cb">            

                <label for="creditCardNumber">Card Number <img src="http://dev.classbyte.net/assets/img/Credit_Card_Icons.jpg">

                <span class="color-red">*</span></label>

                <input type="text" class="span4 form-control" value="" data-stripe="number" name="creditCardNumber" id="creditCardNumber">

            </div>



            <div class="form-group-cb">

                <label for="expDateMonth" data-for="expDateYear" class="checkbox row">Expiration Date <span class="color-red">*</span></label>

                <div class="pull-left">

                    <select class="form-control-cb" name="expDateMonth" id="expDateMonth" data-stripe="exp-month">

                        <option selected="selected" value="">-- Select --</option>

                        <?php foreach (range(1, 12) as $r) {

                            echo '<option value="' . sprintf("%02d", $r) . '">' . sprintf("%02d", $r) . '</option>';

                        } ?>

                    </select>

                </div>

                <div class="pull-left">

                    <select class="form-control-cb" name="expDateYear" id="expDateYear" data-stripe="exp-year">

                        <option selected="selected" value="">-- Select --</option>

                        <?php $date = new \DateTime();

                        for ($i = 0; $i <= 10; $i++) {

                            $year = (int) $date->format('Y') + $i;

                            echo '<option value="' . $year . '">' . $year . '</option>';

                        } ?>

                    </select>

                </div>

            </div>



            <div class="form-group-cb">

                <label for="cvv2Number">Card Verification Number <span class="color-red">*</span></label>

                <input type="text" class="span4 form-control" value="" data-stripe="cvc" name="cvv2Number" id="cvv2Number">

            </div>



            <div class="form-group-cb">

                <label for="address1">Address 1 <span class="color-red">*</span></label>

                <input type="text" id="address1" class="span4 form-control" value="<?php echo get_df_data($user_data['studentaddress']); ?>" name="address1">

            </div>



            <div class="form-group-cb">

                <label for="city">City <span class="color-red">*</span></label>

                <input type="text" class="span4 form-control" value="<?php echo get_df_data($user_data['studentcity']); ?>" name="city" id="city">

            </div>

            <div class="form-group-cb">

                <label for="state">State <span class="color-red">*</span></label>

                <input type="text" class="span4 form-control" value="<?php echo get_df_data($user_data['studentstate']); ?>" name="state" id="state">

            </div>

            <div class="form-group-cb">

                <label for="zip">Zip <span class="color-red">*</span></label>

                <input type="text" class="span4 form-control" value="<?php echo get_df_data($user_data['studentzip']); ?>" name="zip" id="zip">

            </div>

            <div class="form-group-cb">

                <label for="country">Country <span class="color-red">*</span></label>

                <input type="text" readonly="readonly" class="span4 form-control" value="US" name="country" id="country">

            </div>

            <br><br>

            <?php

            /*

            * Promo Code Amount Data

            */

            $getPromoCodeRawData = API::post("users/getPromoCodeAmount", $data)->jsonDecode()->getResponse();



            $promoCodeAmountData = $getPromoCodeRawData['object'];



            $promoCodeAmountData = $promoCodeAmountData[0];





            

            if ( count($promoCodeAmountData) > 0 ) {

                

                if($promoCodeAmountData['disc_type']=="dollar") {



                    $discount = array(

                        'discount_type' => 'dollar',

                        'amount'        => $promoCodeAmountData['discount']

                    );

                    

                    $total_amount = ( $amount - $promoCodeAmountData['discount'] );

                    

                    $amount = $total_amount;

                

                } elseif($promoCodeAmountData['disc_type']=="percentage") {

                    

                    //$totalAmount = $fcd['coursecost']+$addonTotal;



                    $discount = array(

                        'discount_type' => 'percentage',

                        'amount'        => $promoCodeAmountData['discount']

                    );

                    

                    $percentage = ($promoCodeAmountData['discount'] / 100) * $amount;

                    

                    $total_amount = ( $amount - $percentage );

                    

                    $amount = $total_amount;

                    

                }

            }

            

            /* End Promo Code Amount Data*/

    

            ?>

            <button type="submit" class="btn-u btn-u-orange">Pay Now</button>

            <input type="hidden" name="_cb_nonce" value="<?php echo wp_create_nonce('cb_forms-only-ajax'); ?>">

            <!--<input type="hidden" name="coursecost" value="<?php //echo $fcd['coursecost']+$addonTotal; ?>">-->

            <input type="hidden" name="coursecost" value="<?php echo round($amount);?>">

            <input type="hidden" name="total_amount" value="<?php echo $original_amount + $addonTotal;?>">

            <input type="hidden" name="scheduledcoursesid" value="<?php echo $fcd['scheduledcoursesid']; ?>">

        </div>







        <div class="pull-right col-md-6" id="class_details">

            <div class="headline">

                <h3>

                    <strong style="font-size:14px; color:slategray;"><?php echo $fcd['agency']; ?></strong><br>

                    <?php echo $fcd['course']; ?> class in <?php echo $fcd['location']; ?>

                </h3>

            </div>

            <style>

                .table th, .table td {

                    vertical-align: middle;

                }

            </style>

            <table width="100%" style=" margin-top:0px;" class="table table-striped">

                <tbody>

                <tr>

                    <td width="25%"><strong>Date/Time</strong></td>

                    <td width=""><?php echo date('l, M j, Y', strtotime($fcd['coursedate'])) . ' ' . date('g:i a', strtotime($fcd['coursetime'])) . ' - ' . date('g:i a', strtotime($fcd['courseendtime'])); ?></td>

                 </tr>

                <tr>

                    <td><strong>Location</strong></td>

                    <td>

                        <p><?php echo $fcd['locationname']; ?></p>

                        <p><?php echo $fcd['address'] . ','; ?></p>

                        <p><?php echo $fcd['location'] . ', ' . $fcd['locationzip']; ?></p>

                    </td>

                </tr>

                <tr>

                    <td><strong>Course Amount</strong></td>

                    <td>$ <?php echo $originalAmount; ?>.00</td>

                </tr>

                <?php if (!empty( $discount ) and count( $discount )> 0 ) : ?>

                <tr>

                    <td><strong>Promo Code Discount</strong></td>

                    <?php if ($discount['discount_type'] ==  "dollar" ) { ?>

                        <td>$ <?php echo $discount['amount']; ?></td>

                    <?php }  else if ($discount['percentage'] ==  "dollar" ) { ?>

                        <td>$ <?php echo $discount['amount']; ?>%</td>

                    <?php } ?>

                </tr>



                <tr>

                    <td><strong>Discount Final Amount</strong></td>

                    <td><p>$ <?php echo round( $amount ); ?></p></td>

                </tr>

                <?php  endif; ?>

                <?php if ( !empty($getStudentCourseAddons) ): ?>

                    <tr>

                        <td><strong>Addon</strong></td>

                        <?php foreach($getStudentCourseAddons as $getStudentCourseAddon) : ?>

                            <td>

                                <p>

                                    <?php echo $getStudentCourseAddon['product_name']; ?>

                                    $<?php echo $getStudentCourseAddon['product_price']; ?>

                                </p>

                            </td>

                        <?php endforeach; ?>

                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </form>

</div>

<?php } include_once('single-class/footer.php'); ?>