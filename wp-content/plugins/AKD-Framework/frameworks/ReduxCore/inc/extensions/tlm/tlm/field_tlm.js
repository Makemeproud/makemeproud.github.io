/*global jQuery, document, redux*/

jQuery(function($) {
    "use strict";
    $("#info-verification_status").after(" <h4 class='more_info' ><i class='fa fa-life-ring'></i>For more information, open a ticket at <a href='https://designingmedia.com/support/' target='_blank'>Support Portal.</a></h4>");
    $(document).on('confirmation', '.popup-license', function() {
        $('.redux-group-tab-link-li.redux-tlm-class a').click();
    });
    $(document).on('click', 'div#tlm-faq h4', function() {
        $(this).parents('div#tlm-faq').find('span').toggleClass('hide')
    });

    function note_message(data) {
        if (data['status'] == 'error') {
            $('#info-verification_status').removeAttr('class');
            $('#info-verification_status').addClass("redux-critical redux-notice-field");
        }

        if (data['status'] == 'success') {
            $('#info-verification_status').removeAttr('class');
            $('#info-verification_status').addClass("redux-success redux-notice-field");
        }

        $('#info-verification_status p').html(data['msg']);
        $('#info-verification_status').show('fast');
    }

    $('.redux-container').on('click', '.validation_activate_buttons', function(e) {
        e.preventDefault();
        $('#info-verification_status').hide('fast');
        var purchase_code = $('#purchase_code_verification').val();
        var tlm_site_type = $('input:radio.tlm_site_type:checked').val();
        var verify = $(this).data('verify');
        if (purchase_code == '') {
            var response = 'Please, enter purchase code.';
            note_message(response);
        } else {
            $.ajax({
                url: ajaxurl,
                type: 'post',
                data: {
                    action: 'current_theme_verification',
                    purchase_code: purchase_code,
                    verify: verify,
                    type: tlm_site_type
                },
                success: function(response) {
                    var data = JSON.parse(response);

                    note_message(data);

                },
                error: function(errorThrown) {
                    console.log(errorThrown);
                }
            });
        }
    });
});