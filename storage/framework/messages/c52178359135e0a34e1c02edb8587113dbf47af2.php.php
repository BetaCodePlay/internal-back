<div class="modal fade" id="move-agents-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(_i('Move agents')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo e(route('agents.move-agent')); ?>" id="move-agent-form" method="post">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="noty_bar noty_type__warning noty_theme__unify--v1--dark g-mb-25">
                                <div class="noty_body">
                                    <div class="g-mr-20">
                                        <div class="noty_body__icon">
                                            <i class="hs-admin-info"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <p>
                                            <?php echo e(_i('This tool allows you to move the one agent to another agent')); ?>

                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="user" class="user">
                        <div class="col-12 col-sm-6">
                            <div class="form-group">
                                <label for="agents"><?php echo e(_i('Agents')); ?></label>
                                <select name="agent" id="relocation-agents" class="form-control" style="width: 100%" data-route="<?php echo e(route('agents.relocation-agents-data')); ?>">
                                    <option value=""><?php echo e(_i('Select...')); ?></option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn u-btn-3d u-btn-primary u-btn-3d" id="move-agent-button" data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Please wait...')); ?>">
                        <?php echo e(_i('Move')); ?>

                    </button>
                    <button type="button" class="btn u-btn-3d u-btn-bluegray u-btn-3d" data-dismiss="modal">
                        <?php echo e(_i('Close')); ?>

                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
