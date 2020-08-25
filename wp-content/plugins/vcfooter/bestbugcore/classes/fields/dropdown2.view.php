
<div class="bb-field-row" data-dependency="<?php echo ($dependency!='')?'true':'false' ?>" data-element="<?php if($dependency!='') echo esc_attr($field['dependency']['element']) ?>" data-value="<?php  if($dependency!='') echo esc_attr(implode(',', $field['dependency']['value'])) ?>">
    <div class="bb-label">
        <label for="<?php echo esc_attr($field['param_name']) ?>">
            <?php if(!empty($field['heading'])) esc_html_e($field['heading']) ?>
        </label>
    </div>
    <div class="bb-field">
        <select id="<?php echo esc_attr($field['param_name']) ?>" class="bb-dropdown" name="<?php echo esc_attr($field['param_name']) ?>" <?php echo (isset($field['multiple']) && $field['multiple'] == 'multiple')?'multiple="multiple"':'' ?>>
            <?php foreach ($field['value'] as $value => $text) { ?>
                <optgroup label="<?php echo esc_attr($value)?>">
                <?php
                    foreach ($text as $key2 => $value2) {
                        ?>
                        <option value="<?php echo esc_attr($key2) ?>" <?php if(is_array($field['std'])): if(in_array($key2,$field['std'])): echo 'selected'; endif;  else: if($key2 == $field['std']): echo 'selected'; endif; endif; ?>><?php echo esc_html($value2) ?></option>
                        <?php
                    }
                ?>
                </optgroup>
            <?php } ?>
        </select>
    </div>
    <div class="bb-desc">
        <?php if(!empty($field['description'])) echo bb_esc_html($field['description']) ?>
    </div>
</div>

