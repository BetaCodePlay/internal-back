

<?php $__env->startSection('title', _i('Forbidden')); ?>
<?php $__env->startSection('code', '403'); ?>
<?php $__env->startSection('message', _i('Forbidden')); ?>

<?php echo $__env->make('errors.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>