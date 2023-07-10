

<?php $__env->startSection('title'); ?>
    <?php echo e($title); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('subtitle'); ?>
    <?php echo e($subtitle); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php echo e($content); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('button'); ?>
    <a href="<?php echo e($url); ?>"
       style="color: #fff; font-family: Arial, Helvetica, sans-serif; font-size: 13px; background: #ff8500; padding:  15px 40px; text-transform: uppercase; font-weight: bold; text-decoration: none; border-radius: 5px;">
        <?php echo e($button); ?>

    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer'); ?>
    <strong>
        <?php echo e($footer); ?>

    </strong>
    <br>
    <br>
    <a href="<?php echo e($url); ?>" style="text-decoration: none; color: #ff8500; font-weight: 600;">
        <?php echo e($url); ?>

    </a>
    <br>
    <br>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.validate-email', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>