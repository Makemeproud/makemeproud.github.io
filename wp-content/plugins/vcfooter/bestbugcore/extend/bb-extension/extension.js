var $ = jQuery;

function bestbugColorManage(obj) {
    if(typeof($.cookie("bb-list-color-cookies")) == "undefined" || $.cookie("bb-list-color-cookies") == null) {
        var defaultColor = [
            {"hexString":"#800000","name":"Maroon"},
            {"hexString":"#ff0000","name":"Red"},
            {"hexString":"#ff00ff","name":"Fuchsia"},
            {"hexString":"#00ffff","name":"Aqua"},
            {"hexString":"#00ff00","name":"Lime"},
            {"hexString":"#ffff00","name":"Yellow"}
        ];
        $.cookie("bb-list-color-cookies",JSON.stringify(defaultColor));
    }
    this.open=function(){
        var html="";
        html +='<div class="bb-color-manege-content"> ';
        html +='<div class="bb-color-manege"> ';
        html +=     '<header>';
        html +=         '<h3>Colors Manage</h3>';
        html +=             '<div class="bb-header-function"> ';
        html +=                 '<a class="bb-get-color"><i class="fa fa-check" aria-hidden="true"></i></a>';
        html +=                 '<a class="bb-closed-color"><i class="fa fa-times" aria-hidden="true"></i></a>';
        html +=             '</div>';
        html +=     '</header>';
        html +=     '<div class="bb-color-manege-body"> ';
        html +=         '<div class="bb-color-manege-body-left">';
        html +=             '<h2>Add new color</h2>';
        html +=             '<div class="bb-color-manege-add-new">';
        html +=                 '<input type="text" placeholder="Color Label" name="bb-title-color">';
        html +=                 '<input type="text" placeholder="Color HEX" name="bb-value-color-HEX">';
        html +=                 '<input type="color" name="bb-value-color">';
        html +=             '</div>';
        html +=             '<div class="bb-color-manege-add-buttom">';
        html +=                 '<a class="bb-color-manege-add-buttom-addly">Apply Now <i class="fa fa-check" aria-hidden="true"></i></a>';
        html +=                 '<a class="bb-color-manege-add-buttom-add-to-list">Add To List <i class="fa fa-arrow-right" aria-hidden="true"></i></a>';
        html +=             '</div>';
        html +=             '<ul class="bb-color-list">';
        $.each(obj.listColor,function(i,v){
            html +=             '<li class="bb-color-li" data-color="'+v.hexString+'" style="color:'+v.hexString+'">';
            html +=                 v.name;
            html +=             '</li>';
        });
        html +=             '</ul>';
        html +=         '</div>';
        html +=         '<div class="bb-color-manege-body-right">';
        html +=             '<div class="bb-color-manege-body-right-head">';
        html +=                 '<h2>List of your colors</h2>';
        html +=                 '<a class="bb-color-manege-add-buttom-delete-sel">Detele selection</a>';
        html +=                 '<a class="bb-color-manege-add-buttom-unselect-all">Unselect all</a>';
        html +=                 '<a class="bb-color-manege-add-buttom-select-all">Select all</a>';
        html +=             '</div>';
        html +=             '<ul class="bb-color-manege-list-color-selected">';
        $.each(JSON.parse($.cookie("bb-list-color-cookies")),function(i,v){
            html +=              '<li class="bb-color-manege-list-color-selected-li" style="border-left: 10px solid '+v.hexString+';" data-color="'+v.hexString+'"><label><input type="checkbox"><span>'+v.name+'</span></label></li>';
        });
        html +=             '</ul>';
        html +=         '</div>';
        html +=     '</div>';
        html +='</div>';
        html +='</div>';
        $('body').append(html);
        $('.bb-color-li').on('click',function(){
            var color = $(this).attr('data-color');
            var name = $(this).text();
            $('.bb-color-manege-add-new').find('[name="bb-title-color"]').val(name)
            .siblings('[name="bb-value-color-HEX"]').val(color)
            .siblings('[name="bb-value-color"]').val(color);
        })
        $('body .bb-color-manege-add-new [name="bb-value-color-HEX"]').on('input change',function(){
            $(this).siblings('[name="bb-value-color"]').val($(this).val());
        })
        $('body .bb-color-manege-add-new [name="bb-value-color"]').on('change',function(){
            $(this).siblings('[name="bb-value-color-HEX"]').val($(this).val());
        })
        $('.bb-color-manege-add-buttom .bb-color-manege-add-buttom-add-to-list').on('click',function(){
            var lihtml = '<li class="bb-color-manege-list-color-selected-li" style="border-left: 10px solid '+$('.bb-color-manege-add-new').find('[name="bb-value-color-HEX"]').val()+';" data-color="'+$('.bb-color-manege-add-new').find('[name="bb-value-color-HEX"]').val()+'"><label><input type="checkbox"><span>'+$('.bb-color-manege-add-new').find('[name="bb-title-color"]').val()+'</span></label></li>';
            $('.bb-color-manege-list-color-selected').append(lihtml);
            var defaultColor = JSON.parse($.cookie("bb-list-color-cookies"));
            defaultColor.push({"hexString":$('.bb-color-manege-add-new').find('[name="bb-value-color-HEX"]').val(),"name":$('.bb-color-manege-add-new').find('[name="bb-title-color"]').val()});
            $.cookie("bb-list-color-cookies",JSON.stringify(defaultColor));
            $('.bb-color-manege-add-new').find('[name="bb-title-color"]').val("")
            .siblings('[name="bb-value-color-HEX"]').val("")
            .siblings('[name="bb-value-color"]').val("");
        })
        $('.bb-color-manege-body-right-head').on('click','.bb-color-manege-add-buttom-select-all',function(){
            $('.bb-color-manege-list-color-selected-li input').attr('checked','checked');
        })
        $('.bb-color-manege-body-right-head').on('click','.bb-color-manege-add-buttom-unselect-all',function(){
            $('.bb-color-manege-list-color-selected-li input').removeAttr('checked');
        })
        $('.bb-color-manege-body-right-head').on('click','.bb-color-manege-add-buttom-delete-sel',function(){
            var defaultColor = JSON.parse($.cookie("bb-list-color-cookies"));
            $.each($('.bb-color-manege-list-color-selected-li'),function(i,v){
                if ($(v).find('input').is(":checked")) {
                    defaultColor= defaultColor.splice(i,1);
                    $(v).remove();
                }
            })
            $.cookie("bb-list-color-cookies",JSON.stringify(defaultColor));
        })
        $('.bb-header-function').on('click','.bb-get-color',function(){
            var data = [];
            $.each($('.bb-color-manege-list-color-selected-li'),function(i,v){
                if ($(v).find('input').is(":checked")) {
                    data.push({"hexString":$(v).attr('data-color'),"name":$(v).find('span').text()});
                }
            })
            obj.getValue(data);
            $('body .bb-color-manege-content').remove();
        })
        $('.bb-header-function').on('click','.bb-closed-color',function(){
            $('body .bb-color-manege-content').remove();
        })
        $('.bb-color-manege-add-buttom .bb-color-manege-add-buttom-addly').on('click',function(){
            var data = [{"hexString":$('.bb-color-manege-add-new').find('[name="bb-value-color-HEX"]').val(),"name":$('.bb-color-manege-add-new').find('[name="bb-title-color"]').val()}];
            obj.getValue(data);
            $('body .bb-color-manege-content').remove();
        })
        $('.bb-color-manege').on('click',function(e){
           e.stopPropagation();
        })
        $('.bb-color-manege-content').on('click',function(){
            $('body .bb-color-manege-content').remove();
        })
    }
}

