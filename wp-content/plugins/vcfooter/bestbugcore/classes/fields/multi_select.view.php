<div class="bb-field-row bb-multi-select-container" data-dependency="<?php echo ($dependency!='')?'true':'false' ?>" data-element="<?php if($dependency!='') echo esc_attr($field['dependency']['element']) ?>" data-value="<?php  if($dependency!='') echo esc_attr(implode(',', $field['dependency']['value'])) ?>">
    <div class="bb-label">
        <label for="<?php echo esc_attr($field['param_name']) ?>">
            <?php if(!empty($field['heading'])) esc_html_e($field['heading']) ?>
        </label>
    </div>
    <div class="bb-field">
        <select id="<?php echo esc_attr($field['param_name']) ?>" class="bb-multi-select" multiple="multiple"  name="<?php echo esc_attr($field['param_name']) ?>[]">
            <?php foreach ($field['value'] as $value => $text) { ?>
                <?php if(is_array($text)): ?>
                    <option value="<?php echo esc_attr($text[0]) ?>" <?php if(in_array($text[0], $field['std'])) echo 'selected'; ?>><?php echo esc_html($text[1]) ?></option>
                <?php else: ?>
                    <option value="<?php echo esc_attr($value) ?>" <?php if(in_array($value, $field['std'])) echo 'selected'; ?>><?php echo esc_html($text) ?></option>
                <?php endif; ?>
            <?php } ?>
        </select>
    </div>
    <div class="bb-desc">
        <?php if(!empty($field['description'])) echo bb_esc_html($field['description']) ?>
    </div>
</div>