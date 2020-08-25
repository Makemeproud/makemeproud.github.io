<div class="bb-field-row" data-dependency="<?php echo ($dependency!='')?'true':'false' ?>" data-element="<?php if($dependency!='') echo esc_attr($field['dependency']['element']) ?>" data-value="<?php  if($dependency!='') echo esc_attr(implode(',', $field['dependency']['value'])) ?>">
    <div class="bb-label">
        <label for="<?php echo esc_attr($field['param_name']) ?>">
            <?php if(!empty($field['heading'])) esc_html_e($field['heading']) ?>
        </label>
    </div>
    <div class="bb-field bb-attach">
			<div class="bb-upload-image <?php if(isset($field['value']) && $field['value']) echo 'uploaded'; ?>" <?php echo BestBug_Helper::get_background_image($field['value']) ?>>
			    <span class="bb-btn-clear"><span class="dashicons dashicons-no"></span></span>
                <span class="bb-btn-add"><span class="dashicons dashicons-plus"></span></span>
                <input id="<?php echo esc_attr($field['param_name']) ?>" name="<?php echo esc_attr($field['param_name']) ?>" type="hidden" value="<?php echo esc_attr($field['value']) ?>" />	
			</div>
    </div>
    <div class="bb-desc">
        <?php if(!empty($field['description'])) echo bb_esc_html($field['description']) ?>
    </div>
</div>