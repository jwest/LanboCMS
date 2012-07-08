<label class="control-label" for="<?php echo $field_name; ?>">
    <?php echo __($field_name); ?>
</label>

<div class="controls">

    <textarea class="input-xlarge" id="<?php echo $field_name; ?>" name="<?php echo $field_name; ?>" rows="3"><?php echo $value; ?></textarea>

    <?php if ( __('help-' . $object_name . '-' . $field_name) !== 'help-' . $object_name . '-' . $field_name ): ?>
        <p class="help-block"><?php echo __('help-' . $object_name . '-' . $field_name); ?></p>
    <?php endif; ?>

</div>