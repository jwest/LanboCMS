<?php if( !empty($error) ): ?>
<div class="alert alert-error">
    <h4 class="alert-heading"><?php echo __('Validation error'); ?></h4>
    <?php foreach ( $error as $message ): ?>
        <?php echo __($message); ?><br>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="row">
    <div class="span12">
        <form class="form-horizontal" method="post" enctype="multipart/form-data">
            <fieldset>
                
                <?php if ( ! array_key_exists('obj', $fields_inputs) ) : ?>
                    <div class="control-group">
                        <label class="control-label" for="obj"><?php echo __('obj'); ?></label>

                        <div class="controls">
                            <input disabled="disabled" type="text" class="input-xlarge" id="obj" name="obj" value="<?php echo $id; ?>">
                            <?php if ( __('help-obj') !== 'help-obj' ): ?>
                                <p class="help-block"><?php echo __('help-obj'); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php foreach ( $fields_inputs as $field => $input ): ?>
                    <div class="control-group <?php echo (isset($error[$field])) ? 'error' : ''; ?>">
                        <?php echo $input; ?>
                    </div>
                <?php endforeach; ?>

                <div class="form-actions">
                    <?php if ( $id === NULL ): ?>
                        <button type="submit" class="btn btn-primary"><?php echo __('Create'); ?></button>
                    <?php else: ?>
                        <button type="submit" class="btn btn-primary"><?php echo __('Update'); ?></button>
                        <?php if ( ! $only_update ) : ?>
                            <a class="btn btn-danger" href="javascript: (confirm('<?php echo __('Do you have delete object?'); ?>')) ? window.location='<?php echo URL::site( 'admin/' . $object_name .'/delete/' . $id ); ?>' : 0 "><?php echo __('Delete'); ?></a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <a class="btn" href="<?php echo URL::site( 'admin/' . $object_name ); ?>"><?php echo __('Cancel'); ?></a>
                </div>

            </fieldset>
        </form>
    </div>
</div>