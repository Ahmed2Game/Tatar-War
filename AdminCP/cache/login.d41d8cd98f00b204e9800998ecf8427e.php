<?php if(!class_exists('raintpl')){exit;}?><!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>لوحة تحكم حرب التتار - تسجيل الدخول</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <!--base css styles-->
        <link rel="stylesheet" href="<?php echo add_style('bootstrap.min.css', ASSETS_DIR.'/bootstrap/'); ?>">
        <link rel="stylesheet" href="<?php echo add_style('bootstrap-responsive.min.css', ASSETS_DIR.'/bootstrap/'); ?>" >
        <link rel="stylesheet" href="<?php echo add_style('font-awesome.min.css', ASSETS_DIR.'/font-awesome/css/'); ?>" >
        <link rel="stylesheet" href="<?php echo add_style('normalize.css', ASSETS_DIR.'/normalize/'); ?>" >

        <!--page specific css styles-->

        <!--flaty css styles-->
        <link rel="stylesheet" href="<?php echo add_style('flaty.css', ASSETS_DIR.'/css/'); ?>">
        <link rel="stylesheet" href="<?php echo add_style('flaty-responsive.css', ASSETS_DIR.'/css/'); ?>">
        <script src="<?php echo add_style('modernizr-2.6.2.min.js', ASSETS_DIR.'/modernizr/'); ?>"></script>

    </head>
    <body class="login-page">
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <!-- BEGIN Main Content -->
        <div class="login-wrapper">

            <!-- BEGIN Login Form -->
            <form id="form-login" action="" method="POST">
                <?php if( isset($flash_message) ){ ?>

                    <div class="alert alert-<?php echo $flash_message["0"];?>">
                        <strong><?php echo $flash_message["1"];?></strong>
                    </div>
                <?php } ?>

                <h3>تسجيل الدخول</h3>
                <hr/>
                <div class="control-group">
                    <div class="controls">
                        <input type="text" placeholder="البريد" name="email" class="input-block-level" />
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <input type="password" placeholder="كلمة المرور" name="password" class="input-block-level" />
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <button type="submit" class="btn btn-primary input-block-level">دخول</button>
                    </div>
                </div>
            </form>
            <!-- END Login Form -->
        </div>
        <!-- END Main Content -->

        <!--basic scripts-->
        <!--<script src="http://localhost/~ahmed/Tatar-War/AdminCP/app/views///ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>-->
        <script>window.jQuery || document.write('<script src="<?php echo add_style('jquery-1.10.1.min.js', ASSETS_DIR.'/jquery/'); ?>"><\/script>')</script>
        <script src="<?php echo add_style('bootstrap.min.js', ASSETS_DIR.'/bootstrap/'); ?>"></script>
    </body>
</html>
