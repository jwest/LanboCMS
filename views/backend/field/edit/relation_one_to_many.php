<?php if( !empty($view_values['fields']) ): ?>

<label class="control-label" for="<?php echo $field_name; ?>">
    <?php echo __($field_name); ?>
</label>

<div class="controls">
    
    <table class="table table-striped">
        <thead>            
            <tr>
                <?php foreach ( $view_values['fields'] as $field => $mask ): ?>
                    <?php if ( $field == Inflector::singular($object_name) ): ?>
                        <?php continue; ?>
                    <?php else: ?>
                        <th><?php echo __($field); ?></th>
                    <?php endif; ?>
                <?php endforeach; ?>
                <th> </th>
            </tr>            
        </thead>
        <tbody>
            <?php foreach ( $view_values['rows'] as $id => $row ): ?>
                <tr>
                    <?php foreach ( $view_values['fields'] as $field => $mask ): ?>
                        <?php if ( $field == Inflector::singular($object_name) ): ?>
                            <?php continue; ?>
                        <?php else: ?>
                            <td>
                                <?php if ( $field == 'obj' ): ?>
                                    <span class="label label-info"><?php echo $row->$field; ?></span>
                                <?php elseif ( $mask & Object::FIELD_MANY_TO_ONE AND !empty($row->$field) ): ?>
                                    <a href="<?php echo Route::url('lanbocms/backend', array('object' => Inflector::plural($field), 'action' => 'update', 'id' => $row->$field )) ?>">
                                        <span class="label"><?php echo $row->$field; ?> <i class="icon-arrow-right"></i></span>
                                    </a>
                                <?php else: ?>
                                    <?php echo $row->$field; ?>
                                <?php endif; ?>
                            </td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    <td>                    
                        <a class="label label-info" href="<?php echo URL::site( 'admin/' . Inflector::singular($field_name) .'/update/' . $row->id ); ?>"><i class="icon-cog icon-white"></i> <?php echo __('Edit'); ?></a>
                        <a class="label label-important" href="javascript: (confirm('<?php echo __('Do you have delete object?'); ?>')) ? window.location='<?php echo URL::site( 'admin/' . Inflector::singular($field_name) .'/delete/' . $row->id ); ?>' : 0 "><i class="icon-trash icon-white"></i> <?php echo __('Delete'); ?></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <a class="btn btn-primary" href="<?php echo URL::site( 'admin/' . Inflector::singular($field_name) .'/create/' ); ?>"><?php echo __('Create ' . Inflector::singular($field_name)); ?></a>
    
</div>

<?php endif; ?>