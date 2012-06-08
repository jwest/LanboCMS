<label class="control-label" for="<?php echo $field_name; ?>">
    <?php echo __($field_name); ?>
</label>

<div class="controls">

    <?php if ( __('help-' . $field_name) !== 'help-' . $field_name ): ?>
        <?php echo __('help-' . $field_name); ?>
    <?php endif; ?>
    <input type="checkbox" id="<?php echo $field_name; ?>" name="<?php echo $field_name; ?>" value="1" <?php if ( $value === 1 ) echo 'checked="checked"'; ?>>

</div>