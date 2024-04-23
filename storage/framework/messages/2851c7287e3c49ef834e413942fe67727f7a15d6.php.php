

<?php $__env->startSection('content'); ?>
    <div class="container-auth">
        <div class="container-auth-bg" style="background-image: url('https://bestcasinos-llc.s3.us-east-2.amazonaws.com/templates/bg-login-v2.jpg')"></div>
        <div class="bg-opacity"></div>
        <div class="content">
            <div class="content-ex">
                <div class="auth-figure"><img src="https://bestcasinos-llc.s3.us-east-2.amazonaws.com/templates/air-decorate-auth.png"></div>
                <div class="auth-title"><?php echo e(_i('We send you an email.')); ?></div>
                <div class="auth-subtitle"><?php echo e(_i('To reset the account we send you a link to your email for security, enter the new password below.')); ?></div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('auth.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>