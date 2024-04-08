

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
                    <form action="<?php echo e(route('configurations.credentials.store')); ?>" id="save-form" method="post">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="client"><?php echo e(_i('Whitelabel')); ?></label>
                                    <input type="hidden" id="provider" name="provider" value="<?php echo e($provider); ?>">
                                    <select name="client" id="client" class="form-control">
                                        <option value=""><?php echo e(_i('Select...')); ?></option>
                                        <?php $__currentLoopData = $whitelabels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $whitelabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($whitelabel->id); ?>">
                                                <?php echo e($whitelabel->description); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="currencies"><?php echo e(_i('Currency')); ?></label>
                                    <select name="currencies[]" class="form-control" data-placeholder="<?php echo e(_i('Select...')); ?>" multiple>
                                        <?php $__currentLoopData = $currency_client; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $currency): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($currency->iso); ?>">
                                                <?php echo e($currency->iso == 'VEF' ? $free_currency->currency_name : $currency->iso); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="percentage"><?php echo e(_i('Percentage')); ?></label>
                                        <input type="text" name="percentage" id="percentage" class="form-control">
                                    </div>
                            </div>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$andes_sportbook || $provider == \Dotworkers\Configurations\Enums\Providers::$center_horses): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="credential"><?php echo e(_i('Client token')); ?></label>
                                        <input type="text" name="client_token" id="client_token" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$vls || $provider == \Dotworkers\Configurations\Enums\Providers::$color_spin || $provider == \Dotworkers\Configurations\Enums\Providers::$sportbook): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="credential"><?php echo e(_i('Client token')); ?></label>
                                        <input type="text" name="client_token" id="client_token" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$betpay): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="client_credentials_grant_id"><?php echo e(_i('Client credentials grant ID')); ?></label>
                                        <input type="text" name="client_credentials_grant_id" id="client_credentials_grant_id" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="client_credentials_grant_secret"><?php echo e(_i('Client credentials grant secret')); ?></label>
                                        <input type="text" name="client_credentials_grant_secret" id="client_credentials_grant_secret" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="password_grant_id"><?php echo e(_i('Password grant ID')); ?></label>
                                        <input type="text" name="password_grant_id" id="password_grant_id" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="password_grant_secret"><?php echo e(_i('Password grant secret')); ?></label>
                                        <input type="text" name="password_grant_secret" id="password_grant_secret" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$caleta_gaming || $provider == \Dotworkers\Configurations\Enums\Providers::$one_touch): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="operator_id"><?php echo e(_i('Operator ID')); ?></label>
                                        <input type="text" name="operator_id" id="operator_id" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$platipus): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="api_key"><?php echo e(_i('Api key')); ?></label>
                                        <input type="text" name="api_key" id="api_key" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$inmejorable): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="api_key"><?php echo e(_i('Api key')); ?></label>
                                        <input type="text" name="api_key" id="api_key" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="url"><?php echo e(_i('Url')); ?></label>
                                        <input type="text" name="url" id="url" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$ezugi  || $provider == \Dotworkers\Configurations\Enums\Providers::$evolution || $provider == \Dotworkers\Configurations\Enums\Providers::$evolution_slots || $provider == \Dotworkers\Configurations\Enums\Providers::$lucky_spins): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="operator_id"><?php echo e(_i('Operator ID')); ?></label>
                                        <input type="text" name="operator_id" id="operator_id" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="secret_key"><?php echo e(_i('Secret key')); ?></label>
                                        <input type="text" name="secret_key" id="secret_key" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$golden_race || $provider == \Dotworkers\Configurations\Enums\Providers::$spinmatic || $provider == \Dotworkers\Configurations\Enums\Providers::$wnet_games || $provider == \Dotworkers\Configurations\Enums\Providers::$wnet_games || $provider == \Dotworkers\Configurations\Enums\Providers::$veneto_sportbook): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="private_key"><?php echo e(_i('Private key')); ?></label>
                                        <input type="text" name="private_key" id="private_key" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$lega_jackpot): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="site"><?php echo e(_i('Site')); ?></label>
                                        <input type="text" name="site" id="site" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$ocb_slots): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="bank_group"><?php echo e(_i('Bank group')); ?></label>
                                        <input type="text" name="bank_group" id="bank_group" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="restore_policy"><?php echo e(_i('Restore policy')); ?></label>
                                        <select name="restore_policy" id="restore_policy" class="form-control">
                                            <option value=""><?php echo e(_i('Select...')); ?></option>
                                            <option value="Create">Create</option>
                                            <option value="Last">Last</option>
                                            <option value="Restore">Restore</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="start_balance"><?php echo e(_i('Start balance')); ?></label>
                                        <input type="text" name="start_balance" id="start_balance" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$salsa_gaming || $provider == \Dotworkers\Configurations\Enums\Providers::$patagonia || $provider == \Dotworkers\Configurations\Enums\Providers::$pg_soft || $provider == \Dotworkers\Configurations\Enums\Providers::$booongo || $provider == \Dotworkers\Configurations\Enums\Providers::$game_art  || $provider == \Dotworkers\Configurations\Enums\Providers::$booming_games || $provider == \Dotworkers\Configurations\Enums\Providers::$kiron_interactive || $provider == \Dotworkers\Configurations\Enums\Providers::$hacksaw_gaming || $provider == \Dotworkers\Configurations\Enums\Providers::$triple_cherry || $provider == \Dotworkers\Configurations\Enums\Providers::$espresso_games): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="pn"><?php echo e(_i('Pn')); ?></label>
                                        <input type="text" name="pn" id="pn" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="key"><?php echo e(_i('Key')); ?></label>
                                        <input type="text" name="key" id="key" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$sisvenprol): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="client_id"><?php echo e(_i('Client ID')); ?></label>
                                        <input type="text" name="client_id" id="client_id" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="client_secret"><?php echo e(_i('Client secret')); ?></label>
                                        <input type="text" name="client_secret" id="client_secret" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="intermediary_id"><?php echo e(_i('Intermediary ID')); ?></label>
                                        <input type="text" name="intermediary_id" id="intermediary_id" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$virtual_generation): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="private_key"><?php echo e(_i('Private key')); ?></label>
                                        <input type="text" name="private_key" id="private_key" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="merchant_code"><?php echo e(_i('Merchant code')); ?></label>
                                        <input type="text" name="merchant_code" id="merchant_code" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$vivo_gaming): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="operator_id"><?php echo e(_i('Operator ID')); ?></label>
                                        <input type="text" name="operator_id" id="operator_id" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="pass_key"><?php echo e(_i('Pass key')); ?></label>
                                        <input type="text" name="pass_key" id="pass_key" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="server_id"><?php echo e(_i('Server ID')); ?></label>
                                        <input type="text" name="server_id" id="server_id" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$xlive): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="client_id"><?php echo e(_i('Client ID')); ?></label>
                                        <input type="text" name="client_id" id="client_id" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="client_secret"><?php echo e(_i('Client secret')); ?></label>
                                        <input type="text" name="client_secret" id="client_secret" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$tv_bet || $provider == \Dotworkers\Configurations\Enums\Providers::$event_bet): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="client_id"><?php echo e(_i('Client ID')); ?></label>
                                        <input type="text" name="client_id" id="client_id" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="secret_key"><?php echo e(_i('Secret key')); ?></label>
                                        <input type="text" name="secret_key" id="secret_key" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$ka_gaming): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="credential"><?php echo e(_i('Partner Name')); ?></label>
                                        <input type="text" name="partner_name" id="partner_name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="credential"><?php echo e(_i('Partner Access Key')); ?></label>
                                        <input type="text" name="partner_access_key" id="partner_access_key" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$gamzix): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="credential"><?php echo e(_i('Code')); ?></label>
                                        <input type="text" name="code" id="code" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="credential"><?php echo e(_i('Code EGT')); ?></label>
                                        <input type="text" name="code_egt" id="code_egt" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$pragmatic_play): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="credential"><?php echo e(_i('Secure Login')); ?></label>
                                        <input type="text" name="secure_login" id="secure_login" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="key"><?php echo e(_i('Key')); ?></label>
                                        <input type="text" name="key" id="key" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="url_launch"><?php echo e(_i('Launch URL')); ?></label>
                                        <input type="text" name="url_launch" id="url_launch" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="url_api"><?php echo e(_i('Url api')); ?></label>
                                        <input type="text" name="url_api" id="url_api" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$mascot_gaming): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="bank_group"><?php echo e(_i('Bank group')); ?></label>
                                        <input type="text" name="bank_group" id="bank_group" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="restore_policy"><?php echo e(_i('Restore policy')); ?></label>
                                        <select name="restore_policy" id="restore_policy" class="form-control">
                                            <option value=""><?php echo e(_i('Select...')); ?></option>
                                            <option value="Create">Create</option>
                                            <option value="Last">Last</option>
                                            <option value="Restore">Restore</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="start_balance"><?php echo e(_i('Start balance')); ?></label>
                                        <input type="text" name="start_balance" id="start_balance" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$branka): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="operator_id"><?php echo e(_i('Public key')); ?></label>
                                        <input type="text" name="public_key" id="public_key" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="secret_key"><?php echo e(_i('Secret key')); ?></label>
                                        <input type="text" name="secret_key" id="secret_key" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$pragmatic_play_live_casino): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="credential"><?php echo e(_i('Secure login')); ?></label>
                                        <input type="text" name="secure_login" id="secure_login" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="url_launch"><?php echo e(_i('Launch URL')); ?></label>
                                        <input type="text" name="url_launch" id="url_launch" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="url_api"><?php echo e(_i('Url api')); ?></label>
                                        <input type="text" name="url_api" id="url_api" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$play_son): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="partner"><?php echo e(_i('Partner')); ?></label>
                                        <input type="text" name="partner" id="partner" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$triple_cherry_original): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="client_id"><?php echo e(_i('Client DI')); ?></label>
                                        <input type="text" name="client_id" id="client_id" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="client_secret"><?php echo e(_i('Client Secret')); ?></label>
                                        <input type="text" name="client_secret" id="client_secret" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="partner_id"><?php echo e(_i('Partner ID')); ?></label>
                                        <input type="text" name="partner_id" id="partner_id" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$belatra): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="casino_id"><?php echo e(_i('Casino ID')); ?></label>
                                        <input type="text" name="casino_id" id="casino_id" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="token"><?php echo e(_i('Token')); ?></label>
                                        <input type="text" name="token" id="token" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$mancala_gaming): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="brand_name"><?php echo e(_i('Brand Name')); ?></label>
                                        <input type="text" name="brand_name" id="brand_name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="partnerID"><?php echo e(_i('Partner ID')); ?></label>
                                        <input type="text" name="partnerID" id="partnerID" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="api_key"><?php echo e(_i('Api Key')); ?></label>
                                        <input type="text" name="api_key" id="api_key" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$wazdan): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="credential"><?php echo e(_i('Code')); ?></label>
                                        <input type="text" name="code" id="code" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="operator"><?php echo e(_i('Operator')); ?></label>
                                        <input type="text" name="operator" id="operator" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="license"><?php echo e(_i('License')); ?></label>
                                        <input type="text" name="license" id="license" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$red_rake): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="operator_idl"><?php echo e(_i('Operator ID')); ?></label>
                                        <input type="text" name="operator_id" id="operator_id" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="pass_key"><?php echo e(_i('Pass key')); ?></label>
                                        <input type="text" name="pass_key" id="pass_key" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$booongo_original): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="credential"><?php echo e(_i('Project Name')); ?></label>
                                        <input type="text" name="project_name" id="project_name" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$universal_soft): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="operator_idl"><?php echo e(_i('ID')); ?></label>
                                        <input type="text" name="id" id="id" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$altenar): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="channel"><?php echo e(_i('Site ID')); ?></label>
                                        <input type="text" name="site_id" id="site_id" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="bot"><?php echo e(_i('Wallet Code')); ?></label>
                                        <input type="text" name="wallet_code" id="wallet_code" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="path"><?php echo e(_i('Path')); ?></label>
                                        <input type="text" name="path" id="path" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="url"><?php echo e(_i('Url')); ?></label>
                                        <input type="text" name="url" id="url" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$ortiz_gaming): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="operator_id"><?php echo e(_i('Operator ID')); ?></label>
                                        <input type="text" name="operator_id" id="operator_id" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="client_id"><?php echo e(_i('Client ID')); ?></label>
                                        <input type="text" name="client_id" id="client_id" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$evo_play): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="secret_key"><?php echo e(_i('Secret Key')); ?></label>
                                        <input type="text" name="secret_key" id="secret_key" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="project_id"><?php echo e(_i('Project ID')); ?></label>
                                        <input type="text" name="project_id" id="project_id" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$i_soft_bet): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="license_id"><?php echo e(_i('License ID')); ?></label>
                                        <input type="text" name="license_id" id="license_id" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$urgent_games): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="casino_id"><?php echo e(_i('Casino ID')); ?></label>
                                        <input type="text" name="casino_id" id="casino_id" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="token"><?php echo e(_i('Token')); ?></label>
                                        <input type="text" name="token" id="token" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="key"><?php echo e(_i('key')); ?></label>
                                        <input type="text" name="key" id="key" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$mohio): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="portalId"><?php echo e(_i('Portal Key')); ?></label>
                                        <input type="text" name="portalId" id="portalId" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="platformId"><?php echo e(_i('Platform ID')); ?></label>
                                        <input type="text" name="platformId" id="platformId" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$vibra): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="channel"><?php echo e(_i('Site ID')); ?></label>
                                        <input type="text" name="site_id" id="site_id" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$fbm_gaming): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="channel"><?php echo e(_i('Casino ID')); ?></label>
                                        <input type="text" name="casino_id" id="casino_id" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$greentube): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="channel"><?php echo e(_i('Secret key')); ?></label>
                                        <input type="text" name="secret_key" id="secret_key" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="channel"><?php echo e(_i('Authorization')); ?></label>
                                        <input type="text" name="authorization" id="authorization" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$digitain): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="private_key"><?php echo e(_i('Private key')); ?></label>
                                        <input type="text" name="private_key" id="private_key" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="partner_id"><?php echo e(_i('Partner ID')); ?></label>
                                        <input type="text" name="partner_id" id="partner_id" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="url_script"><?php echo e(_i('URL Script')); ?></label>
                                        <input type="text" name="url_script" id="url_script" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($provider == \Dotworkers\Configurations\Enums\Providers::$beter): ?>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="private_key"><?php echo e(_i('Private key')); ?></label>
                                        <input type="text" name="private_key" id="private_key" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="secret_key"><?php echo e(_i('Secret key')); ?></label>
                                        <input type="text" name="secret_key" id="secret_key" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="script"><?php echo e(_i('Script')); ?></label>
                                        <input type="text" name="script" id="script" class="form-control">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button type="button" class="btn u-btn-3d u-btn-primary" id="save"
                                            data-loading-text="<i class='fa fa-spin fa-spinner'></i> <?php echo e(_i('Saving...')); ?>">
                                        <i class="hs-admin-save"></i>
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
                        <h3 class="d-flex text-uppercase g-font-size-12 g-font-size-default--md g-color-black g-mr-10 mb-0 title">
                            <?php echo e(_i('Whitelabels')); ?>

                        </h3>
                        <div class="media-body d-flex justify-content-end" id="table-buttons">

                        </div>
                        <div class="justify-content-end g-ml-10" style="padding-left: 10px">
                            <button class="btn g-bg-primary" type="button" id="update-credential"
                                    data-loading-text="<i class='fa fa-spin fa-refresh g-color-white'></i>">
                                <i class="hs-admin-reload g-color-white"></i>
                            </button>
                        </div>
                    </div>
                </header>
                <div class="card-block g-pa-15">
                    <table class="table table-bordered table-responsive-sm w-100" id="credential-table" data-route="<?php echo e(route('configurations.credentials.data')); ?>">
                        <thead>
                        <tr>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Whitelabel')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Credentials')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Currency')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Percentage')); ?>

                            </th>
                            <th class="g-font-weight-600 g-color-gray-dark-v6 g-brd-top-none">
                                <?php echo e(_i('Status')); ?>

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
            let configurations = new Configurations();
            configurations.save();
            configurations.credentials();
            $(document).on('click', '.update_checkbox', function () {
                if (!$(this).hasClass('active')) {
                    $.post('<?php echo e(route('configurations.credentials.status')); ?>', {client_id: $(this).data('id'),  name: 'status', value: true}, function () {});
                } else {
                    $.post('<?php echo e(route('configurations.credentials.status')); ?>', {client_id: $(this).data('id'),  name: 'status', value: false}, function () {});
                }

                $(this).toggleClass('active');
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('back.template', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>