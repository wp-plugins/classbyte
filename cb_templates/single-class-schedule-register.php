<?php include_once('single-class/header.php'); ?>
<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <form class="reg-page" id="cb_forms-only-ajax" method="post" name="cb_reg_form">
        <div id="cb_reg_form">
            <div class="reg-header">
                <h2>Register a new account</h2>
                <p>Already Signed Up? Click <a data-switch-form="cb_login_form" href="#">Sign In</a> to login your account.</p>
            </div>

            <div class="form-group">
                <label for="studentsname">Student First Name <span class="color-red">*</span></label>
                <input class="form-control" type="text" value="" id="studentsname" name="studentsname">
            </div>
            <div class="form-group">
                <label for="studentlastname">Student Last Name <span class="color-red">*</span></label>
                <input class="form-control" type="text" value="" name="studentlastname" id="studentlastname">
            </div>
            <div class="form-group">
                <label for="studentaddress">Street Address <span class="color-red">*</span></label>
                <input class="form-control" type="text" value="" name="studentaddress" id="studentaddress">
            </div>
            <div class="form-group">
                <label for="studentaddress2">Street Address 2</label>
                <input class="form-control" type="text" value="" name="studentaddress2" id="studentaddress2">
            </div>
            <div class="form-group">
                <label for="studentcity">City</label>
                <input class="form-control" type="text" value="" id="studentcity" name="studentcity">
            </div>
            <div class="form-group">
            <label for="studentstate">State <em>(e.g. TX)</em></label>
                <select id="studentstate" class="form-control" name="studentstate">
                    <option selected="selected" value="">-- Select --</option>
                    <option value="AK">Alaska</option>
                    <option value="AL">Alabama</option>
                    <option value="AR">Arkansas</option>
                    <option value="AZ">Arizona</option>
                    <option value="CA">California</option>
                    <option value="CO">Colorado</option>
                    <option value="CT">Connecticut</option>
                    <option value="DE">Delaware</option>
                    <option value="FL">Florida</option>
                    <option value="GA">Georgia</option>
                    <option value="HI">Hawaii</option>
                    <option value="IA">Iowa</option>
                    <option value="ID">Idaho</option>
                    <option value="IL">Illinois</option>
                    <option value="IN">Indiana</option>
                    <option value="KS">Kansas</option>
                    <option value="KY">Kentucky</option>
                    <option value="LA">Louisiana</option>
                    <option value="MA">Massachusetts</option>
                    <option value="MD">Maryland</option>
                    <option value="ME">Maine</option>
                    <option value="MI">Michigan</option>
                    <option value="MN">Minnesota</option>
                    <option value="MO">Missouri</option>
                    <option value="MS">Mississippi</option>
                    <option value="MT">Montana</option>
                    <option value="NC">North Carolina</option>
                    <option value="ND">North Dakota</option>
                    <option value="NE">Nebraska</option>
                    <option value="NH">New Hampshire</option>
                    <option value="NJ">New Jersey</option>
                    <option value="NM">New Mexico</option>
                    <option value="NV">Nevada</option>
                    <option value="NY">New York</option>
                    <option value="OH">Ohio</option>
                    <option value="OK">Oklahoma</option>
                    <option value="OR">Oregon</option>
                    <option value="PA">Pennsylvania</option>
                    <option value="PR">Puerto Rico</option>
                    <option value="RI">Rhode Island</option>
                    <option value="SC">South Carolina</option>
                    <option value="SD">South Dakota</option>
                    <option value="TN">Tennessee</option>
                    <option value="TX">Texas</option>
                    <option value="UT">Utah</option>
                    <option value="VA">Virginia</option>
                    <option value="VT">Vermont</option>
                    <option value="WA">Washington</option>
                    <option value="WI">Wisconsin</option>
                    <option value="WV">West Virginia</option>
                    <option value="WY">Wyoming</option>
                </select>
            </div>
            <div class="form-group">
                <label for="studentzip">Zip *</label>
                <input class="form-control" type="text" value="" id="studentzip" name="studentzip">
            </div>
            <div class="form-group">
                <label for="studentphone">Phone Number</label>
                <input class="form-control"   data-mask="999-999-9999" type="text" value="" id="studentphone" name="studentphone">
            </div>
            <div class="form-group">
                <label for="studentmobilephone">Mobile Number</label>
                <input class="form-control" type="text"  data-mask="999-999-9999"  value="" name="studentmobilephone" id="studentmobilephone">
            </div>
            <div class="form-group">
                <label for="studentemddress">Email Address *</label>
                <div class="input-group">
                    <span class="input-group-addon"><span class="">@</span></span>
                    <input class="form-control" type="email" value="" id="studentemddress" name="studentemddress">
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <label for="studentpassword" class="control-label">Password <span class="color-red">*</span></label><br>
                    <input class="form-control" type="password" value="" id="studentpassword" name="studentpassword">
                </div>
                <div class="col-lg-6">
                    <label for="studentpassword2">Confirm Password <span class="color-red">*</span></label><br>
                    <input class="form-control" type="password" value="" id="studentpassword2" name="studentpassword2">
                </div>
            </div>
            <hr>

            <div class="row">
                <div class="col-lg-6 text-left">
                    <input type="hidden" name="_cb_nonce" value="<?php echo wp_create_nonce('cb_forms-only-ajax'); ?>">
                    <?php if (isset($fcd['scheduledcoursesid'])) {
                        $course_id = $fcd['scheduledcoursesid'];
                        echo '<input type="hidden" name="course_id" value="' . $course_id .'">';
                    } ?>
                    <input type="submit" class="btn" value="Register">
                </div>
            </div>
        </div>
        <?php do_shortcode('[cb_class_schedule_login parent="no" reg_header="yes"]'); ?>
    </form>
</div>
<?php include_once('single-class/footer.php'); ?>