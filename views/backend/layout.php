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

        <?php echo Html::script( $media_path . '/js/jquery.min.js' ); ?>
        <?php echo Html::script( $media_path . '/js/bootstrap.min.js' ); ?>

        <!--Wysiwyg-->
        <?php if ( $wysiwyg == 'redactor' ):?>
            <?php echo Html::style( $media_path . '/wysiwyg/redactor/css/redactor.css' ); ?>
            <?php echo Html::script( $media_path . '/wysiwyg/redactor/redactor.js' ); ?>
        <?php elseif ( $wysiwyg == 'bootstrap' ): ?>
            <?php echo Html::style( $media_path . '/wysiwyg/bootstrap/prettify.css' ); ?>
            <?php echo Html::style( $media_path . '/wysiwyg/bootstrap/bootstrap-wysihtml5.css' ); ?>
            <?php echo Html::script( $media_path . '/wysiwyg/bootstrap/wysihtml5-0.3.0_rc3.js' ); ?>
            <?php echo Html::script( $media_path . '/wysiwyg/bootstrap/bootstrap-wysihtml5.js' ); ?>
            <?php echo Html::script( $media_path . '/wysiwyg/bootstrap/prettify.js' ); ?>
        <?php endif; ?>
        <!--Wysiwyg END-->

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
                            <li><a href="<?php echo URL::site( 'admin/signout' ); ?>"><?php echo __('logout'); ?></a></li>
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

    </body>
</html>