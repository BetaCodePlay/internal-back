<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('back/css/vendor.min.css')); ?>?v=2">
    <link rel="stylesheet" href="<?php echo e(asset('back/css/custom.min.css')); ?>?v=13">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Open+Sans%3A400%2C300%2C500%2C600%2C700%7CPlayfair+Display%7CRoboto%7CRaleway%7CSpectral%7CRubik">
    <?php if(\Dotworkers\Configurations\Configurations::getWhitelabel() == 109): ?>
        <link rel="shortcut icon" href="<?php echo e(asset('commons/img/bloko-favicon.png')); ?>">
    <?php else: ?>
        <link rel="shortcut icon" href="<?php echo e($favicon); ?>">
    <?php endif; ?>
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo e($favicon); ?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo e($favicon); ?>">
    <title><?php echo e($title ?? _i('BackOffice')); ?></title>
    <link rel="stylesheet" href="<?php echo e(asset('commons/css/template.min.css')); ?>?v=0.47">
    <?php echo $__env->yieldContent('styles'); ?>
    <style>
        li.has-active .u-side-nav-opened {
            background-color: #f4f4f41f !important;
        }
    </style>
</head>
<body class="currency-theme-<?php echo e(session('currency')); ?>">
<?php echo $__env->make('back.layout.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<main class="container-fluid px-0 g-pt-65">
    <div class="row no-gutters g-pos-rel g-overflow-x-hidden">
        <?php echo $__env->make('back.layout.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="col g-ml-45 g-ml-0--lg g-pb-65--md">
            <?php echo $__env->make('back.layout.warning', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>






            <div class="g-pa-20">
                <?php echo $__env->yieldContent('content'); ?>
                <?php if(!empty($action)): ?>
                    <?php if($iagent == 1): ?>
                        <?php echo $__env->make('back.users.modals.reset-email', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <?php echo $__env->make('back.layout.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
</main>
<script src="<?php echo e(mix('js/manifest.js', 'back')); ?>"></script>
<script src="<?php echo e(mix('js/vendor.js', 'back')); ?>"></script>
<script src="<?php echo e(mix('js/custom.min.js', 'back')); ?>"></script>
<script src="<?php echo e(asset('back/js/scripts.min.js')); ?>?v=23"></script>





<?php echo $__env->yieldContent('scripts'); ?>
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$tawk_chat])): ?>
    <?php echo $__env->make('back.layout.tawk', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php echo $__env->make('back.layout.chat', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script>
    <?php if(env('APP_ENV') == 'testing'): ?>
    $(function () {
        //let socket = new Socket();
        //socket.initChannel('<?php echo e(session()->get('betpay_client_id')); ?>', '<?php echo e($favicon); ?>', '<?php echo e(route('push-notifications.store')); ?>');
    });
    <?php endif; ?>
</script>
</body>
</html>

