

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
                    <form action="<?php echo e(route('core.add.rol.admin')); ?>" id="change-rol-form"  method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="username_search"><?php echo e(_i('Search Admin')); ?></label>
                                    <select name="user_id" id="username_search"
                                            class="form-control select2 username_search agent_id_search"
                                            required="required"
                                            data-route="<?php echo e(route('agents.search-username')); ?>?type=1"
                                            data-select="<?php echo e(route('agents.find-user')); ?>">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rol_id"><?php echo e(_i('Rol')); ?></label>
                                    <select name="rol_id[]" id="rol_id" class="form-control"
                                            required="required" multiple>
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($value->id); ?>"><?php echo e($value->description); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12" style="text-align: end;">
                                <div class="form-group">
                                    <button type="button" class="btn u-btn-3d u-btn-primary" id="changeRolAdmin"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Please wait...')); ?>">
                                        <i class="hs-admin-save"></i>
                                        <?php echo e(_i('Add')); ?>

                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-12">
                            <h4 class="textTitleRol"></h4>
                        </div>

                        <div class="col-6" id="listRoles" data-route_delete="<?php echo e(route('core.delete.rol.admin')); ?>">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let agents = new Agents();
            let users = new Users();
            agents.selectUserSearch('<?php echo e(_i('User search...')); ?>','<?php echo e(_i('Roles current')); ?>','<?php echo e(_i('No role assigned')); ?>');
            users.changeRolAdmin();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>