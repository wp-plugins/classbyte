/**
 * ClassByte JavaScript API
 * @author Rw
 * @year 2014
 * @version 1.0
 * License: Not for public use
 */
var CB = (function($) {

    function _extend(defaults, options) {
        if (typeof defaults !== "object" || typeof options !== "object") {
            return {};
        }

        for (var i in options) {
            if (options.hasOwnProperty(i)) {
                for (var x in defaults) {
                    if (x !== i) {
                        defaults[i] = options[i];
                    }
                }
            }
        }

        return defaults;
    }

    function isArray(__var) {
        return Object.prototype.toString.call(__var) === '[object Array]';
    }

    /**
     * Private
     */
    var cb_form_area = $('#cb-form-area'),
        submitAjax = true;

    /**
     * Classbyte form steps
     */
    $(document).on('submit', '#cb_forms-only-ajax', function (e) {
        var $form = $(this);

        e.preventDefault();

        if ($form.prop('name') == "cb_payment_form" && $form.find('input[name=stripeToken]').length < 1) {
            submitAjax = false;

            var stripeResponseHandler = function(status, response) {
                if (response.error) {
                    cb_form_area.find('.alert').remove();
                    cb_form_area.prepend('<div class="alert alert-danger">' + response.error.message + '</div>');
                    $form.find('button').prop('disabled', false);
                } else {
                    var token = response.id;
                    $form.append($('<input type="hidden" name="stripeToken" />').val(token));
                    submitAjax = true;
                    $form.submit();
                }
            };

            $form.find('button').prop('disabled', true);

            try {
                Stripe.card.createToken($form, stripeResponseHandler);
            } catch (e) {
                submitAjax = true;
                console.log(e);
            }
        }

        if (submitAjax == false) {
            return;
        }

        $.ajax({
            type: 'POST',
            url: cbConfig.ajax_url,
            async: false,
            data: {
                action: 'cb_form',
                form_name: $(this).prop('name'),
                form_data: $(this).serialize()
            },
            beforeSend: function () {
                cb_form_area.append('<div id="cb-form-loading"></div>');
                cb_form_area.find('.alert').remove();
                cb_form_area.find('.has-error').removeClass('has-error');
                $form.find('button').prop('disabled', true);
            },
            success: function(result) {
                try {
                    if (result.success == true) {
                        if (result.data.redirect) {
                            var delay = 4000;

                            if (result.data.object
                                && result.data.object.hasOwnProperty('session_id')
                                && result.data.object.session_id
                            ) {
                                $.removeCookie(cbConfig.CB_COOKIE_NAME, { path: cbConfig.COOKIEPATH });
                                $.cookie(cbConfig.CB_COOKIE_NAME, result.data.object.session_id, { path: cbConfig.COOKIEPATH });
                            }

                            $('#cb_forms-only-ajax').slideUp('fast', function() {
                                if (result.data.message) {
                                    alertMessages({
                                        message: result.data.message + ' Please wait while you\'re being redirected...'
                                    });
                                }

                                if (result.data.hasOwnProperty('noDelay')
                                    && result.data.noDelay == true
                                ) {
                                    delay = 0;
                                }

                                setTimeout(function () {
                                    location.replace(result.data.redirect);
                                }, delay);
                            });
                        } else if (result.data.message) {
                            switchForm({
                                complete: alertMessages({
                                    message: result.data.message
                                })
                            });
                        }
                    } else if (result.success == false) {
                        if (result.data !== "") {
                            var error_data = result.data,
                                display_errors = new Array();

                            if (error_data.message) {
                                display_errors.push(error_data.message);
                            } else {
                                var labels = Object.keys(error_data);

                                $("#" + labels.join(', #')).each(function () {
                                    if (error_data[$(this).prop('id')]) {
                                        display_errors.push(
                                            $('label[for="' + $(this).prop('id') + '"], label[data-for="' + $(this).prop('id') + '"]')
                                            .text() .replace(' *', '') + ' ' + error_data[$(this).prop('id')]
                                        );
                                        $(this).closest('.form-group').addClass('has-error');
                                    }
                                });
                            }

                            alertMessages({
                                success: false,
                                messages: display_errors
                            });
                        }
                    }
                } catch(e) {
                    console.log(e, result);
                }
            },
            complete: function () {
                $form.find('button').prop('disabled', false);
                $('#cb-form-loading').remove();
            }
        });
    });

    /**
     * Display success or error messages
     */
    function alertMessages(options) {
        var defaults = {
                appendTo: cb_form_area,
                success: true
            },
            options = _extend(defaults, options),
            display_messages = '';

        if (options.success) {
            display_messages = '<div class="alert alert-success">';
        } else {
            display_messages = '<div class="alert alert-danger">';
        }

        if (options.message) {
            display_messages += '<p>' + options.message + '</p>';
        } else if (options.messages && isArray(options.messages)) {
            var i = 0;
            for(; i < options.messages.length; i++) {
                display_messages += '<p>' + options.messages[i] + '</p>';
            }
        }

        display_messages += '</div>';

        if (options.appendTo) {
            options.appendTo.prepend(display_messages);
        }
    }

    /**
     * Switch Login/Register form
     */
    function switchForm(options) {
        var options = options || {},
            login_reg_form = $('form.reg-page'),
            reg_form = $('#cb_reg_form'),
            login_form = $('#cb_login_form');

        if (!login_form.is(':visible')) {
            login_reg_form.prop('name', 'cb_login_form');
            login_form.show();
            reg_form.hide();
        } else {
            login_reg_form.prop('name', 'cb_reg_form');
            login_form.hide();
            reg_form.show();
        }

        // complete callback
        if (typeof options.complete === "function") {
            options.complete();
        }
    }

    $(document).on('click', 'a[data-switch-form]', function(e) {
        e.preventDefault();
        $('.alert').remove();
        switchForm();
    });

    $(document).on('click', '.mini-request', function(e) {
        e.preventDefault();

        var self = $(this), event = null, removeCookie = false;

        switch (self.prop('id')) {
            case 'cb_sign_out':
                event = 'sign_out';
                removeCookie = true;
                break;
            default:
                break;
        }

        $.ajax({
            type: 'POST',
            url: cbConfig.ajax_url,
            data: {
                action: 'mini_requests',
                event: event,
                _: Date.now()
            },
            async: false,
            beforeSend: function() {
                self.after('<img src="' + cbConfig.assets_url + 'img/progress-dots.gif" alt="" class="progress-loader">');
            },
            success: function(data) {
                if (data.success == true) {

                    // remove cookie session cookie
                    if (removeCookie) {
                        $.removeCookie(cbConfig.CB_COOKIE_NAME, { path: cbConfig.COOKIEPATH });
                    }

                    location.replace(data.data);
                }
            },
            complete: function () {
                self.next('.progress-loader').remove();
            }
        });
    });

    $('#map-canvas').css({
        height: parseInt($('#course_information .table-responsive').height(), 10) + 'px'
    });
}(jQuery));
