

<?php $__env->startSection('styles'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-xl-12">
            <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                <header
                    class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                    <div class="media">
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                            <?php echo e($title); ?>

                        </h3>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <form action="<?php echo e(route('agents.store-user')); ?>" method="post" id="create-users-form">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="username"><?php echo e(_i('Username')); ?></label>
                                        <input type="text" name="username" class="form-control" autocomplete="off">
                                        <small class="form-text text-muted"><?php echo e(_i('Only letters and numbers without spaces (4-12 characters)')); ?></small>
                                        <small class="form-text text-muted"><?php echo e(_i('The username cannot be changed later')); ?></small>
                                    </div>
                                </div>







                                <div class="col-12 col-md-6">
                                    <label for="password"><?php echo e(_i('Password')); ?></label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="password">
                                        <div class="input-group-append">
                                            <button class="btn u-input-btn--v1 g-width-40 u-btn-primary g-rounded-right-4 u-btn-3d refresh-password" type="button">
                                                <i class="hs-admin-reload g-absolute-centered g-font-size-16 g-color-white"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted"><?php echo e(_i('Minimum 8 characters, 1 letter and 1 number')); ?></small>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="balance"><?php echo e(_i('Balance')); ?></label>
                                        <input type="number" name="balance" class="form-control">
                                        <small class="form-text text-danger">
                                            <?php echo e(_i('Available')); ?>: <span class="balance"></span>
                                        </small>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12">
                                    <div class="form-group">
                                        <label class="form-check-inline u-check g-pl-25">
                                            <input class="g-hidden-xs-up g-pos-abs g-top-0 g-left-0" name="show_data_user"
                                                   id="show_data_user" type="checkbox">
                                            <div class="u-check-icon-checkbox-v6 g-absolute-centered--y g-left-0">
                                                <i class="fa" data-check-icon="&#xf00c"></i>
                                            </div>
                                            <?php echo e(_i('Show additional data')); ?>

                                        </label>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 option_data_user d-none">
                                    <div class="form-group">
                                        <label for="email"><?php echo e(_i('Email')); ?></label>
                                        <input type="email" name="email" id="email" class="form-control"
                                               placeholder="<?php echo e(_i('Optional')); ?>" autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 option_data_user d-none">
                                    <div class="form-group">
                                        <label for="country"><?php echo e(_i('Country')); ?></label>
                                        <select name="country" id="country" class="form-control" style="width: 100%">
                                            <option value=""><?php echo e(_i('Select...')); ?></option>
                                            <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option
                                                    value="<?php echo e($country->iso); ?>" <?php echo e($country->iso == $agent->country_iso ? 'selected' : ''); ?>>
                                                    <?php echo e($country->name); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4 option_data_user d-none">
                                    <div class="form-group">
                                        <label for="timezone"><?php echo e(_i('Timezone')); ?></label>
                                        <select name="timezone" id="timezone" class="form-control" style="width: 100%">
                                            <option value=""><?php echo e(_i('Select...')); ?></option>
                                            <?php $__currentLoopData = $timezones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timezone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option
                                                    value="<?php echo e($timezone); ?>" <?php echo e($timezone == session()->get('timezone') ? 'selected' : ''); ?>>
                                                    <?php echo e($timezone); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn u-btn-primary u-btn-3d" id="create-user"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Please wait...')); ?>">
                                <?php echo e(_i('Create player')); ?>

                            </button>
                            <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                                <?php echo e(_i('Close')); ?>

                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let agents = new Agents();
            agents.dashboard();
            agents.optionsFormUser();
            agents.storeUsers();
            agents.balanceAgentCurrent('<?php echo e(route('agents.consult.balance.by.type')); ?>');
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>