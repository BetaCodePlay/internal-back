

<?php $__env->startSection('content'); ?>
    <form action="<?php echo e(route('bonus-system.campaigns.update')); ?>" id="campaigns-form" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12">
                <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                    <header
                        class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                        <div class="media">
                            <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                                <?php echo e(_i('Users details')); ?>

                            </h3>
                            <div class="media-body d-flex justify-content-end">
                                <a href="<?php echo e(route('bonus-system.campaigns.index')); ?>" class="btn u-btn-3d u-btn-primary float-right">
                                    <i class="hs-admin-layout-list-thumb"></i>
                                    <?php echo e(_i('Go to list')); ?>

                                </a>
                            </div>
                        </div>
                    </header>
                    <div class="card-block g-pa-15">
                        <div class="row">
                            <div class="card-block g-pa-15">
                                <div class="noty_bar noty_type__warning noty_theme__unify--v1--dark g-mb-25">
                                    <div class="noty_body">
                                        <div class="g-mr-20">
                                            <div class="noty_body__icon">
                                                <i class="hs-admin-info"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <p>
                                                <?php echo e(_i('For the assignment of users, the excluded will be taken into account as the first option')); ?>

                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php if(isset($campaign->data->user_search_type)): ?>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="user_search_type"><?php echo e(_i('User search type')); ?></label>
                                        <select name="user_search_type" id="user_search_type" class="form-control">
                                            <?php if($campaign->data->user_search_type == \App\BonusSystem\Enums\UsersSearchTypes::$users): ?>
                                                <option value=""><?php echo e(_i('Select...')); ?></option>
                                                <option value="users" selected><?php echo e(_i('Users')); ?></option>
                                                <option value="segments"><?php echo e(_i('Segments')); ?></option>
                                                <option value="excel"><?php echo e(_i('Excel')); ?></option>
                                                <option value="all"><?php echo e(_i('All')); ?></option>
                                            <?php elseif($campaign->data->user_search_type == \App\BonusSystem\Enums\UsersSearchTypes::$segments): ?>
                                                <option value="users"><?php echo e(_i('Users')); ?></option>
                                                <option value="segments" selected><?php echo e(_i('Segments')); ?></option>
                                                <option value="excel"><?php echo e(_i('Excel')); ?></option>
                                                <option value="all"><?php echo e(_i('All')); ?></option>
                                            <?php elseif($campaign->data->user_search_type == \App\BonusSystem\Enums\UsersSearchTypes::$excel): ?>
                                                <option value="users"><?php echo e(_i('Users')); ?></option>
                                                <option value="segments"><?php echo e(_i('Segments')); ?></option>
                                                <option value="excel" selected><?php echo e(_i('Excel')); ?></option>
                                                <option value="all"><?php echo e(_i('All')); ?></option>
                                            <?php else: ?>
                                                <option value="users"><?php echo e(_i('Users')); ?></option>
                                                <option value="segments"><?php echo e(_i('Segments')); ?></option>
                                                <option value="excel"><?php echo e(_i('Excel')); ?></option>
                                                <option value="all" selected><?php echo e(_i('All')); ?></option>
                                            <?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                                <?php if($campaign->data->user_search_type == \App\BonusSystem\Enums\UsersSearchTypes::$users): ?>
                                    <div class="col-md-12">
                                        <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                                            <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                                                <div class="media">
                                                    <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                                                        <?php echo e(_i('Include users')); ?>

                                                    </h3>
                                                    <div class="media-body d-flex justify-content-end g-mb-10" id="users-table-buttons">

                                                    </div>
                                                </div>
                                            </header>
                                            <div class="card-block g-pa-15">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered w-100" id="users-table"
                                                           data-route="<?php echo e(route('bonus-system.campaigns.include-users', [$campaign->id])); ?>">
                                                        <thead>
                                                        <tr>
                                                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                                                <?php echo e(_i('ID')); ?>

                                                            </th>
                                                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                                                <?php echo e(_i('Username')); ?>

                                                            </th>
                                                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                                                <?php echo e(_i('Actions')); ?>

                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if($campaign->data->user_search_type == \App\BonusSystem\Enums\UsersSearchTypes::$segments): ?>
                                    <div class="col-md-12">
                                        <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                                            <header class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
                                                <div class="media">
                                                    <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                                                        <?php echo e(_i('Segments')); ?>

                                                    </h3>
                                                    <div class="media-body d-flex justify-content-end g-mb-10" id="segments-table-buttons">

                                                    </div>
                                                </div>
                                            </header>
                                            <div class="card-block g-pa-15">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered w-100" id="segments-table"
                                                           data-route="<?php echo e(route('bonus-system.campaigns.include-segments', [$campaign->id])); ?>">
                                                        <thead>
                                                        <tr>
                                                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                                                <?php echo e(_i('Segment')); ?>

                                                            </th>
                                                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                                                <?php echo e(_i('Actions')); ?>

                                                            </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <?php if($campaign->data->user_search_type == \App\BonusSystem\Enums\UsersSearchTypes::$all): ?>
                                    <div class="col-md-12">
                                        <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
                                            <div>
                                                <p>
                                                    <?php echo e(_i('Campaign for all users')); ?>

                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php if(!isset($campaign->data->user_search_type)): ?>
                                <div class="col-md-12 search-type d-none">
                                    <div class="row m--margin-bottom-20">
                                        <div class="col-md-6 search-users d-none">
                                            <div class="form-group">
                                                <label for="include_user"><?php echo e(_i('Include users')); ?></label>
                                                <select name="include_user[]" id="include_user" class="form-control select2" data-route="<?php echo e(route('users.search-username')); ?>" multiple>
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 search-users d-none">
                                            <div class="form-group">
                                                <label for="exclude_user"><?php echo e(_i('Exclude users')); ?></label>
                                                <select name="exclude_user[]" id="exclude_user" class="form-control select2" data-route="<?php echo e(route('users.search-username')); ?>" multiple>
                                                    <option></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 search-segments d-none">
                                            <div class="form-group">
                                                <label for="include_segments"><?php echo e(_i('Include segments')); ?></label>
                                                <select name="include_segments[]" id="include_segments" class="form-control" multiple>
                                                    <option value=""><?php echo e(_i('Select...')); ?></option>
                                                    <?php $__currentLoopData = $segments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $segment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($segment->id); ?>">
                                                            <?php echo e($segment->name); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 search-segments d-none">
                                            <div class="form-group">
                                                <label for="exclude_segments"><?php echo e(_i('Exclude segments')); ?></label>
                                                <select name="exclude_segments[]" id="exclude_segments" class="form-control" multiple>
                                                    <option value=""><?php echo e(_i('Select...')); ?></option>
                                                    <?php $__currentLoopData = $segments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $segment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($segment->id); ?>">
                                                            <?php echo e($segment->name); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 search-excel d-none">
                                            <div class="form-group">
                                                <label for="include_excel"><?php echo e(_i('Include excel')); ?></label>
                                                <input type="file" name="include_excel" id="include_excel">
                                            </div>
                                        </div>
                                        <div class="col-md-6 search-excel d-none">
                                            <div class="form-group">
                                                <label for="exclude_excel"><?php echo e(_i('Exclude excel')); ?></label>
                                                <input type="file" name="exclude_excel" id="exclude_excel">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="submit" class="btn u-btn-3d u-btn-primary" id="update"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Updating...')); ?>">
                                        <i class="hs-admin-reload"></i>
                                        <?php echo e(_i('Update campaign')); ?>

                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <?php echo $__env->make('back.bonus-system.campaigns.modals.add-translations-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let bonusSystem = new BonusSystem();
            bonusSystem.usersSearchType();
            bonusSystem.select2ExcludeUsers('<?php echo e(_i('Select user')); ?>');
            bonusSystem.select2IncludeUsers('<?php echo e(_i('Select user')); ?>');
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>