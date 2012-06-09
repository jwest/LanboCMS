<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>LanboCMS - <?php echo __('admin panel'); ?> - <?php echo _($object_name); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="description" content="<?php echo __('admin panel'); ?> - <?php echo _($object_name); ?>">
        <meta name="author" content="Jakub Westfalewski <jwest@jwest.pl>">

        <?php echo Html::style( $media_path . '/css/bootstrap.css' ); ?>
        <?php echo Html::style( $media_path . '/css/bootstrap-responsive.css' ); ?>
        <style type="text/css">
            body { padding-top: 60px; padding-bottom: 40px; }
        </style>

        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>
    <body>

        <div class="navbar navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="#">LanboCMS</a>
                    <div class="nav-collapse">
                        <ul class="nav">
                            <?php foreach ( $menu as $object => $value ): ?>
                                <?php if ( $object_name == $object ): ?>
                                    <li class="active"><a href="<?php echo URL::site( 'admin/'.$object ); ?>"><?php echo __($object); ?></a></li>
                                <?php else: ?>
                                    <li><a href="<?php echo URL::site( 'admin/'.$object ); ?>"><?php echo __($object); ?></a></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <li><a href="<?php echo URL::site( 'admin/logout' ); ?>"><?php echo __('logout'); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">

            <?php echo $content; ?>

            <hr>

            <footer>
                <p>&copy; LanboCMS 2012</p>
            </footer>

        </div>

        <?php echo Html::script( $media_path . '/js/jquery.min.js' ); ?>
        <?php echo Html::script( $media_path . '/js/bootstrap.min.js' ); ?>

    </body>
</html>