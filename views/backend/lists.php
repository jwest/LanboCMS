<table class="table table-striped">
    <thead>
        <tr>
            <?php foreach ( $fields as $field => $mask ): ?>
                <th><?php echo __($field); ?></th>
            <?php endforeach; ?>
            <th> </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ( $rows as $id => $row ): ?>
            <tr>
                <?php foreach ( $fields as $field => $mask ): ?>
                    <td>
                        <?php if ( $field == 'obj' ): ?>
                            <span class="label label-info"><?php echo $row->$field; ?></span>
                        <?php else: ?>
                            <?php echo $row->$field; ?>
                        <?php endif; ?>
                    </td>
                <?php endforeach; ?>
                <td>                    
                    <a class="label label-info" href="<?php echo URL::site( 'admin/' . $object_name .'/update/' . $id ); ?>"><i class="icon-cog icon-white"></i> <?php echo __('Edit'); ?></a>
                    <?php if ( ! $only_update ): ?>
                        <a class="label label-important" href="javascript: (confirm('<?php echo __('Do you have delete object?'); ?>')) ? window.location='<?php echo URL::site( 'admin/' . $object_name .'/delete/' . $id ); ?>' : 0 "><i class="icon-trash icon-white"></i> <?php echo __('Delete'); ?></a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if ( ! $only_update ): ?>
    <a class="btn btn-primary" href="<?php echo URL::site( 'admin/' . $object_name .'/create/' ); ?>"><?php echo __('Create'); ?></a>
<?php endif; ?>