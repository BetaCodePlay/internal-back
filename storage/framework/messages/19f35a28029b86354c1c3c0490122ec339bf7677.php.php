<div class="modal fade" id="details-user-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(_i('Information of the User')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo e(route('agents.move-agent')); ?>" id="move-agent-form" method="post">
                <div class="modal-body">
                    <div class="row row-div">

                        <div class="col-lg-6 col-overauto">
                            <div class="row mb-2">
                                <div class="offset-md-1 col-md-3">
                                    <strong> <?php echo e(_i('User ID')); ?>: </strong>
                                </div>
                                <div class="col-md-8">
                                    <span class="userIdSet"></span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="offset-md-1 col-md-3">
                                    <strong> <?php echo e(_i('User')); ?>: </strong>
                                </div>
                                <div class="col-md-8">
                                    <span class="userSet"></span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="offset-md-1 col-md-3">
                                    <strong> <?php echo e(_i('Email')); ?>: </strong>
                                </div>
                                <div class="col-md-8">
                                    <span class="emailSet"></span>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="offset-md-1 col-md-3">
                                    <strong> <?php echo e(_i('Agent ID')); ?>: </strong>
                                </div>
                                <div class="col-md-8">
                                    <span class="agentIdSet"></span>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="offset-md-1 col-md-3">
                                    <strong> <?php echo e(_i('Father')); ?>: </strong>
                                </div>
                                <div class="col-md-8">
                                    <span class="fatherSet"></span>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="offset-md-1 col-md-3">
                                    <strong> <?php echo e(_i('Rol')); ?>: </strong>
                                </div>
                                <div class="col-md-8">
                                    <span class="typeSet"></span>
                                </div>
                            </div>
                            <div class="row mb-1 cantA_P">
                                <div class="offset-md-1 col-md-3">
                                    <strong> <?php echo e(_i('Agents')); ?>: </strong>
                                </div>
                                <div class="col-md-8">
                                    <span class="agentsSet"></span>
                                </div>
                            </div>
                            <div class="row mb-1 cantA_P">
                                <div class="offset-md-1 col-md-3">
                                    <strong> <?php echo e(_i('Players')); ?>: </strong>
                                </div>
                                <div class="col-md-8">
                                    <span class="playersSet"></span>
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="offset-md-1 col-md-3">
                                    <strong> <?php echo e(_i('Created')); ?>: </strong>
                                </div>
                                <div class="col-md-8">
                                    <span class="createdSet"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-overauto">
                            <div class="row mb-1">
                                <div class="col-md-12">
                                   <h5><strong> <?php echo e(_i('Structure')); ?></strong></h5>
                                </div>
                            </div>
                            <div class="col-12 appendTreeFather">
                                <h5 style="text-align: center;"><strong> <?php echo e(_i('Loading')); ?>...</strong></h5>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <br>
                            <br>
                            <div class="table-responsive">
                                <table class="table table-bordered w-100" id="ipTableIps" data-route="<?php echo e(route('users.users-ips-data')); ?>">
                                    <thead>
                                        <tr>
                                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                                <?php echo e(_i('IP')); ?>

                                            </th>
                                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                                <?php echo e(_i('Quantity')); ?>

                                            </th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                        <?php echo e(_i('Close')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
