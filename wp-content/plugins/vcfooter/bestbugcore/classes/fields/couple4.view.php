<div class="bb-field-row" data-dependency="<?php echo ($dependency!='')?'true':'false' ?>" data-element="<?php if($dependency!='') echo esc_attr($field['dependency']['element']) ?>" data-value="<?php  if($dependency!='') echo esc_attr(implode(',', $field['dependency']['value'])) ?>">
    <div class="bb-label">
        <label for="<?php echo esc_attr($field['param_name']) ?>">
            <?php if(!empty($field['heading'])) esc_html_e($field['heading']) ?>
        </label>
    </div>
    <div class="bb-field bb-couples-container bb-couples3">
        <div class="bb-couples " data-name="<?php echo esc_attr($field['param_name']) ?>">     
            <?php $count = -1; 
                $fieldNew = $field['fields'];
                $fieldPram = $field['param_name'];
                $fieldDis = $field['description'];
            ?>
           
            <?php if(isset($field['std']) && !empty($field['std'])): foreach ($field['std'] as $key => $std) {;?>
                <?php $count++; ?>
                <div class="bb-couple bb-couple4">
                    <span class="bb-couple4-remove">
                        <button type="button" class="bb-minus-couple button danger">
                            <span class="dashicons dashicons-minus"></span>
                        </button>
                    </span>
                    <input type="hidden" value="<?php echo esc_attr($count) ?>" name="<?php echo esc_attr($fieldPram."[".$count."]") ?>">
                    <?php 
                    
                    foreach ( $fieldNew as $value => $field) {
                        if(isset($_REQUEST['ID']) && !empty($_REQUEST['ID'])) {
                            $this->post_id = $_REQUEST['ID'];
                            $value_exists = (get_post_meta($this->post_id, $field['param_name'].'_'.$count, true));
                            if($value_exists != null) {
                                if(!isset($field['value'])||is_array($field['value'])) {
                                    $field['std'] = $value_exists;
                                } else {
                                    $field['value'] = $value_exists;
                                }
                            }
                        }

                        $field["param_name"] = $field["param_name"] .'_'.$count;
                        $dependency = '';
                        if(isset($field['dependency'])) {
                            $dependency = 'data-dependency="true" data-element="'.$field['dependency']['element'].'_'.$count.'" data-value="'.implode(',', $field['dependency']['value']).'"';
                            $field["dependency"]['element'] =  $field["dependency"]['element'] .'_'.$count;
                        }
                        include  $field['type'] . ".view.php";
                    } 
                    ?>
                </div>
               
            <?php } endif;?>    
        </div>
        <button type="button" class="bb-add-couple button primary" data-count="<?php echo esc_attr($count++) ?>">
			<span class="dashicons dashicons-plus"></span>
        </button>
        <div class="bb-couple-clone">
            <div class="bb-couple bb-couple4">
                <span class="bb-couple4-remove">
                    <button type="button" class="bb-minus-couple button danger">
                        <span class="dashicons dashicons-minus"></span>
                    </button>
                </span>
                <input type="hidden" data-check="as" value="bb_insert_key" bb_name_param="<?php echo esc_attr($fieldPram."[bb_insert_key]") ?>">
                <?php 
                foreach ($fieldNew as $value => $field) {
                    $field["param_name"] = $field["param_name"] .'_bb_insert_key';
                    $dependency = '';
				    if(isset($field['dependency'])) {
				        $dependency = 'data-dependency="true" data-element="'.$field['dependency']['element'].'_bb_insert_key'.'" data-value="'.implode(',', $field['dependency']['value']).'"';
                        $field["dependency"]['element'] =  $field["dependency"]['element'] .'_bb_insert_key';
                    }
                    include  $field['type'] . ".view.php";
                } 
                ?>
           </div>
       </div>
    </div>
    <div class="bb-desc">
        <?php if(!empty($fieldDis)) echo bb_esc_html($fieldDis) ?>
    </div>
</div>



