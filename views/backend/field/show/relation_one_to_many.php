<a href="<?php echo Route::url('lanbocms/backend', array('object' => $field_name, 'action' => 'index', 'id' => Inflector::singular($object_name) . ':' . $value )) ?>">
    <span class="label">show all <i class="icon-arrow-right"></i></span>
</a>