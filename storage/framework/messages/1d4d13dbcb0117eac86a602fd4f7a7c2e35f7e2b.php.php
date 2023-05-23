

<?php $__env->startSection('content'); ?>
    <div class="card g-brd-gray-light-v7 g-rounded-4 g-mb-30">
        <header
            class="card-header g-bg-transparent g-brd-gray-light-v7 g-px-15 g-pt-15 g-pt-20--sm g-pb-10 g-pb-15--sm">
            <div class="media">
                <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0">
                    <?php echo e($title); ?>

                </h3>
                <div class="media-body d-flex justify-content-end">
                    <a href="<?php echo e(url()->previous()); ?>" class="btn u-btn-3d u-btn-primary float-right">
                        <i class="hs-admin-layout-list-thumb"></i>
                        <?php echo e(_i('Go to list')); ?>

                    </a>
                </div>
            </div>
        </header>
        <div class="card-block g-pa-15">
            <form action="<?php echo e(route('configurations.credentials.update')); ?>" id="posts-form" method="post">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="client"><?php echo e(_i('Whitelabel')); ?></label>
                            <input type="text" readonly="readonly" class="form-control" value="<?php echo e($whitelabels->description); ?>" />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="currency"><?php echo e(_i('Currency')); ?></label>
                            <input type="text" readonly="readonly" class="form-control" value="<?php echo e($last_currency); ?>" />
                        </div>
                    </div>
                     <div class="col-md-3">
                        <div class="form-group">
                            <label for="percentage"><?php echo e(_i('Percentage')); ?></label>
                            <input type="text" name="percentage" id="percentage" class="form-control">
                        </div>
                    </div>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$andes_sportbook): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="credential"><?php echo e(_i('Client token')); ?></label>
                                <input type="text" name="client_token" id="client_token" class="form-control" value="<?php echo e(isset($credentials->data->client_token) ? $credentials->data->client_token : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$vls || $provider == \Dotworkers\Configurations\Enums\Providers::$color_spin): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="credential"><?php echo e(_i('Client token')); ?></label>
                                <input type="text" name="client_token" id="client_token" class="form-control" value="<?php echo e(isset($credentials->data->client_token) ? $credentials->data->client_token : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$betpay): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="client_credentials_grant_id"><?php echo e(_i('Client credentials grant ID')); ?></label>
                                <input type="text" name="client_credentials_grant_id" id="client_credentials_grant_id" class="form-control" value="<?php echo e(isset($credentials->data->client_credentials_grant_id) ? $credentials->data->client_credentials_grant_id : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="client_credentials_grant_secret"><?php echo e(_i('Client credentials grant secret')); ?></label>
                                <input type="text" name="client_credentials_grant_secret" id="client_credentials_grant_secret" class="form-control" value="<?php echo e(isset($credentials->data->client_credentials_grant_secret) ? $credentials->data->client_credentials_grant_secret : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="password_grant_id"><?php echo e(_i('Password grant ID')); ?></label>
                                <input type="text" name="password_grant_id" id="password_grant_id" class="form-control" value="<?php echo e(isset($credentials->data->password_grant_id) ? $credentials->data->password_grant_id : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="password_grant_secret"><?php echo e(_i('Password grant secret')); ?></label>
                                <input type="text" name="password_grant_secret" id="password_grant_secret" class="form-control" value="<?php echo e(isset($credentials->data->password_grant_secret) ? $credentials->data->password_grant_secret : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$caleta_gaming || $provider == \Dotworkers\Configurations\Enums\Providers::$one_touch): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="operator_id"><?php echo e(_i('Operator ID')); ?></label>
                                <input type="text" name="operator_id" id="operator_id" class="form-control" value="<?php echo e(isset($credentials->data->operator_id) ? $credentials->data->operator_id : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$center_horses): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="credential"><?php echo e(_i('Client token')); ?></label>
                                <input type="text" name="client_token" id="client_token" class="form-control" value="<?php echo e(isset($credentials->data->client_token) ? $credentials->data->client_token : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$platipus): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="api_key"><?php echo e(_i('Api key')); ?></label>
                                <input type="text" name="api_key" id="api_key" class="form-control" value="<?php echo e($credentials->data->api_key); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$inmejorable): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="api_key"><?php echo e(_i('Api key')); ?></label>
                                <input type="text" name="api_key" id="api_key" class="form-control" value="<?php echo e(isset($credentials->data->api_key) ? $credentials->data->api_key : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="url"><?php echo e(_i('Url')); ?></label>
                                <input type="text" name="url" id="url" class="form-control" value="<?php echo e($credentials->data->url); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$ezugi  || $provider == \Dotworkers\Configurations\Enums\Providers::$evolution || $provider == \Dotworkers\Configurations\Enums\Providers::$evolution_slots): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="operator_id"><?php echo e(_i('Operator ID')); ?></label>
                                <input type="text" name="operator_id" id="operator_id" class="form-control" value="<?php echo e(isset($credentials->data->operator_id) ? $credentials->data->operator_id : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="secret_key"><?php echo e(_i('Secret key')); ?></label>
                                <input type="text" name="secret_key" id="secret_key" class="form-control" value="<?php echo e(isset($credentials->data->secret_key) ? $credentials->data->secret_key : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$golden_race || $provider == \Dotworkers\Configurations\Enums\Providers::$spinmatic): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="private_key"><?php echo e(_i('Private key')); ?></label>
                                <input type="text" name="private_key" id="private_key" class="form-control" value="<?php echo e(isset($credentials->data->private_key) ? $credentials->data->private_key : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$lega_jackpot): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="site"><?php echo e(_i('Site')); ?></label>
                                <input type="text" name="site" id="site" class="form-control" value="<?php echo e(isset($credentials->data->site) ? $credentials->data->site : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$lucky_spins): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="operator_id"><?php echo e(_i('Operator ID')); ?></label>
                                <input type="text" name="operator_id" id="operator_id" class="form-control" value="<?php echo e(isset($credentials->data->operator_id) ? $credentials->data->operator_id : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="secret_key"><?php echo e(_i('Secret key')); ?></label>
                                <input type="text" name="secret_key" id="secret_key" class="form-control" value="<?php echo e(isset($credentials->data->secret_key) ? $credentials->data->secret_key : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$ocb_slots): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="bank_group"><?php echo e(_i('Bank group')); ?></label>
                                <input type="text" name="bank_group" id="bank_group" class="form-control" value="<?php echo e(isset($credentials->data->bank_group) ? $credentials->data->bank_group : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="restore_policy"><?php echo e(_i('Restore policy')); ?></label>
                                <select name="restore_policy" id="restore_policy" class="form-control">
                                    <?php if($credentials->data->restore_policy === "Create"): ?>
                                        <option value="Create" selected>Create</option>
                                        <option value="Last">Last</option>
                                        <option value="Restore">Restore</option>
                                    <?php elseif($credentials->data->restore_policy === "Last"): ?>
                                        <option value="Create">Create</option>
                                        <option value="Last" selected>Last</option>
                                        <option value="Restore">Restore</option>
                                    <?php else: ?>
                                        <option value="Create">Create</option>
                                        <option value="Last">Last</option>
                                        <option value="Restore" selected>Restore</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="start_balance"><?php echo e(_i('Start balance')); ?></label>
                                <input type="text" name="start_balance" id="start_balance" class="form-control" value="<?php echo e(isset($credentials->data->start_balance) ? $credentials->data->start_balance : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$salsa_gaming || $provider == \Dotworkers\Configurations\Enums\Providers::$patagonia || $provider == \Dotworkers\Configurations\Enums\Providers::$pg_soft || $provider == \Dotworkers\Configurations\Enums\Providers::$booongo || $provider == \Dotworkers\Configurations\Enums\Providers::$game_art  || $provider == \Dotworkers\Configurations\Enums\Providers::$booming_games || $provider == \Dotworkers\Configurations\Enums\Providers::$kiron_interactive || $provider == \Dotworkers\Configurations\Enums\Providers::$hacksaw_gaming || $provider == \Dotworkers\Configurations\Enums\Providers::$triple_cherry || $provider == \Dotworkers\Configurations\Enums\Providers::$espresso_games): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="pn"><?php echo e(_i('Pn')); ?></label>
                                <input type="text" name="pn" id="pn" class="form-control" value="<?php echo e(isset($credentials->data->pn) ? $credentials->data->pn : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="key"><?php echo e(_i('Key')); ?></label>
                                <input type="text" name="key" id="key" class="form-control" value="<?php echo e(isset($credentials->data->key) ? $credentials->data->key : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$sisvenprol): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="client_id"><?php echo e(_i('Client ID')); ?></label>
                                <input type="text" name="client_id" id="client_id" class="form-control" value="<?php echo e(isset($credentials->data->client_id) ? $credentials->data->client_id : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="client_secret"><?php echo e(_i('Client secret')); ?></label>
                                <input type="text" name="client_secret" id="client_secret" class="form-control" value="<?php echo e(isset($credentials->data->client_secret) ? $credentials->data->client_secret : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="intermediary_id"><?php echo e(_i('Intermediary ID')); ?></label>
                                <input type="text" name="intermediary_id" id="intermediary_id" class="form-control" value="<?php echo e(isset($credentials->data->intermediary_id) ? $credentials->data->intermediary_id : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$sportbook): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="credential"><?php echo e(_i('Client token')); ?></label>
                                <input type="text" name="client_token" id="client_token" class="form-control" value="<?php echo e(isset($credentials->data->client_token) ? $credentials->data->client_token : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$virtual_generation): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="private_key"><?php echo e(_i('Private key')); ?></label>
                                <input type="text" name="private_key" id="private_key" class="form-control" value="<?php echo e(isset($credentials->data->private_key) ? $credentials->data->private_key : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="merchant_code"><?php echo e(_i('Merchant code')); ?></label>
                                <input type="text" name="merchant_code" id="merchant_code" class="form-control" value="<?php echo e(isset($credentials->data->merchant_code) ? $credentials->data->merchant_code : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$vivo_gaming): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="operator_id"><?php echo e(_i('Operator ID')); ?></label>
                                <input type="text" name="operator_id" id="operator_id" class="form-control" value="<?php echo e(isset($credentials->data->operator_id) ? $credentials->data->operator_id : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="pass_key"><?php echo e(_i('Pass key')); ?></label>
                                <input type="text" name="pass_key" id="pass_key" class="form-control" value="<?php echo e(isset($credentials->data->pass_key) ? $credentials->data->pass_key : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="server_id"><?php echo e(_i('Server ID')); ?></label>
                                <input type="text" name="server_id" id="server_id" class="form-control" value="<?php echo e(isset($credentials->data->server_id) ? $credentials->data->server_id : ' '); ?>" >
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$wnet_games || $provider == \Dotworkers\Configurations\Enums\Providers::$veneto_sportbook): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="private_key"><?php echo e(_i('Private key')); ?></label>
                                <input type="text" name="private_key" id="private_key" class="form-control" value="<?php echo e(isset($credentials->data->private_key) ? $credentials->data->private_key : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$xlive): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="client_id"><?php echo e(_i('Client ID')); ?></label>
                                <input type="text" name="client_id" id="client_id" class="form-control" value="<?php echo e(isset($credentials->data->client_id) ? $credentials->data->client_id : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="client_secret"><?php echo e(_i('Client secret')); ?></label>
                                <input type="text" name="client_secret" id="client_secret" class="form-control" value="<?php echo e(isset($credentials->data->client_secret) ? $credentials->data->client_secret : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$tv_bet || $provider == \Dotworkers\Configurations\Enums\Providers::$event_bet): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="client_id"><?php echo e(_i('Client ID')); ?></label>
                                <input type="text" name="client_id" id="client_id" class="form-control" value="<?php echo e(isset($credentials->data->client_id) ? $credentials->data->client_id : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="secret_key"><?php echo e(_i('Secret key')); ?></label>
                                <input type="text" name="secret_key" id="secret_key" class="form-control" value="<?php echo e(isset($credentials->data->secret_key) ? $credentials->data->secret_key : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$ka_gaming): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="credential"><?php echo e(_i('Partner Name')); ?></label>
                                <input type="text" name="partner_name" id="partner_name" class="form-control" value="<?php echo e(isset($credentials->data->partner_name) ? $credentials->data->partner_name : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="credential"><?php echo e(_i('Partner Access Key')); ?></label>
                                <input type="text" name="partner_access_key" id="partner_access_key" class="form-control" value="<?php echo e(isset($credentials->data->partner_access_key) ? $credentials->data->partner_access_key : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$gamzix): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="credential"><?php echo e(_i('Code')); ?></label>
                                <input type="text" name="code" id="code" class="form-control" value="<?php echo e(isset($credentials->data->code) ? $credentials->data->code : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="credential"><?php echo e(_i('Code EGT')); ?></label>
                                <input type="text" name="code_egt" id="code_egt" class="form-control" value="<?php echo e(isset($credentials->data->code_egt) ? $credentials->data->code_egt : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$pragmatic_play): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="credential"><?php echo e(_i('Secure Login')); ?></label>
                                <input type="text" name="secure_login" id="secure_login" class="form-control"  value="<?php echo e(isset($credentials->data->secure_login) ? $credentials->data->secure_login : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="key"><?php echo e(_i('Key')); ?></label>
                                <input type="text" name="key" id="key" class="form-control" value="<?php echo e(isset($credentials->data->key) ? $credentials->data->key : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="url_launch"><?php echo e(_i('Launch URL')); ?></label>
                                <input type="text" name="url_launch" id="url_launch" class="form-control" value="<?php echo e(isset($credentials->data->url_launch) ? $credentials->data->url_launch : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="url_api"><?php echo e(_i('Url api')); ?></label>
                                <input type="text" name="url_api" id="url_api" class="form-control" value="<?php echo e(isset($credentials->data->url_api) ? $credentials->data->url_api : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$mascot_gaming): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="bank_group"><?php echo e(_i('Bank group')); ?></label>
                                <input type="text" name="bank_group" id="bank_group" class="form-control" value="<?php echo e(isset($credentials->data->bank_group) ? $credentials->data->bank_group : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="restore_policy"><?php echo e(_i('Restore policy')); ?></label>
                                <select name="restore_policy" id="restore_policy" class="form-control">
                                    <?php if($credentials->data->restore_policy === "Create"): ?>
                                        <option value="Create" selected>Create</option>
                                        <option value="Last">Last</option>
                                        <option value="Restore">Restore</option>
                                    <?php elseif($credentials->data->restore_policy === "Last"): ?>
                                        <option value="Create">Create</option>
                                        <option value="Last" selected>Last</option>
                                        <option value="Restore">Restore</option>
                                    <?php else: ?>
                                        <option value="Create">Create</option>
                                        <option value="Last">Last</option>
                                        <option value="Restore" selected>Restore</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="start_balance"><?php echo e(_i('Start balance')); ?></label>
                                <input type="text" name="start_balance" id="start_balance" class="form-control" value="<?php echo e(isset($credentials->data->start_balance) ? $credentials->data->start_balance : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$branka || $provider == \Dotworkers\Configurations\Enums\Providers::$branka_originals): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="operator_id"><?php echo e(_i('Public key')); ?></label>
                                <input type="text" name="public_key" id="public_key" class="form-control" value="<?php echo e(isset($credentials->data->public_key) ? $credentials->data->public_key : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="secret_key"><?php echo e(_i('Secret key')); ?></label>
                                <input type="text" name="secret_key" id="secret_key" class="form-control" value="<?php echo e(isset($credentials->data->secret_key) ? $credentials->data->secret_key : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$pragmatic_play_live_casino): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="credential"><?php echo e(_i('Secure Login')); ?></label>
                                <input type="text" name="secure_login" id="secure_login" class="form-control"  value="<?php echo e(isset($credentials->data->secure_login) ? $credentials->data->secure_login : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="url_launch"><?php echo e(_i('Launch URL')); ?></label>
                                <input type="text" name="url_launch" id="url_launch" class="form-control" value="<?php echo e(isset($credentials->data->url_launch) ? $credentials->data->url_launch : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="url_api"><?php echo e(_i('Url api')); ?></label>
                                <input type="text" name="url_api" id="url_api" class="form-control" value="<?php echo e(isset($credentials->data->url_api) ? $credentials->data->url_api : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$play_son): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="partner"><?php echo e(_i('Partner')); ?></label>
                                <input type="text" name="partner" id="partner" class="form-control" value="<?php echo e(isset($credentials->data->partner) ? $credentials->data->partner : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$triple_cherry_original): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="client_id"><?php echo e(_i('Client DI')); ?></label>
                                <input type="text" name="client_id" id="client_id" class="form-control" value="<?php echo e(isset($credentials->data->client_id) ? $credentials->data->client_id : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="client_secret"><?php echo e(_i('Client Secret')); ?></label>
                                <input type="text" name="client_secret" id="client_secret" class="form-control" value="<?php echo e(isset($credentials->data->client_secret) ? $credentials->data->client_secret : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="partner_id"><?php echo e(_i('Partner ID')); ?></label>
                                <input type="text" name="partner_id" id="partner_id" class="form-control" value="<?php echo e(isset($credentials->data->partner_id) ? $credentials->data->partner_id : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$belatra): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="casino_id"><?php echo e(_i('Casino ID')); ?></label>
                                <input type="text" name="casino_id" id="casino_id" class="form-control" value="<?php echo e(isset($credentials->data->casino_id) ? $credentials->data->casino_id : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="token"><?php echo e(_i('Token')); ?></label>
                                <input type="text" name="token" id="token" class="form-control" value="<?php echo e(isset($credentials->data->token) ? $credentials->data->token : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$mancala_gaming): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="brand_name"><?php echo e(_i('Brand Name')); ?></label>
                                <input type="text" name="brand_name" id="brand_name" class="form-control" value="<?php echo e(isset($credentials->data->brand_name) ? $credentials->data->brand_name : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="partnerID"><?php echo e(_i('Partner ID')); ?></label>
                                <input type="text" name="partnerID" id="partnerID" class="form-control" value="<?php echo e(isset($credentials->data->partnerID) ? $credentials->data->partnerID : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="api_key"><?php echo e(_i('Api Key')); ?></label>
                                <input type="text" name="api_key" id="api_key" class="form-control" value="<?php echo e(isset($credentials->data->api_key) ? $credentials->data->api_key : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$red_rake): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="operator_idl"><?php echo e(_i('Operator ID')); ?></label>
                                <input type="text" name="operator_id" id="operator_id" class="form-control" value="<?php echo e(isset($credentials->data->operator_id) ? $credentials->data->operator_id : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="pass_key"><?php echo e(_i('Pass key')); ?></label>
                                <input type="text" name="pass_key" id="pass_key" class="form-control" value="<?php echo e(isset($credentials->data->pass_key) ? $credentials->data->pass_key : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$wazdan): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="credential"><?php echo e(_i('Code')); ?></label>
                                <input type="text" name="code" id="code" class="form-control" value="<?php echo e(isset($credentials->data->code) ? $credentials->data->code : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="operator"><?php echo e(_i('Operator')); ?></label>
                                <input type="text" name="operator" id="operator" class="form-control" value="<?php echo e(isset($credentials->data->operator) ? $credentials->data->operator : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="license"><?php echo e(_i('License')); ?></label>
                                <input type="text" name="license" id="license" class="form-control" value="<?php echo e(isset($credentials->data->license) ? $credentials->data->license : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$telegram): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="channel"><?php echo e(_i('Channel')); ?></label>
                                <input type="text" name="channel" id="channel" class="form-control" value="<?php echo e(isset($credentials->data->channel) ? $credentials->data->channel : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="bot"><?php echo e(_i('Bot')); ?></label>
                                <input type="text" name="bot" id="bot" class="form-control" value="<?php echo e(isset($credentials->data->bot) ? $credentials->data->bot : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$booongo_original): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="credential"><?php echo e(_i('Project Name')); ?></label>
                                <input type="text" name="project_name" id="project_name" class="form-control" value="<?php echo e(isset($credentials->data->project_name) ? $credentials->data->project_name : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$universal_soft): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="operator_idl"><?php echo e(_i('ID')); ?></label>
                                <input type="text" name="id" id="id" class="form-control" value="<?php echo e(isset($credentials->data->id) ? $credentials->data->id : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$altenar): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="channel"><?php echo e(_i('Site ID')); ?></label>
                                <input type="text" name="site_id" id="site_id" class="form-control" value="<?php echo e(isset($credentials->data->site_id) ? $credentials->data->site_id : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="bot"><?php echo e(_i('Wallet Code')); ?></label>
                                <input type="text" name="wallet_code" id="wallet_code" class="form-control" value="<?php echo e(isset($credentials->data->wallet_code) ? $credentials->data->wallet_code : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                                <div class="form-group">
                                    <label for="path"><?php echo e(_i('Path')); ?></label>
                                    <input type="text" name="path" id="path" class="form-control" value="<?php echo e(isset($credentials->data->path) ? $credentials->data->path : ' '); ?>">
                                </div>
                            </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="url"><?php echo e(_i('Url')); ?></label>
                                <input type="text" name="url" id="url" class="form-control" value="<?php echo e(isset($credentials->data->url) ? $credentials->data->url : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$ortiz_gaming): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="operator_id"><?php echo e(_i('Operator ID')); ?></label>
                                <input type="text" name="operator_id" id="operator_id" class="form-control" value="<?php echo e(isset($credentials->data->operator_id) ? $credentials->data->operator_id : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="client_id"><?php echo e(_i('Client ID')); ?></label>
                                <input type="text" name="client_id" id="client_id" class="form-control" value="<?php echo e(isset($credentials->data->client_id) ? $credentials->data->client_id : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$evo_play): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="secret_key"><?php echo e(_i('Secret Key')); ?></label>
                                <input type="text" name="secret_key" id="secret_key" class="form-control" value="<?php echo e(isset($credentials->data->secret_key) ? $credentials->data->secret_key : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="project_id"><?php echo e(_i('Project ID')); ?></label>
                                <input type="text" name="project_id" id="project_id" class="form-control" value="<?php echo e(isset($credentials->data->project_id) ? $credentials->data->project_id : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$i_soft_bet): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="license_id"><?php echo e(_i('License ID')); ?></label>
                                <input type="text" name="license_id" id="license_id" class="form-control"  value="<?php echo e(isset($credentials->data->license_id) ? $credentials->data->license_id : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$urgent_games): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="casino_id"><?php echo e(_i('Casino ID')); ?></label>
                                <input type="text" name="casino_id" id="casino_id" class="form-control" value="<?php echo e(isset($credentials->data->casino_id) ? $credentials->data->casino_id : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="token"><?php echo e(_i('Token')); ?></label>
                                <input type="text" name="token" id="token" class="form-control" value="<?php echo e(isset($credentials->data->token) ? $credentials->data->token : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="key"><?php echo e(_i('Key')); ?></label>
                                <input type="text" name="key" id="key" class="form-control" value="<?php echo e(isset($credentials->data->key) ? $credentials->data->key : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$mohio): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="portalId"><?php echo e(_i('Portal Key')); ?></label>
                                <input type="text" name="portalId" id="portalId" class="form-control" value="<?php echo e(isset($credentials->data->portalId) ? $credentials->data->portalId : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="platformId"><?php echo e(_i('Platform ID')); ?></label>
                                <input type="text" name="platformId" id="platformId" class="form-control" value="<?php echo e(isset($credentials->data->platformId) ? $credentials->data->platformId : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$vibra): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="site"><?php echo e(_i('Site')); ?></label>
                                <input type="text" name="site_id" id="site_id" class="form-control" value="<?php echo e(isset($credentials->data->site_id) ? $credentials->data->site_id : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$fbm_gaming): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="casino_id"><?php echo e(_i('Casino ID')); ?></label>
                                <input type="text" name="casino_id" id="casino_id" class="form-control" value="<?php echo e(isset($credentials->data->casino_id) ? $credentials->data->casino_id : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$greentube): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="casino_id"><?php echo e(_i('Secret key')); ?></label>
                                <input type="text" name="secret_key" id="secret_key" class="form-control" value="<?php echo e(isset($credentials->data->secret_key) ? $credentials->data->secret_key : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="casino_id"><?php echo e(_i('Authorization')); ?></label>
                                <input type="text" name="authorization" id="authorization" class="form-control" value="<?php echo e(isset($credentials->data->authorization) ? $credentials->data->authorization : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$digitain): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="private_key"><?php echo e(_i('Private key')); ?></label>
                                <input type="text" name="private_key" id="private_key" class="form-control" value="<?php echo e(isset($credentials->data->private_key) ? $credentials->data->private_key : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="partner_id"><?php echo e(_i('Partner ID')); ?></label>
                                <input type="text" name="partner_id" id="partner_id" class="form-control" value="<?php echo e(isset($credentials->data->partner_id) ? $credentials->data->partner_id : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="url_script"><?php echo e(_i('URL Script')); ?></label>
                                <input type="text" name="url_script" id="url_script" class="form-control" value="<?php echo e(isset($credentials->data->url_script) ? $credentials->data->url_script : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$beter): ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="private_key"><?php echo e(_i('Private key')); ?></label>
                                <input type="text" name="private_key" id="private_key" class="form-control" value="<?php echo e(isset($credentials->data->private_key) ? $credentials->data->private_key : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="secret_key"><?php echo e(_i('Secret key')); ?></label>
                                <input type="text" name="secret_key" id="secret_key" class="form-control" value="<?php echo e(isset($credentials->data->secret_key) ? $credentials->data->secret_key : ' '); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="script"><?php echo e(_i('Script')); ?></label>
                                <input type="text" name="script" id="script" class="form-control" value="<?php echo e(isset($credentials->data->script) ? $credentials->data->script : ' '); ?>">
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="hidden" id="provider" name="provider" value="<?php echo e($provider); ?>">
                            <input type="hidden" id="last_client" name="last_client" value="<?php echo e($last_client); ?>">
                            <input type="hidden" id="last_currency" name="last_currency" value="<?php echo e($last_currency); ?>">
                            <button type="button" class="btn u-btn-3d u-btn-primary" id="update"
                                    data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Updating...')); ?>">
                                <i class="hs-admin-reload"></i>
                                <?php echo e(_i('Update credentials')); ?>

                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(function () {
            let configurations = new Configurations();
            configurations.update();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>