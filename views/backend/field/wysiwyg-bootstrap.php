<label class="control-label" for="<?php echo $field_name; ?>">
    <?php echo __($field_name); ?>
</label>

<div class="controls">

    <textarea class="input-xlarge" id="<?php echo $field_name; ?>" name="<?php echo $field_name; ?>" rows="15" style="width:100%"><?php echo $value; ?></textarea>

    <?php if ( __('help-' . $field_name) !== 'help-' . $field_name ): ?>
        <p class="help-block"><?php echo __('help-' . $field_name); ?></p>
    <?php endif; ?>

    <script type="text/javascript">
        $('#<?php echo $field_name; ?>').wysihtml5();
    </script>

</div>