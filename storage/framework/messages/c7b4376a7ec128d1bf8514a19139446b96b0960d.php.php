<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo e($title); ?></title>
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="">
    <link rel="shortcut icon" href="<?php echo e($favicon); ?>">
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo e($favicon); ?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo e($favicon); ?>">
    <link rel="stylesheet" href="<?php echo e(mix('css/vendor.min.css', 'auth')); ?> ">
    <link rel="stylesheet" href="<?php echo e(mix('css/custom.min.css', 'auth')); ?> ">
</head>
<body class="double-diagonal dark auth-page">
<div class="preloader" id="preloader">
    <div class="logopreloader">
        <img src="<?php echo e($logo->img_dark); ?>" alt="<?php echo e($whitelabel_description); ?>" width="350">
    </div>
    <div class="loader" id="loader"></div>
</div>
<div class="wrapper">
    <div class="languages-menu" id="languages-menu">
        <a class="languages-menu-selected">
            <img class="lang-flag" src="<?php echo e($selected_language['flag']); ?>"
                 alt="<?php echo e($selected_language['name']); ?>">
            <span class="title-lang">
            <?php echo e($selected_language['name']); ?>

            <i class="fa fa-caret-down" aria-hidden="true"></i>
        </span>
        </a>
        <ul class="languages-submenu">
            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li>
                    <a href="<?php echo e(route('core.change-language', [$language['iso']])); ?>"
                       class="change-language" data-locale="<?php echo e($language['iso']); ?>">
                        <img class="lang-flag" src="<?php echo e($language['flag']); ?>" alt="<?php echo e($language['name']); ?>">
                        <span><?php echo e($language['name']); ?></span>
                    </a>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <div class="container-fluid user-auth">
        <div class="row">
            <div class="d-none d-lg-block col-lg-4">
            </div>
            <div class="col-xs-12 col-lg-4">
                <div class="form-container">
                    <div>
                        <div class="text-center d-md-block my-5">
                            <img src="<?php echo e($logo->img_dark); ?>" alt="<?php echo e($whitelabel_description); ?>" width="350">
                        </div>
                        <form class="custom-form" action="<?php echo e(route('auth.authenticate')); ?>" id="login-form">
                            <div class="form-group">
                                <input class="form-control" type="text" placeholder="<?php echo e(_i('Username')); ?>"
                                       name="username" id="username" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="password" placeholder="<?php echo e(_i('Password')); ?>"
                                       name="password" id="password" autocomplete="off" required>
                            </div>
                            <div class="form-group">
                                <button class="custom-button login mb-5" id="login" type="button"
                                        data-loading-text="<?php echo e(_i('Please wait...')); ?>">
                                    <?php echo e(_i('Login')); ?>

                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <p class="text-center copyright-text">
                    <?php echo e($whitelabel_info->copyright ? _i('Developed by Dotworkers. Operated by') : ''); ?> <?php echo e($whitelabel_description); ?>

                    © <?php echo e(_i('Copyright')); ?> - <?php echo e(date('Y')); ?>. <?php echo e(_i('All rights reserved')); ?>

                </p>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo e(mix('js/manifest.js', 'auth')); ?>"></script>
<script src="<?php echo e(mix('js/vendor.js', 'auth')); ?>"></script>
<script src="<?php echo e(mix('js/custom.min.js', 'auth')); ?>"></script>
<script>
    $(function () {
        Auth.login();
    });
</script>
</body>
</html>
