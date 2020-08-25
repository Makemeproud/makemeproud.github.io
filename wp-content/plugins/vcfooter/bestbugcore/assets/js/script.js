var $ = jQuery;
$(document).ready(function () {
    if ($("div").hasClass('wpbb_about_page')) {
        $.getJSON("http://bestbug.net/api/about-plugin.php", function (response) {
                var title = '<h2>' + response['header']['title'] + '</h1>\
                            <h4>' + response['header']['title2'] + '</h4>'
                $(".wpbb-plugin-title").append(title);
                $.each(response['plugin'], function (i, plugins) {
                    var html = '<li class="wpbb-list-li">\
                                <div class="wpbb-content-li">\
                                    <img src="' + plugins['url_img'] + '" class="wpbb-img" alt="">\
                                    <h2 class="wpbb-title">' + plugins['name'] + ' </h2>\
                                    <div class="wpbb-get-plugin-now"><a href="' + plugins['url'] + '" target="_blank" >Download Now</a></div>\
                                </div>\
                                <div class="wpbb-preview">\
                                    <img src="' + plugins['url_img_pre'] + '" class="wpbb-preview-img" alt="">\
                                </div>\
                            </li>';

                    $(".wpbb-list-ul").append(html);
                })
            },
        );
    }
})
 