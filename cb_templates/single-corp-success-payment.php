<?php
namespace CB;

include_once('single-class/header.php');?>

<?php
    $paid               = API::post("course/corpregister/{$course_id}")->jsonDecode()->getResponse();
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
}
?>
<?php include_once('single-class/footer.php');?>
