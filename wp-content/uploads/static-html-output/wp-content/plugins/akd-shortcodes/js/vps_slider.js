var def_pos =2;
var step_value =1;
var paddingmin=0;
var paddingmax=0;
if(jQuery(window).width()>767){
	paddingmin=85;
	paddingmax=85;	
}
else{
	paddingmin=0;
	paddingmax=0;
}
jQuery(document).ready(function(){
  if(jQuery( "#slider" ).length>0){
    var updatevalues= function(val){
        jQuery('.slider-container #opt1Val').html(opt1arr[val-1]);
        jQuery('.slider-container #opt2Val').html(opt2arr[val-1]);
        jQuery('.slider-container #opt3Val').html(opt3arr[val-1]);
        jQuery('.slider-container #opt4Val').html(opt4arr[val-1]);
        var dPrice=discount_price_arr[val-1];
        dp= parseInt(dPrice);

        if(dp>0) {
            jQuery('.slider-container #price_wrap').html('<div class="slider_pirce_old">'+currency+price_arr[val-1]+'<span>/'+period_text+'</span></div><div class="price_txt"><span class="dollar"></span><span id="price_val">'+currency+discount_price_arr[val-1]+'</span>/'+period_text+'</div>');

        }
        else{
            jQuery('.slider-container #price_wrap').html(' <div class="price_txt"><span class="dollar"></span><span id="price_val">'+currency+price_arr[val-1]+'</span>/'+period_text+' </div>');

        }
        jQuery('.slider-container a.buynow-button').attr('href', link_arr[val-1]);
        jQuery('.slider-container div.price_rangetxt div').removeClass('current');
        jQuery('.slider-container div.price_rangetxt div#icon-'+(val-1)).addClass('current');
    }
if(sliderType!=''&&sliderType!=1) {
    jQuery("#slider").slider({
        range: 'min',
        animate: true,
        orientation: "vertical",
        min: minSliderVal,
        max: maxSliderVal,
        paddingMin: 30,
        paddingMax: 30,
        step: step_value,
        slide: function (event, ui) {
            updatevalues(ui.value);
        },
        change: function (event, ui) {
            updatevalues(ui.value);
        }
    });
}
else{
    jQuery("#slider").slider({
        range: 'min',
        animate: true,
        min: minSliderVal,
        max: maxSliderVal,
        paddingMin: paddingmin,
        paddingMax: paddingmax,
        step: step_value,
        slide: function (event, ui) {
            updatevalues(ui.value);

        },
        change: function (event, ui) {
            updatevalues(ui.value);
        }
    });
}
    var ch_value=0;

    jQuery( "#amount" ).val( "$" + jQuery( "#slider" ).slider( "value" ) );
    jQuery('#slider').slider('value', def_pos);
    jQuery('.slider-container .icon').click(function() {
        ch_value= parseInt(this.id.slice(5)) + 1;
        jQuery(".slider-container div.price_rangetxt div").removeClass("current");
        jQuery('#slider').slider('value', ch_value);
    });
  }
});