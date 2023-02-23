

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                            <?php echo e($title); ?>

                        </h3>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <form action="<?php echo e(route('games.store')); ?>" id="store-form" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="change_provider"><?php echo e(_i('Provider')); ?></label>
                                    <select name="change_provider" id="change_provider" data-route="<?php echo e(route('games.game')); ?>" class="form-control">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <?php $__currentLoopData = $providers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $provider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($provider->provider_id); ?>">
                                                <?php echo e($provider->name); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="">
                                    <input type="checkbox" class="checkshow" name="personalize" autocomplete="off">
                                    <span class="glyphicon glyphicon-ok"><?php echo e(_i('Games Personalize: ')); ?></span>
                                </label>
                                <div class="div_a_show">
                                    <div>
                                        <select name="games[]" id="games" class="form-control"
                                                data-loading-text="<i class='fa fa-spin fa-spinner'></i>  <?php echo e(_i('Loading...')); ?>"
                                                multiple>
                                            <option value=""><?php echo e(_i('Select')); ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <?php if(isset($route)): ?>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="route"><?php echo e(_i('Menu where it will be shown')); ?></label>
                                        <select select name="route" id="route" class="form-control">
                                            <option value=""><?php echo e(_i('Select...')); ?></option>
                                            <?php $__currentLoopData = $route; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($item->route); ?>">
                                                    <?php echo e($item->name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="div_a_show col-md-6">
                                <div class="form-group">
                                    <label for="order"><?php echo e(_i('Order (optional)')); ?></label>
                                    <input type="number" name="order" id="order" value="0" class="form-control" min="0">
                                </div>
                            </div>
                            <div class="div_a_show card-block g-pa-15">
                                <div class="noty_bar noty_type__warning noty_theme__unify--v1--dark g-mb-25">
                                    <div class="noty_body">
                                        <div class="g-mr-20">
                                            <div class="noty_body__icon">
                                                <i class="hs-admin-info"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p>
                                                <?php echo e(_i(' The maximum file size is 5mb')); ?>

                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="image"><?php echo e(_i('Image')); ?></label>
                                    <input type="file" name="image" id="image" class="opacity-0">
                                </div>
                            </div>
                            <br>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="store"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Saving...')); ?>">
                                        <i class="hs-admin-save"></i>
                                        <?php echo e(_i('Save')); ?>

                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0 title">
                            <?php echo e(_i('Games')); ?>

                        </h3>
                        <div class="media-body d-flex justify-content-end" id="table-buttons">

                        </div>
                        <div class="justify-content-end g-ml-10" style="padding-left: 10px">
                        </div>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <table class="table table-bordered table-responsive-sm w-100" id="games-table" data-route="<?php echo e(route('games.all')); ?>">
                        <thead>
                        <tr>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Image')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Provider')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Game')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Menu')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Order')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Actions')); ?>

                            </th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let lobbyGames = new LobbyGames()
            lobbyGames.all();
            lobbyGames.game();
            lobbyGames.store();
        });
    </script>
    <script>
        $(function() {

            // obtener campos ocultar div
            var checkbox = $(".checkshow");
            var hidden = $(".div_a_show");
            //

            hidden.hide();
            checkbox.change(function() {
                if (checkbox.is(':checked')) {
                    //hidden.show();
                    $(".div_a_show").fadeIn("200")
                } else {
                    //hidden.hide();
                    $(".div_a_show").fadeOut("200")
                    $('input[type=checkbox]').prop('checked',false);// limpia los valores de checkbox al ser ocultado

                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#limpiar').click(function() {
                $('select[type="text"]').val('');
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>