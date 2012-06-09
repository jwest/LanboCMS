<h3><?php echo __( $object_name ); ?></h3>

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
        <form class="form-horizontal" method="post">
            <fieldset>
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
                        <a class="btn btn-danger" href="javascript: (confirm('<?php echo __('Do you have delete object?'); ?>')) ? window.location='<?php echo URL::site( 'admin/' . $object_name .'/delete/' . $id ); ?>' : 0 "><?php echo __('Delete'); ?></a>
                    <?php endif; ?>
                    <a class="btn" href="<?php echo URL::site( 'admin/' . $object_name ); ?>"><?php echo __('Cancel'); ?></a>
                </div>
            </fieldset>
        </form>
    </div>
</div>