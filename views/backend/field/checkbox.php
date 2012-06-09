<label class="control-label" for="<?php echo $field_name; ?>">
    <?php echo __($field_name); ?>
</label>

<div class="controls">

    <?php if ( __('help-' . $object_name . '-' . $field_name) !== 'help-' . $object_name . '-' . $field_name ): ?>
        <?php echo __('help-' . $object_name . '-' . $field_name); ?>
    <?php endif; ?>    
    <input type="checkbox" id="<?php echo $field_name; ?>" name="<?php echo $field_name; ?>" value="1" <?php if ( $value == 1 ) echo 'checked="checked"'; ?>>

</div>