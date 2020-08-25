<tr > 
   <td colspan="10">
      <ul class="bb-meta_box-field-tab-ul">
         <?php
         foreach ( $option['options'] as $key => $value) {
         ?>
            <li class="bb-field-tab-li">
               <input  id="<?php echo esc_attr($option['param_name'].'_'.$key) ?>" type="radio" class="bb-tab-parent" name="<?php echo esc_attr($option['param_name']) ?>"  value="<?php echo esc_attr($key);?>" <?php if($key==$option['value']): echo esc_html('checked'); endif;?>>
               <div class="bb-field-tab-title">
                  <label for="<?php echo esc_attr($option['param_name'].'_'.$key) ?>">
                     <?php echo esc_html($value);?>
                  </label>
               </div>
            
            </li>
         <?php
         }
         ?>
      </ul>
   </td>
</tr>
    
