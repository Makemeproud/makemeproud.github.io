<div class="bb-field-row <?php if(empty($field['value'])) echo 'bb-hidden'; ?>" data-dependency="<?php echo ($dependency!='')?'true':'false' ?>" data-element="<?php if($dependency!='') echo esc_attr($field['dependency']['element']) ?>" data-value="<?php  if($dependency!='') echo esc_attr(implode(',', $field['dependency']['value'])) ?>" >
    <div class="bb-label">
        <label for="<?php echo esc_attr($field['param_name']) ?>">
            <?php if(!empty($field['heading'])) esc_html_e($field['heading']) ?>
        </label>
    </div>
    <div class="bb-field">
        <div id="<?php echo esc_attr($field['param_name']) ?>" class="bb-text">
            <?php
            if(is_array($field['value'])) {
                var_dump( $field['value'] );
            } else {
                echo bb_esc_html($field['value']);
            }
            ?>
        </div>
    </div>
    <div class="bb-desc">
        <?php if(!empty($field['description'])) echo bb_esc_html($field['description']) ?>
    </div>
</div>