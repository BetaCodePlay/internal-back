

<?php $__env->startSection('title', _i('Page not found')); ?>
<?php $__env->startSection('code', '404'); ?>
<?php $__env->startSection('message', _i('Page not found')); ?>

<?php echo $__env->make('errors.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>