<!DOCTYPE html>
<html lang="en">
<head>
    <title><?php echo e($title); ?></title>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="">
    <!--===============================================================================================-->
    <link rel="shortcut icon" href="<?php echo e($favicon); ?>">
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo e($favicon); ?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo e($favicon); ?>">
    <!--===============================================================================================-->
    <link rel="stylesheet" href="<?php echo e(mix('css/vendor.min.css', 'auth')); ?> ">
    <link rel="stylesheet" href="<?php echo e(mix('css/custom.min.css', 'auth')); ?> ">
    <!--===============================================================================================-->
</head>
<body>

<div class="limiter">
    <div class="container-login100">
        <div class="wrap-login100">
            <form class="login100-form validate-form" action="<?php echo e(route('auth.authenticate')); ?>" id="login-form">
					<span class="login100-form-title p-b-48">
						<img class="LogoPrincipal" src="<?php echo e($logo->img_dark); ?>" alt="<?php echo e($whitelabel_description); ?>" width="350">
					</span>

                <div class="wrap-input100 validate-input">
                    <input class="input100" type="text" name="username" id="username" autocomplete="off" required>
                    <span class="focus-input100" data-placeholder="<?php echo e(_i('Username')); ?>"></span>
                </div>

                <div class="wrap-input100 validate-input" data-validate="<?php echo e(_i('Enter password')); ?>">
						<span class="btn-show-pass">
							<i class="fa fa-eye"></i>
						</span>
                    <input class="input100" type="password" name="password" id="password" autocomplete="off" required>
                    <span class="focus-input100" data-placeholder="<?php echo e(_i('Password')); ?>"></span>
                </div>

                <div class="container-login100-form-btn">
                    <div class="wrap-login100-form-btn">
                        <div class="login100-form-bgbtn"></div>
                        <button class="login100-form-btn" id="login" type="button"
                                data-loading-text="<?php echo e(_i('Please wait...')); ?>">
                            <?php echo e(_i('Login')); ?>

                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<footer>

    <div class="dropdown text-center p-b-20">
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
        <div id="dropDownSelect1"></div>

    </div>
    <p class="text-center copyright-text">
        <?php echo e($whitelabel_info->copyright ? _i('Developed by Betsweet. Operated by') : ''); ?> <?php echo e($whitelabel_description); ?>

        © <?php echo e(_i('Copyright')); ?> - <?php echo e(date('Y')); ?>. <?php echo e(_i('All rights reserved')); ?>

    </p>


</footer>
<?php echo $__env->make('auth.modals.change-password', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

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
