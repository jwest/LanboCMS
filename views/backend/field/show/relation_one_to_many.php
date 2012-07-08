<a href="<?php echo Route::url('lanbocms/backend', array('object' => $field_name, 'action' => 'index', 'id' => Inflector::singular($object_name) . ':' . $id )) ?>">
    <span class="label"><?php echo $count; ?> <i class="icon-arrow-right"></i></span>
</a>