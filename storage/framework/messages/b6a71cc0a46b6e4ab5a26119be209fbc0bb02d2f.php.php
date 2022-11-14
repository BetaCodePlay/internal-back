

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
                    <form action="<?php echo e(route('security.manage-store-permissions-users')); ?>" id="save-form" method="post">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                <label for="user"><?php echo e(_i('User')); ?></label>
                                    <select class="form-control select2 permissionsselect user" id="user" name="users[]"
                                            data-route="<?php echo e(route('users.search-username')); ?>" multiple>
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="permissions"><?php echo e(_i('Permissions')); ?></label>
                                    <select name="permissions[]" id="permissionselect" class="form-control select2 permissionselect user " multiple>
                                        <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($permission->id); ?>">
                                                <?php echo e($permission->description); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" class="btn u-btn-3d u-btn-primary" id="save"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Saving...')); ?>">
                                        
                                        <?php echo e(_i('Save')); ?>

                                    </button>
                                    <button type="button" class="btn u-btn-3d u-btn-bluegray" id="clear">
                                        <i class="hs-admin-close"></i>
                                        <?php echo e(_i('Clear')); ?>

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
                                <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 g-mb-0">
                                    <?php echo e(_i('Users and Permissions')); ?>

                                </h3>
                            </div>
                        </header>
                        <div class="card-block g-pa-15">
                            <div class="table-responsive">
                                <input type="hidden" name="permissions" id="permissions" >
                                <table class="table table-bordered w-100" id="permissions-users-table" data-route="<?php echo e(route('security.manage-permissions-users-data')); ?>">
                                    <thead>
                                    <tr>
                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                            <?php echo e(_i('ID')); ?>

                                        </th>
                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                            <?php echo e(_i('Users')); ?>

                                        </th>
                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                            <?php echo e(_i('permissions')); ?>

                                        </th>
                                        <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                            <?php echo e(_i('Actions')); ?>

                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        </div>

<?php echo $__env->make('back.security.permissions.modals.edit-permissions', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let security = new Security();
            let users = new Users();
            security.assignPermissionToUser();
            security.permissionsUsers();
            security.editPermissionUsers();
            security.select2permission('<?php echo e(_i('Select permission')); ?>')
            security.select2Users('<?php echo e(_i('Select user')); ?>');
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>