$.fn.syncVal = function(selecter){
    var mySelf =this;
    $(mySelf).on('input change',function(){
        $(selecter).val($(mySelf).val());
    })
    $(selecter).on('input change',function(){
        $(mySelf).val($(selecter).val());
    })
}
function inn(data){
    console.log(data);
}
function getGoogleFonts(obj){
    var seft = this;
    seft.selectedFonts=[];
    if (obj.oldFont) {
        seft.selectedFonts = obj.oldFont;
    }
    seft.open=function(){
        $.getJSON( "https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyB2HZF6yb_ObOkWkq5JbvbmajDZ4N-vm7E", function( data ) {
        var html =  '<div class="bb-google-load-fonts-container">';
            html +=  '<div class="bb-google-load-fonts">';
            html +=     '<div class="bb-load-fonts-header">';
            html +=         '<div class="bb-load-fonts-title">';
            html +=             '<h2>Fonts Manager</h2>';
            html +=             '<div class="bb-load-fonts-select">';
            html +=                 '<i class="fa fa-check bb-ruturn-font" aria-hidden="true"></i>';
            html +=                 '<i class="fa fa-times bb-cancel-font" aria-hidden="true"></i>';
            html +=             '</div>';
            html +=         '</div>';
            html +=         '<div class="bb-load-fonts-menu">';
            html +=             '<ul>\
                                    <li>\
                                        <select id="bb-google-fonts-language">\
                                            <option value="">All subsets</option>\
                                            <option value="arabic">Arabic</option>\
                                            <option value="bengali">Bengali</option>\
                                            <option value="cyrillic">Cyrillic</option>\
                                            <option value="cyrillic-ext">Cyrillic Extended</option>\
                                            <option value="devanagari">Devanagari</option>\
                                            <option value="greek">Greek</option>\
                                            <option value="greek-ext">Greek Extended</option>\
                                            <option value="gujarati">Gujarati</option>\
                                            <option value="hebrew">Hebrew</option>\
                                            <option value="khmer">Khmer</option>\
                                            <option value="latin">Latin</option>\
                                            <option value="latin-ext">Latin Extended</option>\
                                            <option value="tamil">Tamil</option>\
                                            <option value="telugu">Telugu</option>\
                                            <option value="thai">Thai</option>\
                                            <option value="vietnamese">Vietnamese</option>\
                                        </select>\
                                    </li>\
                                    <li>\
                                        <select id="bb-google-fonts-category">\
                                            <option value="">All Categories</option>\
                                            <option value="serif">Serif</option>\
                                            <option value="sans-serif">Sans Serif</option>\
                                            <option value="display">Display</option>\
                                            <option value="handwriting">Handwriting</option>\
                                            <option value="monospace">Monospace</option>\
                                        </select>\
                                    </li>\
                                    <li>\
                                        <div class="bb-load-fonts-title-shearch">\
                                            <input type="text" placeholder="Search">\
                                            <i class="fa fa-search" aria-hidden="true"></i>\
                                        </div>\
                                    </li>\
                                </ul>';
            html +=         '</div>';
            html +=     '</div>';
            html +=     '<div class="bb-load-fonts-body">';
            html +=         '<div class="bb-load-fonts-seleted">';
            html +=             '<h4>Fonts seleted:</h4>';
            html +=             '<ul class="bb-load-fonts-seleted-ul">';
            html +=             '</ul>';
            html +=         '</div>';
            html +=         '<div class="bb-load-fonts-list">';
            html +=             '<ul class="bb-load-fonts-list-ul">';
            html +=             '</ul>';
            html +=         '</div>';
            html +=     '</div>';
            html += '</div>';
            html += '</div>';
            $('body').append(html);
            pagination(data.items);
            $('#bb-google-fonts-language').on('change',function(){
                pagination( filterData(data.items,$('#bb-google-fonts-language').val(),$('#bb-google-fonts-category').val(),$('.bb-load-fonts-title-shearch input').val().replace(/ /g,"")) );
            })
            $('#bb-google-fonts-category').on('change',function(){
                pagination( filterData(data.items,$('#bb-google-fonts-language').val(),$('#bb-google-fonts-category').val(),$('.bb-load-fonts-title-shearch input').val().replace(/ /g,"")) );
            })
            $('.bb-load-fonts-title-shearch input').on('input change',function(){
                pagination( filterData(data.items,$('#bb-google-fonts-language').val(),$('#bb-google-fonts-category').val(),$('.bb-load-fonts-title-shearch input').val().replace(/ /g,"")) );
            })
            $('.bb-ruturn-font').on('click',function(){
                obj.select(seft.selectedFonts);
                canceltag();
            })
            $('.bb-cancel-font').on('click',function(){
                canceltag();
            })
            $('.bb-google-load-fonts-container').on('click',function(){
                canceltag();
            })
            $('.bb-google-load-fonts').on('click',function(){
                event.stopPropagation();
            })
        });
    }
    function pagination(data){
        var denseKeys = Object.keys(data).map(function (x) { 
            return parseInt(x, 10); 
        });
        $('.bb-load-fonts-list').pagination({
            dataSource: denseKeys,
            pageSize: 51,
            showPageNumbers: true,
            showFirstOnEllipsisShow:true,
            position: 'top',
            pageRange:10,
            callback: function(data2, pagination) {
                var html = "";
                var indexSelectes = seft.selectedFonts.map(function(x){
                    return x.index;
                })
                $.each(data2,function(i,v){
                    html += inFont(v,data[v],indexSelectes);
                })
                $('.bb-load-fonts-list-ul').html(html);
                $('.bb-google-fonts-files').on('change',function(){
                    $(this).closest('.bb-load-fonts-list-font').find('h3').css('font-style','normal').css('font-weight','normal');
                    if ( $(this).val().indexOf('100')!=-1|| $(this).val().indexOf('200')!=-1|| $(this).val().indexOf('300')!=-1|| $(this).val().indexOf('400')!=-1|| $(this).val().indexOf('500')!=-1|| $(this).val().indexOf('600')!=-1|| $(this).val().indexOf('700')!=-1|| $(this).val().indexOf('800')!=-1|| $(this).val().indexOf('900')!=-1) {
                    	$(this).closest('.bb-load-fonts-list-font').find('h3').css('font-weight', $(this).val().replace("italic", ""));
                    }
                    if ( $(this).val().indexOf('italic')!=-1) {
                    	$(this).closest('.bb-load-fonts-list-font').find('h3').css('font-style','italic');
                    }
                })
                $('.bb-load-fonts-list-font-funct .bb-add-font').on('click',function(){
                    if (!$(this).hasClass('bb-add-font-unable')) {
                        $(this).removeClass('fa-plus').addClass('bb-add-font-unable').addClass('fa-check');
                        var index = $(this).attr('data-index');
                        var dataAdd =  data[index];
                        dataAdd['index'] = index;
                        seft.selectedFonts.push(dataAdd);
                        inSelectFont(seft.selectedFonts);
                        
                    }
                })
            }
        })
        
    }
    function inFont(index,data,indexSelectes){
        html ="";
        html +='<li class="bb-load-fonts-list-li">';
        html += '<div class="bb-load-fonts-list-font">';
        html +=     '<style>';
        $.each(data.files,function(i4,v4){
            html +=     '@font-face {';
            html +=         'font-family: '+data.family+';';
            html +=         'src: url('+v4+');';
            if (i4.indexOf('100')!=-1||i4.indexOf('200')!=-1||i4.indexOf('300')!=-1||i4.indexOf('400')!=-1||i4.indexOf('500')!=-1||i4.indexOf('600')!=-1||i4.indexOf('700')!=-1||i4.indexOf('800')!=-1||i4.indexOf('900')!=-1) {
            html +=          'font-weight: '+i4.replace("italic", "")+';';
            }
            if (i4.indexOf('italic')!=-1) {
            html +=           'font-style: italic;';
            }
            html +=      '}';
        })
        html +=     '</style>';
        html +=     '<h3 style="font-family:\''+data.family+'\'" contenteditable="">'+data.family+'</h3>'
        html +=     '<span>'+data.category+'</span>';
        html +=     '<div class="bb-load-fonts-list-font-funct">';
        html +=         '<select class="bb-google-fonts-files">\
                            <option value="">Style & Weight</option>'
        $.each(data.variants,function(i2,v2){
            html +=         '<option value="'+v2+'">'+v2+'</option>';
        })
        html +=         '</select>';
        if (indexSelectes.indexOf(index+"")!=-1) {
            html +=         '<i class="fa fa-check bb-add-font bb-add-font-unable" data-index="'+index+'" aria-hidden="true"></i>';
        } else {
            html +=         '<i class="fa fa-plus bb-add-font" data-index="'+index+'" aria-hidden="true"></i>';
        }
        html +='    </div>';
        html +='</div>';
        html +='</li>';
        return html;
    }
    function inSelectFont(data){
        var html ="";
        $.each(data,function(i,v){
            html +=                 '<li class="bb-load-fonts-seleted-li" data-index="'+v.index+'">';
            html +=                     '<div class="bb-load-fonts-seleted-font">';
            html +=     '<style>';
            $.each(v.files,function(i4,v4){
            html +=         '@font-face {';
            html +=             'font-family: '+v.family+';';
            html +=             'src: url('+v4+');';
            if (i4.indexOf('100')!=-1||i4.indexOf('200')!=-1||i4.indexOf('300')!=-1||i4.indexOf('400')!=-1||i4.indexOf('500')!=-1||i4.indexOf('600')!=-1||i4.indexOf('700')!=-1||i4.indexOf('800')!=-1||i4.indexOf('900')!=-1) {
            html +=             'font-weight: '+i4.replace("italic", "")+';';
            }
            if (i4.indexOf('italic')!=-1) {
            html +=             'font-style: italic;';
            }
            html +=             '}';
            })
            html +=     '</style>';
            html +=                          '<h3 style="font-family:\''+v.family+'\'" contenteditable="">'+v.family+'</h3>'
            html +=                          '<div class="bb-load-fonts-seleted-font-funct">';
            html +=                                 '<i class="fa fa-times  bb-remove-font" data-stt="'+i+'" aria-hidden="true"></i><i class="fa fa-cogs  bb-remove-settings" aria-hidden="true"></i>';
            html +=                          '</div>';
            html +=                     '</div>';
            html +=                 '</li>';
        })
        $('.bb-load-fonts-seleted-ul').html(html);
        $('.bb-remove-font').on('click',function(){
            seft.selectedFonts.splice($(this).attr('data-stt'),1);
            inSelectFont(seft.selectedFonts);
            $('.bb-load-fonts-list-font-funct .fa-check[data-index="'+$(this).closest('.bb-load-fonts-seleted-li').attr('data-index')+'"]').addClass('fa-plus').removeClass('bb-add-font-unable').removeClass('fa-check');
        })
    }
    function filterData(data,subsets,category,name){
        var filterData = [];
        $.each(data,function(i,v){
            var filter1 = v.category == category;
            var filter2 = v.subsets.indexOf(subsets)!=-1;
            var filter3 = v.family.toLowerCase().indexOf(name.toLowerCase())!=-1;
            if (!category) {
                filter1 = true;
            }
            if (!subsets) {
                filter2 = true;
            }
            if (!name) {
                filter3 = true;
            }
            if (filter1 && filter2 && filter3) {
                filterData.push(v);
            };
        })
        return filterData;
    }
    function canceltag(){
        $('.bb-google-load-fonts-container').remove();
    }
}