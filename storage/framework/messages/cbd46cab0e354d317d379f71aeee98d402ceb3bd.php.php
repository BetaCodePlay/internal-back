<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('back/css/vendor.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('back/css/custom.min.css')); ?>?v=2">
    <link rel="stylesheet"
          href="//fonts.googleapis.com/css?family=Open+Sans%3A400%2C300%2C500%2C600%2C700%7CPlayfair+Display%7CRoboto%7CRaleway%7CSpectral%7CRubik">
    <title><?php echo $__env->yieldContent('title'); ?></title>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <!-- Styles -->
    <style>
        html {
            line-height: 1.15;
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        body {
            margin: 0;
        }

        header,
        nav,
        section {
            display: block;
        }

        figcaption,
        main {
            display: block;
        }

        a {
            background-color: transparent;
            -webkit-text-decoration-skip: objects;
        }

        strong {
            font-weight: inherit;
        }

        strong {
            font-weight: bolder;
        }

        code {
            font-family: monospace, monospace;
            font-size: 1em;
        }

        dfn {
            font-style: italic;
        }

        svg:not(:root) {
            overflow: hidden;
        }

        button,
        input {
            font-family: sans-serif;
            font-size: 100%;
            line-height: 1.15;
            margin: 0;
        }

        button,
        input {
            overflow: visible;
        }

        button {
            text-transform: none;
        }

        button,
        html [type="button"],
        [type="reset"],
        [type="submit"] {
            -webkit-appearance: button;
        }

        button::-moz-focus-inner,
        [type="button"]::-moz-focus-inner,
        [type="reset"]::-moz-focus-inner,
        [type="submit"]::-moz-focus-inner {
            border-style: none;
            padding: 0;
        }

        button:-moz-focusring,
        [type="button"]:-moz-focusring,
        [type="reset"]:-moz-focusring,
        [type="submit"]:-moz-focusring {
            outline: 1px dotted ButtonText;
        }

        legend {
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            color: inherit;
            display: table;
            max-width: 100%;
            padding: 0;
            white-space: normal;
        }

        [type="checkbox"],
        [type="radio"] {
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            padding: 0;
        }

        [type="number"]::-webkit-inner-spin-button,
        [type="number"]::-webkit-outer-spin-button {
            height: auto;
        }

        [type="search"] {
            -webkit-appearance: textfield;
            outline-offset: -2px;
        }

        [type="search"]::-webkit-search-cancel-button,
        [type="search"]::-webkit-search-decoration {
            -webkit-appearance: none;
        }

        ::-webkit-file-upload-button {
            -webkit-appearance: button;
            font: inherit;
        }

        menu {
            display: block;
        }

        canvas {
            display: inline-block;
        }

        template {
            display: none;
        }

        [hidden] {
            display: none;
        }

        html {
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            font-family: sans-serif;
        }

        *,
        *::before,
        *::after {
            -webkit-box-sizing: inherit;
            box-sizing: inherit;
        }

        p {
            margin: 0;
        }

        button {
            background: transparent;
            padding: 0;
        }

        button:focus {
            outline: 1px dotted;
            outline: 5px auto -webkit-focus-ring-color;
        }

        *,
        *::before,
        *::after {
            border-width: 0;
            border-style: solid;
            border-color: #dae1e7;
        }

        button,
        [type="button"],
        [type="reset"],
        [type="submit"] {
            border-radius: 0;
        }

        button,
        input {
            font-family: inherit;
        }

        input::-webkit-input-placeholder {
            color: inherit;
            opacity: .5;
        }

        input:-ms-input-placeholder {
            color: inherit;
            opacity: .5;
        }

        input::-ms-input-placeholder {
            color: inherit;
            opacity: .5;
        }

        input::placeholder {
            color: inherit;
            opacity: .5;
        }

        button,
        [role=button] {
            cursor: pointer;
        }

        .bg-white {
            background-color: #fff;
        }

        .flex {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
        }

        .items-center {
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
        }

        .justify-center {
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
        }

        .font-light {
            font-weight: 300;
        }

        .font-black {
            font-weight: 900;
        }

        .leading-normal {
            line-height: 1.5;
        }

        .m-8 {
            margin: 2rem;
        }

        .mb-8 {
            margin-bottom: 2rem;
        }

        .max-w-sm {
            max-width: 30rem;
        }

        .min-h-screen {
            min-height: 100vh;
        }

        .text-black {
            color: #22292f;
        }

        .text-grey-darker {
            color: #606f7b;
        }

        .text-2xl {
            font-size: 1.5rem;
        }

        .text-5xl {
            font-size: 3rem;
        }

        .w-full {
            width: 100%;
        }

        @media (min-width: 768px) {
            .md\:flex {
                display: -webkit-box;
                display: -ms-flexbox;
                display: flex;
            }

            .md\:text-3xl {
                font-size: 1.875rem;
            }

            .md\:text-15xl {
                font-size: 9rem;
            }
        }
    </style>
</head>
<body class="currency-theme-<?php echo e(session('currency')); ?>">
<?php echo $__env->make('back.layout.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<main class="container-fluid px-0 g-pt-65">
    <div class="row no-gutters g-pos-rel g-overflow-x-hidden">
        <?php echo $__env->make('back.layout.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="col g-ml-45 g-ml-0--lg g-pb-65--md">
            <div class="g-pa-20">
                <div class="md:flex min-h-screen">
                    <div class="w-full bg-white flex items-center justify-center">
                        <div class="max-w-sm m-8">
                            <div class="text-black text-5xl md:text-15xl font-black">
                                <?php echo $__env->yieldContent('code'); ?>
                            </div>
                            <p class="text-grey-darker text-2xl md:text-3xl font-light mb-8 leading-normal">
                                <?php echo $__env->yieldContent('message'); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <?php echo $__env->make('back.layout.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
</main>
<script src="<?php echo e(mix('js/manifest.js', 'back')); ?>"></script>
<script src="<?php echo e(mix('js/vendor.js', 'back')); ?>"></script>
<script src="<?php echo e(mix('js/custom.min.js', 'back')); ?>"></script>
<script src="<?php echo e(asset('back/js/scripts.min.js')); ?>"></script>
</body>
</html>
