

<?php $__env->startSection('content'); ?>
    <div class="container-login">
        <div class="wrap-login">
            <div class="login-preview-bg" style="background-image: url('https://bestcasinos-llc.s3.us-east-2.amazonaws.com/templates/bg-login-v2.jpg')">
                <div class="bg-opacity"></div>
                <img class="login-logo" src="<?php echo e($logo->img_dark); ?>" alt="<?php echo e($whitelabel_description); ?>" width="350">
            </div>

            <form class="login-form validate-form" action="<?php echo e(route('auth.authenticate')); ?>" id="login-form">
                <div class="loader-login"></div>
                <div class="login-form-ex">
                    <div class="login-nav">
                        <button type="button" class="btn btn-tab-login" data-tag="show-input-email"><?php echo e(_i('By email')); ?></button>
                        <button type="button" class="btn btn-tab-login" data-tag="show-input-user"><?php echo e(_i('By user')); ?></button>
                    </div>
                    <div class="wrap-input-title"><?php echo e(_i('Welcome')); ?></div>
                    <div class="wrap-input-subtitle">
                        <?php echo e(_i("Today is a new day. It's your day. You shape it.")); ?><br>
                        <?php echo e(_i('Sign in to start managing your project.')); ?>

                    </div>
                    <div class="login-form-line">
                        <div class="login-tag show-tag show-input-email">
                            <label><?php echo e(_i('E-mail')); ?></label>
                            <div class="wrap-input-login validate-input">
                                <input class="input-login" type="text" name="email" id="email" autocomplete="off" placeholder="<?php echo e(_i('example@email.com')); ?>" required>
                            </div>
                        </div>

                        <div class="login-tag show-input-user">
                            <label><?php echo e(_i('Username')); ?></label>
                            <div class="wrap-input-login validate-input">
                                <input class="input-login" type="text" name="username" id="username" autocomplete="off" placeholder="<?php echo e(_i('Enter name')); ?>" required>
                            </div>
                        </div>

                        <label><?php echo e(_i('Password')); ?></label>
                        <div class="wrap-input-login validate-input" data-validate="<?php echo e(_i('Enter password')); ?>">
						<span class="btn-show-pass">
							<i class="fa fa-eye-slash"></i>
						</span>
                            <input class="input-login" type="password" name="password" id="password" autocomplete="off" placeholder="<?php echo e(_i('At least 8 characters')); ?>" required>
                        </div>

                        <div class="wrap-input-login">
                            <a href="#" class="a-login"><?php echo e(_i('have you forgotten your password?')); ?></a>
                        </div>

                        <div class="container-login-form-btn">
                            <button class="btn-login" id="login" type="button" data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Please wait...')); ?>">
                                <?php echo e(_i('Login')); ?>

                            </button>
                        </div>

                        <div class="login-tag login-tag-invisible show-tag show-input-email">
                            <div class="wrap-input-divider">
                                O
                            </div>

                            <div class="container-login-form-btn">
                                <button class="btn-login-google" type="button" data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Please wait')); ?>...">
                                    <img src="https://bestcasinos-llc.s3.us-east-2.amazonaws.com/templates/google.png"> <?php echo e(_i('Sign in with Google')); ?>

                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('modals'); ?>
    <?php echo $__env->make('auth.modals.change-password', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            Auth.login();
        });

        $(window).on('load', function () {
            $('.loader-login').hide();
            $('.login-form-ex').addClass('load');
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('auth.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>