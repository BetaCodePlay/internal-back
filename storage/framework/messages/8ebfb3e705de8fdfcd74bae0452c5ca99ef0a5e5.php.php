<div class="modal fade modal-style" id="role-create">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(_i('Create role')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><span class="font-weight-bold"><?php echo e(_i('What role do you want to create?')); ?></span></p>
                <p><?php echo e(_i('You will be able to create master and support agents initially, then you can assign players if necessary.')); ?></p>
                <form autocomplete="destroy" class="form">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label><?php echo e(_i('Name')); ?></label>
                                <input type="text" class="form-control" placeholder="" id="createRolUsername">
                            </div>
                        </div>
                        <div class="col-12 col-lg-6">
                            <div class="form-group">
                                <label><?php echo e(_i('Password')); ?></label>
                                <div class="wrap-input">
                                    <input type="text" class="form-control" name="password" id="createRolPassword">
                                    <div class="wrap-element">
                                        <button class="btn btn-theme" type="button" id="createRoPasswordRefresh">
                                            <i class="fa-solid fa-arrows-rotate"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if($agent?->master): ?>
                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                    <label><?php echo e(_i('Role type')); ?></label>
                                    <select class="form-control" id="createRolType">
                                        <option value="true"><?php echo e(_i('Master')); ?></option>
                                        <option value="false"><?php echo e(_i('Support')); ?></option>
                                        <option value=""><?php echo e(_i('Players')); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-lg-6">
                                <div class="form-group">
                                    <label><?php echo e(_i('Dependence on')); ?></label>
                                    <select class="form-control" id="createRolDependence">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <option value="<?php echo e(auth()->user()->id); ?>"><?php echo e(auth()->user()->username); ?></option>
                                        <?php $__currentLoopData = $dependencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dependece): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($dependece['user_id']); ?>">
                                                <?php echo e($dependece['username']); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 col-lg-6 d-agent">
                                <div class="form-group">
                                    <label><?php echo e(_i('Percentage')); ?></label>
                                    <div class="wrap-input">
                                        <input type="text" name="percentage" class="form-control" placeholder="Rango disponible de 1 - 99" id="createRolPercentage" data-max="99">
                                        <div class="wrap-element">
                                            <div class="wrap-element-text">%</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-transparent" data-dismiss="modal">
                    <?php echo e(_i('Cancel creation')); ?>

                </button>
                <button type="button" class="btn btn-theme createUser" data-route-agent="<?php echo e(route('agents.role.store-rol')); ?>" data-route-player="<?php echo e(route('agents.role.store-user')); ?>" data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('creating role')); ?>...">
                    <?php echo e(_i('Ready! Create role')); ?>

                </button>
            </div>
        </div>
    </div>
</div>
