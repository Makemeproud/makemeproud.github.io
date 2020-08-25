<tr <?php if( isset($option['tab']) && !empty($option['tab'])): ?> tab="tab" tab-element="<?php esc_html_e($option['tab']['element']);?>" tab-value="<?php esc_html_e( implode(',',$option['tab']['value']));?>" <?php endif;?>> 
    <th scope="row">
        <label for="<?php echo esc_html( $option['param_name']); ?>"><?php echo esc_html( $option['heading']); ?></label>
    </th>
    <td>
        <?php        
        if (isset($option['options'])&& !empty($option['options'])) {
            foreach ($option['options'] as $optionKey => $optionVal) {
            ?>
                <label>
                    <input type="radio" name="<?php echo esc_html( $option['param_name']); ?>" value="<?php echo esc_html( $optionKey); ?>" <?php if($option['value']==$optionKey): echo esc_html('checked'); endif;?> >
                    <?php echo esc_html( $optionVal); ?>
                </label>
                <?php if(!$option['horizontal']): echo("<br/>"); endif;?>
            <?php
            }
        }
        ?>
         <p class="description"><?php echo ( $option['description']); ?></p>
    </td>
</tr>
    
