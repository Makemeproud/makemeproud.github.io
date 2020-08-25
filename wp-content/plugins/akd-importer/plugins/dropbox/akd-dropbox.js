/**
 * Created by FOX on 8/15/2016.
 */
jQuery(function ($) {
    "use strict";

    /* get access token. */
    $('#dropbox-code').on('change', function () {
        var code = $('#dropbox-code').val();
        /* demo name not null. */
        if(code == undefined || code == ''){
            return;
        }

        $(this).prop('disabled', true);
        $('#dropbox-loading').addClass('is-active');

        $.post(ajaxurl, {
            'action': 'akd_dropbox_get_access_token',
            'code': code,
        }, function (response) {
            $('#dropbox-loading').removeClass('is-active');
        });
    });

    /** select folder. */
    $('#dropbox-select-dir').on('click', function () {

        /* demo name not null. */
        var code = $('#dropbox-code').val();
        if(code == undefined || code == ''){
            $('#dropbox-code').trigger('focus');
            return;
        }

        if($('.tree-content .jqueryFileTree').length > 0){
            return;
        }

        var _dir = $(this);
        var _tree = $('.tree-content').fileTree({
            root: '/',
            script: ajaxurl + '?action=akd_dropbox_get_access_files',
            expandSpeed: 500,
            collapseSpeed: 500,
            multiFolder: false,
        }, function (file) {
            $('#dropbox-dir').val(file);
        });
    });

    /* upload. */
    $('#dropbox-upload').on('click', function () {
        var btn = $(this);
        btn.prop('disabled', true);
        $('#dropbox-loading').addClass('is-active');

        $.post(ajaxurl, {
            'action': 'akd_dropbox_upload',
            'dir': $('#dropbox-dir').val(),
            'package' : $('#create-pakage').prop('checked'),
        }, function (response) {
            btn.prop('disabled', false);
            $('#dropbox-loading').removeClass('is-active');
            $('#dropbox-url').text(response).attr('href', response).css('display','block');
        });
    });
})