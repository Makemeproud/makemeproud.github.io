<div class="bb-field-row" data-dependency="<?php echo ($dependency!='')?'true':'false' ?>" data-element="<?php if($dependency!='') echo esc_attr($field['dependency']['element']) ?>" data-value="<?php  if($dependency!='') echo esc_attr(implode(',', $field['dependency']['value'])) ?>">
    <div class="bb-label">
        <label>
            <?php if(!empty($field['heading'])) esc_html_e($field['heading']) ?>
        </label>
    </div>
    <div class="bb-field bb-couples-container">
        <div class="bb-couples" data-name="<?php echo esc_attr($field['param_name']) ?>">     
            <?php $count = -1; ?>       
            <?php foreach ($field['std'] as $key => $std) { ?>
                <?php $count++; ?>
                <div class="bb-couple">
                    <span>
                        <button type="button" class="bb-minus-couple button danger">
                			<span class="dashicons dashicons-minus"></span>
                        </button>
                    </span>
                    <span><b><?php echo esc_html( $field['label'][0] ) ?></b></span>
                    <select class="bb-dropdown" name="<?php echo esc_attr($field['param_name']) ?>[<?php echo esc_attr($count) ?>][value]">
                        <?php foreach ($field['value'] as $value => $text) { ?>
                            <option value="<?php echo esc_attr($value) ?>" <?php if($value == $std['value']) echo 'selected'; ?>><?php echo esc_html($text) ?></option>
                        <?php } ?>
                    </select>
                    
                   <span class="label2"><b><?php echo esc_html( $field['label'][1] ) ?></b></span>
                   <select class="bb-dropdown" name="<?php echo esc_attr($field['param_name']) ?>[<?php echo esc_attr($count) ?>][value2]">
                       <?php foreach ($field['value2'] as $value => $text) { ?>
                           <option value="<?php echo esc_attr($value) ?>" <?php if($value == $std['value2']) echo 'selected'; ?>><?php echo esc_html($text) ?></option>
                       <?php } ?>
                   </select>
               </div>
            <?php } ?>
        </div>
        
        <button type="button" class="bb-add-couple button primary" data-count="<?php echo esc_attr($count++) ?>">
			<span class="dashicons dashicons-plus"></span>
        </button>
        <div class="bb-couple-clone">
            <div class="bb-couple">
                <span>
                    <button type="button" class="bb-minus-couple button danger">
                        <span class="dashicons dashicons-minus"></span>
                    </button>
                </span>
                <span><b><?php echo esc_html( $field['label'][0] ) ?></b></span>
                <select class="" bb_name_param="<?php echo esc_attr($field['param_name']) ?>[bb_insert_key][value]">
                    <?php foreach ($field['value'] as $value => $text) { ?>
                        <option value="<?php echo esc_attr($value) ?>"><?php echo esc_html($text) ?></option>
                    <?php } ?>
                </select>
                
               <span class="label2"><b><?php echo esc_html( $field['label'][1] ) ?></b></span>
               <select class="" bb_name_param="<?php echo esc_attr($field['param_name']) ?>[bb_insert_key][value2]">
                   <?php foreach ($field['value2'] as $value => $text) { ?>
                       <option value="<?php echo esc_attr($value) ?>"><?php echo esc_html($text) ?></option>
                   <?php } ?>
               </select>
           </div>
       </div>
    </div>
    <div class="bb-desc">
        <?php if(!empty($field['description'])) echo bb_esc_html($field['description']) ?>
    </div>
</div>