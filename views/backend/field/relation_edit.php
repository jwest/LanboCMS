<label class="control-label" for="<?php echo $field_name; ?>">
    <?php echo __($field_name); ?>
</label>

<div class="controls">
    
    <select class="input-xlarge" id="<?php echo $field_name; ?>" name="<?php echo $field_name; ?>">
        value="<?php echo $value; ?>"
        <?php foreach($view_values['options'] as $key => $obj): ?>
            <option value="<?php echo $key; ?>" <?php if ($value == $key): ?> selected="<?php echo $key ?>"<?php endif; ?>><?php echo $obj; ?></option>
        <?php endforeach; ?>
    </select>

    <?php if ( __('help-' . $object_name . '-' . $field_name) !== 'help-' . $object_name . '-' . $field_name ): ?>
        <p class="help-block"><?php echo __('help-' . $object_name . '-' . $field_name); ?></p>
    <?php endif; ?>

</div>