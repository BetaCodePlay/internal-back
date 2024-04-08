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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(mix('css/vendor.min.css', 'auth')); ?> ">
    <link rel="stylesheet" href="<?php echo e(mix('css/custom.min.css', 'auth')); ?> ">
    <!--===============================================================================================-->
</head>
<body class="body-auth">
<div class="limiter">
    <?php echo $__env->yieldContent('content'); ?>
</div>


<?php echo $__env->yieldContent('modals'); ?>

<script src="<?php echo e(mix('js/manifest.js', 'auth')); ?>"></script>
<script src="<?php echo e(mix('js/vendor.js', 'auth')); ?>"></script>
<script src="<?php echo e(mix('js/custom.min.js', 'auth')); ?>"></script>
<?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
