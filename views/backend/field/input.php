<label class="control-label" for="<?php echo $field_name; ?>">
    <?php echo __($field_name); ?>
</label>

<div class="controls">

    <input type="text" class="input-xlarge" id="<?php echo $field_name; ?>" name="<?php echo $field_name; ?>" value="<?php echo $value; ?>">

    <?php if ( __('help-' . $field_name) !== 'help-' . $field_name ): ?>
        <p class="help-block"><?php echo __('help-' . $field_name); ?></p>
    <?php endif; ?>

</div>