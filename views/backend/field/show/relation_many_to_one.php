<?php if ( $value === NULL ):?>
	-
<?php else: ?>
	<a href="<?php echo Route::url('lanbocms/backend', array('object' => Inflector::plural($field_name), 'action' => 'update', 'id' => $value )) ?>">
	    <span class="label"><?php echo $value_name; ?> <i class="icon-white icon-arrow-right"></i></span>
	</a>
<?php endif; ?>