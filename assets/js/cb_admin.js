var cbAdmin = (function($) {

    // jQuery UI Tabs
    $('#tabs').tabs();

    var self;

    // Color Picker
    $('.cb-colorpicker').click(function() { self = $(this); }).ColorPicker({
        onShow: function (colpkr) {
            $(colpkr).fadeIn(500);
            return false;
        },
        onHide: function (colpkr) {
            $(colpkr).fadeOut(500);
            return false;
        },
        onChange: function (hsb, hex, rgb) {
            self.children('div').css('backgroundColor', '#' + hex);
            self.closest('td').find('input[type=hidden]').val('#' + hex);
        }
    });

    // Setting default or dynamic values for colorpicker box

    $('.colorpicker-val-swap').each(function (index, element) {
        var colorValue = $(element).val();
        $(element).closest('td').find('.cb-colorpicker > div').css('backgroundColor', colorValue);
    });

}(jQuery));