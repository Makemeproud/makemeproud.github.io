var $ = jQuery;
$( document ).ready(function(){
    if($("body").hasClass("post-type-bbhd_content") || $("body").hasClass("post-type-bbhd_megamenu")){
        $('html.wp-toolbar').addClass('wpsg_active');
        var html = '<div class="wpsg_raiting">Enjoyed <b>Ultimate Header Builder</b>? Please leave us a <div class="wpsg_content_star">';
            html += ' <a href="mailto:bestbugteam@gmail.com" class="wpsg_star_1 fa fa-star" title = "Really bad"></a> ';
            html += ' <a href="mailto:bestbugteam@gmail.com" class="wpsg_star_2 fa fa-star" title ="Bad"></a>';
            html += ' <a href="mailto:bestbugteam@gmail.com" class="wpsg_star_3 fa fa-star" title = "Okay"></a>';
            html += ' <a href="https://1.envato.market/21118792" target="_blank" class="wpsg_star_4 fa fa-star" title = "Good"></a>';
            html += ' <a href="https://1.envato.market/21118792" target="_blank" class="wpsg_star_5 fa fa-star" title = "Very good"></a>';
            html += '</div> rating. We really appreciate your support!</div>';
        $('#footer-left').html(html);
        $('.wpsg_star_4').on('click',function(){
            setTimeout(function(){
                window.location.href = "mailto:bestbugteam@gmail.com";
            }, 1000);
        });
    }
})