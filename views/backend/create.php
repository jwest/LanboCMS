<h3><?php echo __( $object_name ); ?></h3>

<div class="row">
    <div class="span12">
        <form class="form-horizontal">
            <fieldset>
                <?php foreach ( $fields_inputs as $input ): ?>
                    <div class="control-group">
                        <?php echo $input; ?>
                    </div>
                <?php endforeach; ?>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><?php echo __('Create'); ?></button>
                    <a class="btn" href="<?php echo URL::site( 'admin/' . $object_name ); ?>"><?php echo __('Cancel'); ?></a>
                </div>
            </fieldset>
        </form>
    </div>
</div>