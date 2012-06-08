<h3><?php echo __( $object_name ); ?></h3>

<table class="table table-striped">
    <thead>
        <tr>
            <?php foreach ( $fields as $field => $mask ): ?>
                <th><?php echo __($field); ?></th>
            <?php endforeach; ?>
            <th> </th>
            <th> </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ( $rows as $id => $row ): ?>
            <tr>
                <?php foreach ( $fields as $field => $mask ): ?>
                    <td><?php echo $row[$field]; ?></td>
                <?php endforeach; ?>
                <td><a href="<?php echo URL::site( 'admin/' . $object_name .'/edit/' . $id ); ?>"><?php echo __('Edit'); ?></a></td>
                <td><a href=""><?php echo __('Delete'); ?></a></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<a class="btn btn-primary" href="<?php echo URL::site( 'admin/' . $object_name .'/create/' ); ?>"><?php echo __('Create'); ?></a>