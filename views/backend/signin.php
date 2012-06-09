<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>LanboCMS - <?php echo __('admin panel'); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="description" content="<?php echo __('admin panel'); ?>">
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
                            <li><a href="<?php echo URL::site( '/' ); ?>"><?php echo __('go to site'); ?></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">

            <form method="post">

                <?php if($error): ?>
                    <div class="alert alert-block alert-error fade in">
                        <strong><?php echo __('Wrong data!') ?></strong> <?php echo __('You must signin with valid username and password!') ?>
                    </div>
                <?php endif; ?>

                <fieldset>
                    <legend><?php echo __('Signin to admin panel')?></legend>
                    
                    <div class="control-group">
                        <label class="control-label" for="input01"><?php echo __('Username')?></label>
                        <div class="controls">
                            <input type="text" class="input-xlarge" name="username">
                        </div>
                    </div>
                    
                    <div class="control-group">
                        <label class="control-label" for="input01"><?php echo __('Password')?></label>
                        <div class="controls">
                            <input type="password" class="input-xlarge" name="password">
                        </div>
                    </div>
          
                    <div class="form-actions">
                        <input type="submit" class="btn btn-primary" value="<?php echo __('Signin')?>">
                        <a href="<?php echo URL::base() ?>" class="btn"><?php echo __('Cancel')?></A>
                    </div>
                </fieldset>
            
            </form>

            <hr>

            <footer>
                <p>&copy; LanboCMS 2012</p>
            </footer>

        </div>

    </body>
</html>