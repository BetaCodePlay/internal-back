<!doctype html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <?php if(\Dotworkers\Configurations\Configurations::getWhitelabel() == 109): ?>
        <link rel="shortcut icon" href="<?php echo e(asset('commons/img/bloko-favicon.png')); ?>">
    <?php else: ?>
        <link rel="shortcut icon" href="<?php echo e($favicon); ?>">
    <?php endif; ?>
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo e($favicon); ?>">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo e($favicon); ?>">
    <title><?php echo e($title ?? _i('BackOffice')); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('back/css/vendor.min.css')); ?>?v=6.33">
    <link rel="stylesheet" href="<?php echo e(asset('back/css/custom.min.css')); ?>?v=12.43">
    <link href="https://unpkg.com/primeicons/primeicons.css " rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset("themes/$theme")); ?>?v=1.011">

    <!--<link href="https://cdn.datatables.net/2.0.0/css/dataTables.dataTables.css" rel="stylesheet">-->

    <script>
        window.authUserId = parseInt('<?php echo e(auth()->id()); ?>')
        window.timezone = "<?php echo e(session('timezone')); ?>"
        window.userBalance = "<?php echo e(getAuthenticatedUserBalance(true)); ?>"
        String.prototype.formatMoney = function (decimalPlaces = 2, currency = null) {
            return new Intl.NumberFormat("es-ES", {
                style: "currency",
                currency: "<?php echo e(session('currency')); ?>",
                minimumFractionDigits: decimalPlaces,
            }).format(this);
        };
        Number.prototype.formatMoney = function (decimalPlaces = 2, currency = null) {
            return new Intl.NumberFormat("es-ES", {
                style: "currency",
                currency: "<?php echo e(session('currency')); ?>",
                minimumFractionDigits: decimalPlaces,
            }).format(this);
        };

    </script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.13.1/xlsx.full.min.js"></script>
    <?php echo $__env->yieldContent('styles'); ?>
    <style>
        li.has-active .u-side-nav-opened {
            background-color: #f4f4f41f !important;
        }
    </style>
</head>

<body class="currency-theme-<?php echo e(session('currency')); ?>">
<div id="app">
    <?php echo $__env->make('back.layout.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <main class="container-fluid px-0 g-pt-65">
        <div class="row no-gutters g-pos-rel">
            <?php echo $__env->make('back.layout.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <div class="col col-general">
                <div class="g-pa-20 g-pt-30 g-pb-30">
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
</div>

<?php echo $__env->yieldContent('modals'); ?>

<script src="<?php echo e(mix('js/manifest.js', 'back')); ?>"></script>
<script src="<?php echo e(mix('js/vendor.js', 'back')); ?>"></script>
<script src="<?php echo e(mix('js/custom.min.js', 'back')); ?>"></script>
<script src="<?php echo e(asset('back/js/scripts.min.js')); ?>?v=24"></script>





<?php echo $__env->yieldContent('scripts'); ?>
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('access', [\Dotworkers\Security\Enums\Permissions::$tawk_chat])): ?>
    <?php echo $__env->make('back.layout.tawk', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php endif; ?>
<?php echo $__env->make('back.layout.chat', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<script>
    /*  <?php if(env('APP_ENV') == 'testing'): ?>
    $(function() {
        let socket = new Socket();
        socket.initChannel('<?php echo e(session()->get('betpay_client_id')); ?>', '<?php echo e($favicon); ?>', '<?php echo e(route('push - notifications.store ')); ?>');
        });
        <?php endif; ?> */
    Global.sidebar();
</script>
</body>

</html>
