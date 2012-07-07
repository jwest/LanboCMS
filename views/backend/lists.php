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
        <?php foreach ( $rows as $row ): ?>
            <tr>
                <?php foreach ( $fields as $field => $mask ): ?>
                    <td>
                        <?php if ( $field == 'obj' ): ?>
                            <span class="label label-info"><?php echo $row->$field; ?></span>
                        <?php elseif ( $mask & Object::FIELD_CHECKBOX ): ?>
                            <?php if ((bool) $row->$field): ?>
                                <i class="icon-ok"></i>
                            <?php else: ?>
                                <i class="icon-remove"></i>
                            <?php endif; ?>
                        <?php elseif ( $mask & Object::FIELD_ONE_TO_MANY ): ?>
                            <a href="<?php echo Route::url('lanbocms/backend', array('object' => $field, 'action' => 'index', 'id' => Inflector::singular($object_name) . ':' . $row->id )) ?>">
                                <span class="label">show all <i class="icon-arrow-right"></i></span>
                            </a>
                        <?php elseif ( $mask & Object::FIELD_MANY_TO_ONE AND !empty($row->$field) ): ?>
                            <a href="<?php echo Route::url('lanbocms/backend', array('object' => Inflector::plural($field), 'action' => 'update', 'id' => $row->$field )) ?>">
                                <span class="label"><?php echo $row->$field; ?> <i class="icon-arrow-right"></i></span>
                            </a>
                        <?php else: ?>
                            <?php echo $row->$field; ?>
                        <?php endif; ?>
                    </td>
                <?php endforeach; ?>
                <td>                    
                    <a class="label label-info" href="<?php echo URL::site( 'admin/' . $object_name .'/update/' . $row->id ); ?>"><i class="icon-cog icon-white"></i> <?php echo __('Edit'); ?></a>
                    <?php if ( ! $only_update ): ?>
                        <a class="label label-important" href="javascript: (confirm('<?php echo __('Do you have delete object?'); ?>')) ? window.location='<?php echo URL::site( 'admin/' . $object_name .'/delete/' . $row->id ); ?>' : 0 "><i class="icon-trash icon-white"></i> <?php echo __('Delete'); ?></a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if ( ! $only_update ): ?>
    <a class="btn btn-primary" href="<?php echo URL::site( 'admin/' . $object_name .'/create/' ); ?>"><?php echo __('Create'); ?></a>
<?php endif; ?>