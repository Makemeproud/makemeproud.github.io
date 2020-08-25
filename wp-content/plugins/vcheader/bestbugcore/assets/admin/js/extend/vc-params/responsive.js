if (_.isUndefined(window.vc)) {
	var vc = {atts: {}};
}

(function($) {
	'use strict';

    var BBResponsiveModel = Backbone.Model.extend({
        defaults: {
            "name": "",
            "value": "",
            "use": "",
            
            marginTop: '',
            marginRight: '',
            marginBottom: '',
            marginLeft: '',
            
            paddingTop: '',
            paddingRight: '',
            paddingBottom: '',
            paddingLeft: '',
            
            borderTopWidth: '',
            borderRightWidth: '',
            borderBottomWidth: '',
            borderLeftWidth: '',
            borderColor: '',
            borderStyle: '',
            
            borderTopLeftRadius: '',
            borderTopRightRadius: '',
            borderBottomRightRadius: '',
            borderBottomLeftRadius: '',
            
            backgroundImage: '',
            backgroundColor: '',
            backgroundSize: '',
            backgroundPosition: '',
            backgroundRepeat: '',
            backgroundAttachment: '',
            
            color: '',
			fontFamily: '',
            fontSize: '',
            lineHeight: '',
            letterSpacing: '',
            fontWeight: '',
            fontStyle: '',
            textTransform: '',
            textDecoration: '',
            textOverflow: '',
            whiteSpace: '',
            wordSpacing: '',
            textAlign: '',
            
            'display': '',
            
            'width': '',
            'height': '',
            
            'maxWidth': '',
            'maxHeight': '',
            
            'position': '',
            'top': '',
            'right': '',
            'bottom': '',
            'left': '',
            
            "selector": "",
        }
    });

    var BBResponsiveView = Backbone.View.extend({
        events: {
            "change input.bb-binddata": "changed",
            "change select.bb-binddata": "changed",
            "changeColor": "changed"
        },
        templateSettings: _.templateSettings = {
            interpolate: /\{\{(.+?)\}\}/g
        },
        template: _.template($('#BESTBUG_EXTEND_VCPARAMS_RESPONSIVE').html()),
        initialize: function() {
            if(this.model.get('value') != '') {
                var value = this.model.get('value').split(/\s*(\.[^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/g);
    			value = (value[2] && value[2].replace(/!important/ig, ""));
                value = value.split(/;/);
                var self = this;
                
                // selector
                var selector = this.model.get('value').split(/\s*(\.[^\{]+)\s*\{\s*([^\}]+)\s*\}\s*/g);
                var is_selector = (selector[1] && selector[1].replace(/\.(bb_custom_)\d*/ig, ""));
                if(is_selector.trim().length > 0) {
                    selector = (selector[1] && selector[1].replace(/\.(bb_custom_)\d*/ig, "#class#"));
                    this.model.set({selector: selector});
                }
                
                $.each(value, function( index, val ) {
                    var property = val.split(/\:/);
                    if(property.length >= 2) {
                        var newSettingsValue = property[1].trim(),
                            key = self.toCamel(property[0]);
                        
                        if($.inArray(key, ['value', 'name', 'use', 'selector']) >= 0) {
                            return true;
                        }
                        if(key == 'backgroundImage') {
    						newSettingsValue = val.replace("background-image:url(", "").replace(")", "");
    					}
                        self.model.set( {[key]:newSettingsValue} );
                    }
                });
            }
            
            this.render();
            _.bindAll(this, 'render');
        },
        render: function() {
            var self = this;
            
            this.$el.html(this.template(this.model.toJSON()));
            $.each(this.model.get('use').split(','), function( index, temp ) {
                self.$el.find('[data-template="' + temp + '"]').css({ display: 'block' });
            });
            this.$el.find('.bb-responsive-selector .bb-text-examples a').on('click', function(){
                if(!$(this).data('example')) {
                    return;
                }
                $(this).closest('.bb-responsive-section').find('input').val($(this).data('example'));
                self.model.set({selector: $(this).data('example')});
            });
            this.$el.find('.bb-color-picker').wpColorPicker({
                change: function(event, ui){
                    self.changedColor( event, ui.color.toString());
    			}
            });
            this.$el.find('.bb-list-radio').each(function(index){
                $(this).find('input[value="'+$(this).data('radio')+'"]').attr('checked','checked');
            });
            this.$el.find('select').each(function(index){
                $(this).val($(this).data('value'));
            });
            this.$el.find('.bb-upload-image').each(function(index) {
                $(this).css({backgroundImage: 'url('+$(this).data('image')+')'});
            });
            this.$el.find('.bb-upload-image').on('click', function(event){
    			event.preventDefault();
    			var frame,
                    btn = $(this);
                
    			if ( frame ) {
    			  frame.open();
    			  return;
    			}
                if(btn.hasClass('uploaded')) {
                    var newSettings = $.extend( true, self.model.get('css'), {backgroundImage: ''} );
                    self.model.set( newSettings );
                    btn.removeClass('uploaded');
                    btn.css({backgroundImage: ''});
                    return;
                }
    			frame = wp.media({
    			  title: 'Choose background image',
    			  button: {
    				text: 'Use this image'
    			  },
    			  multiple: false
    			});
    			frame.on( 'select', function() {
    			  var attachment = frame.state().get('selection').first().toJSON(),
                  newSettings = $.extend( true, self.model.get('css'), {backgroundImage: attachment.url} );
                  self.model.set( newSettings );
                  btn.addClass('uploaded');
                  btn.css({backgroundImage: 'url('+attachment.url+')'});
    			});
    			frame.open();
    		});
            
            return this;
        },
        buildCSS: function(){
            var css = ".bb_custom_" + Date.now() + "{",
                self = this, isEmpty = true;
            
            if(this.model.get('selector') != '' && this.model.get('selector').indexOf('#class#') >= 0) {
                css = this.model.get('selector').replace(/(\#class\#)/ig, ".bb_custom_" + Date.now());
                css += "{";
            }    
                
            $.each( this.model.toJSON(), function( key, value ) {
                if(value != '') {
                    if(key == 'backgroundImage') {
                        value = 'url('+value+')';
                    }
                    if($.inArray(key, ['value', 'name', 'use', 'selector']) >= 0) {
                        return true;
                    }
                    css += self.toSnake(key) + ':' + value + '!important;';
                    isEmpty = false;
                }
            });
            css += "}";
            
            if(isEmpty) {
                css = '';
            }
            this.model.set({
                value: css
            });
            return css;
        },
        changed: function(event){
    		var target = event.target,
                name = target.type === 'radio' ? $(target).data('name') : target.name,
                value = target.value,
                propertiesHasNumberWithoutSuffix = ['fontWeight'];
            if(!isNaN(value) && value !== '' && $.inArray(name, propertiesHasNumberWithoutSuffix) == -1) {
                value = value + 'px';
            }
    		var newSettings = $.extend( true, this.model.get('css'), {[name]: value} );
            this.model.set( newSettings );
    	},
        changedColor: function(event, value){
            var target = event.target,
                name = target.name;
            var newSettings = $.extend( true, this.model.get('css'), {[name]: value} );
            this.model.set( newSettings );
        },
        toCamel: function(str){
            return str.replace(/(\-[a-z])/g, function($1){return $1.toUpperCase().replace('-','');});
        },
        toSnake: function(str){
            return str.replace(/([A-Z])/g, function($1){return "-"+$1.toLowerCase();});
        },
        toUnderscore: function(str){
            return str.replace(/([A-Z])/g, function($1){return "_"+$1.toLowerCase();});
        },
    });

    vc.atts.bb_responsive = {
        parse: function (param) {
            return $('.bb-responsive-field[data-name="' + param.param_name + '"]').data('view').buildCSS();
        },
        init: function (param, $field) {
            var self = $('.bb-responsive-field[data-name="' + param.param_name + '"]');
            self.data('view',
                new BBResponsiveView({
                    el: self,
                    model: new BBResponsiveModel({
                        name: self.data('name'),
                        value: self.data('value'),
                        use: self.data('use'),
						selector: self.data('selector'),
                    })
                })
            );
        }
    };
}(window.jQuery));