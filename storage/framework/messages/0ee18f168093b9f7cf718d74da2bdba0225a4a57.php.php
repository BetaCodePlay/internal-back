

<?php $__env->startSection('title', _i('Internal error')); ?>
<?php $__env->startSection('code', '500'); ?>
<?php $__env->startSection('message', _i('Internal error')); ?>

<?php echo $__env->make('errors.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>