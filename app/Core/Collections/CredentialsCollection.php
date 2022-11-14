<?php


namespace App\Core\Collections;

use Dotworkers\Configurations\Enums\Providers;
use Dotworkers\Configurations\Enums\ProviderTypes;
use App\Core\Repositories\ProvidersRepo;

/**
 * Class CredentialsCollection
 *
 * This class allows to format credentials data
 *
 * @package App\Core\Collections
 * @author  Orlando
 */
class CredentialsCollection
{
    /**
     * Format dot suite
     *
     * @param $credentials
     * @return array[]
     */
    public function formatDotSuite($credentials)
    {
        $credentialsData = [];
        foreach ($credentials as $credential) {
            if ($credential->data == [] || $credential->provider_id == Providers::$dot_suite && isset($credential->data->client_credentials_grant_secret)) {
                $credential->client = $credential->client_name;
                $credential->provider_name = Providers::getName($credential->provider_id);
                $percentage =  $credential->percentage * 100;
                $credential->percentage_credential = $percentage;
                if ($credential->data == []) {
                    $credential->credential =  '';
                } else {
                    $credential->credential = sprintf(
                        '<ul><li><strong>%s</strong>%s%s</li></ul>',
                        _i('Client grant secret'),
                        ': ',
                        $credential->data->client_credentials_grant_secret,
                    );
                }
                if ($credential->status) {
                    $status = 0;
                } else {
                    $status = 1;
                }
                $statusClass = $credential->status ? 'lightred' : 'teal';
                $statusText = $credential->status ? _i('Deactivate') : _i('Activate');
                $credential->actions =  sprintf(
                    '<button type="button" class="btn u-btn-primary g-bg-%s mr-2 status" data-route="%s"> %s</button>',
                    $statusClass,
                    route('dot-suite.credentials.status',[$credential->client_id, $credential->provider_id, $credential->currency_iso, $status]),
                    $statusText
                );
                $credential->status_data = $credential->status ? _i('Active') : _i('Inactive');
                $credentialsData[] = $credential;
            }
         }
        $data = [
            'credentials' => $credentialsData
        ];
        return $data;
    }

    /**
     * Format search
     * @param integer $provider Provider ID
     * @param array $credentials Credentials data
     */
    public function formatSearch($credentials, $provider)
    {
        foreach ($credentials as $credential) {
            $data ="";
            if(($credential->provider_id!==Providers::$dlv) && ($credential->provider_id!==Providers::$iq_soft)){
                $credential->client = sprintf(
                    '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm">%s</a>',
                    route('configurations.credentials.details', [ $credential->client_id, $credential->provider_id, $credential->currency_iso]),
                    $credential->client
                );
            }else{
                $credential->client;
            }
           $percentage = ($credential->percentage == 0.0 || is_null($credential->percentage) ) ? 0 : ($credential->percentage * 100);
           $credential->percentage = number_format($percentage,2);
           $credential->actions = sprintf(
               '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" id="delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
               route('configurations.credentials.delete',[$credential->client_id, $credential->provider_id, $credential->currency_iso]),
               _i('Delete')
           );
            $data = $credential->client_id."|".$credential->provider_id."|".$credential->currency_iso;
            $credential->status = sprintf(
                '<div class="checkbox checkbox-primary">
                          <input class="update_checkbox %s" id="status_%s" value="" type="checkbox" %s data-id="%s" data-name="status" data-url="" />
                                            <label for="status_%s">&nbsp;</label>
                    </div>', ($credential->status ? 'active' : ''), $data,  ($credential->status ? 'checked' : ''), $data, $data
            );
            switch ($provider) {
                case Providers::$pragmatic_play_live_casino:
                {
                    if(!is_null($credential->data)){
                        if((isset($credential->data->secure_login)) && (isset($credential->data->url_launch)) && (isset($credential->data->url_api))){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Secure login'),
                                ': ',
                                $credential->data->secure_login,
                                _i('Launch URL'),
                                ': ',
                                $credential->data->url_launch,
                                _i('API URL'),
                                ': ',
                                $credential->data->url_api
                            );
                        } else {
                            $credential->setting = '';
                        }
                    } else {
                        $credential->setting = '';
                    }
                   break;
                }
                case Providers::$play_son:
                {
                    if(!is_null($credential->data)){
                        if(isset($credential->data->partner)){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Partner'),
                                ': ',
                                $credential->data->partner
                            );
                        }else{
                            $credential->setting = ' ';
                        }
                    }else{
                        $credential->setting = ' ';
                    }
                    break;
                }
                case Providers::$triple_cherry_original:
                {
                    if(!is_null($credential->data)){
                        if((isset($credential->data->client_id)) && (isset($credential->data->client_secret)) && (isset($credential->data->partner_id))){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Client ID'),
                                ': ',
                                $credential->data->client_id,
                                _i('Client Secret'),
                                ': ',
                                $credential->data->client_secret,
                                _i('Partner ID'),
                                ': ',
                                $credential->data->partner_id
                            );
                        }else{
                            $credential->setting = ' ';
                        }
                    }else{
                        $credential->setting = ' ';
                    }
                    break;
                }
                case Providers::$mancala_gaming:
                {
                    if(!is_null($credential->data)){
                        if((isset($credential->data->brand_name)) && (isset($credential->data->partnerID)) && (isset($credential->data->api_key))){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Brand Name'),
                                ': ',
                                $credential->data->brand_name,
                                _i('Partner ID'),
                                ': ',
                                $credential->data->partnerID,
                                _i('Api Key'),
                                ': ',
                                $credential->data->api_key
                            );
                        }else{
                            $credential->setting = ' ';
                        }
                    }else{
                        $credential->setting = ' ';
                    }
                    break;
                }
                case Providers::$wazdan:
                {
                    if(!is_null($credential->data)){
                        if((isset($credential->data->code)) && (isset($credential->data->operator)) && (isset($credential->data->license))){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Code'),
                                ': ',
                                $credential->data->code,
                                _i('Operator'),
                                ': ',
                                $credential->data->operator,
                                _i('License'),
                                ': ',
                                $credential->data->license
                            );
                        }else{
                            $credential->setting = ' ';
                        }
                    }else{
                        $credential->setting = ' ';
                    }
                    break;
                }
                case Providers::$red_rake:
                {
                    if(!is_null($credential->data)){
                        if((isset($credential->data->operator_id)) && (isset($credential->data->pass_key))){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Operator ID'),
                                ': ',
                                $credential->data->operator_id,
                                _i('Pass key'),
                                ': ',
                                $credential->data->pass_key
                            );
                        }else{
                            $credential->setting = ' ';
                        }
                    }else{
                        $credential->setting = ' ';
                    }
                    break;
                }
                case Providers::$belatra:
                {
                    if(!is_null($credential->data)){
                        if((isset($credential->data->casino_id)) && (isset($credential->data->token))){
                        $credential->setting = sprintf(
                            '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                            _i('Casino ID'),
                            ': ',
                            $credential->data->casino_id,
                            _i('Token'),
                            ': ',
                            $credential->data->token
                        );
                        }else{
                            $credential->setting = '';
                        }
                    }else{
                        $credential->setting = '';
                    }
                    break;
                }
                case Providers::$telegram:
                {
                    if(!is_null($credential->data)){
                        if((isset($credential->data->channel)) && (isset($credential->data->bot))){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Channel'),
                                ': ',
                                $credential->data->channel,
                                _i('Bot'),
                                ': ',
                                $credential->data->bot
                            );
                        }else{
                            $credential->setting = '';
                        }
                    }else{
                        $credential->setting = '';
                    }
                    break;
                }
                case Providers::$universal_soft:
                {
                    if(!is_null($credential->data)){
                        if(isset($credential->data->id)){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li></ul>',
                                _i('ID'),
                                ': ',
                                $credential->data->id,
                            );
                        }else{
                            $credential->setting = '';
                        }
                    }else{
                        $credential->setting = '';
                    }
                    break;
                }
                case Providers::$altenar:
                {
                    if(!is_null($credential->data)){
                        if( (isset($credential->data->site_id)) && (isset($credential->data->wallet_code)) && (isset($credential->data->path)) && (isset($credential->data->url))){
                        $credential->setting = sprintf(
                            '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                            _i('Site ID'),
                            ': ',
                            $credential->data->site_id,
                            _i('Wallet Code'),
                            ': ',
                            $credential->data->wallet_code,
                            _i('Path'),
                            ': ',
                            $credential->data->path,
                            _i('Url'),
                            ': ',
                            $credential->data->url
                        );
                        }else{
                            $credential->setting ="";
                        }
                    }else{
                        $credential->setting ="";
                    }
                    break;
                }
                case Providers::$booongo_original:
                {
                    if(!is_null($credential->data)){
                        if(isset($credential->data->project_name)){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Project Name'),
                                ': ',
                                $credential->data->project_name
                            );
                        }else{
                            $credential->setting ="";
                        }
                    }else{
                        $credential->setting ="";
                    }
                    break;
                }
                case Providers::$evo_play:
                {
                    if(!is_null($credential->data)){
                        if( (isset($credential->data->secret_key)) && (isset($credential->data->project_id))){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Secret Key'),
                                ': ',
                                $credential->data->secret_key,
                                _i('Project ID'),
                                ': ',
                                $credential->data->project_id
                            );
                        }else{
                            $credential->setting ="";
                        }
                    }else{
                        $credential->setting ="";
                    }
                    break;
                }
                case Providers::$i_soft_bet:
                {
                    if(!is_null($credential->data)){
                        if(isset($credential->data->license_id)){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li></ul>',
                                _i('License ID'),
                                ': ',
                                $credential->data->license_id
                            );
                        }else{
                            $credential->setting ="";
                        }
                    }else{
                        $credential->setting ="";
                    }
                    break;
                }
                case Providers::$urgent_games:
                {
                    if(!is_null($credential->data)){
                        if( (isset($credential->data->casino_id)) && (isset($credential->data->token))&& (isset($credential->data->key))){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Casino ID'),
                                ': ',
                                $credential->data->casino_id,
                                _i('Token'),
                                ': ',
                                $credential->data->token,
                                _i('Key'),
                                ': ',
                                $credential->data->key
                            );
                        }else{
                            $credential->setting ="";
                        }
                    }else{
                        $credential->setting ="";
                    }
                    break;
                }
                case Providers::$mohio:
                {
                    if(!is_null($credential->data)){
                        if( (isset($credential->data->portalId)) && (isset($credential->data->platformId))){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Portal ID'),
                                ': ',
                                $credential->data->portalId,
                                _i('Platform ID'),
                                ': ',
                                $credential->data->platformId
                            );
                        }else{
                            $credential->setting ="";
                        }
                    }else{
                        $credential->setting ="";
                    }
                    break;
                }
                case Providers::$betpay:
                {
                    if(!is_null($credential->data)){
                        if((isset($credential->data->client_credentials_grant_id)) && (isset($credential->data->client_credentials_grant_secret)) && (isset($credential->data->password_grant_id)) && (isset($credential->data->password_grant_secret))){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Client credentials grant ID'),
                                ': ',
                                $credential->data->client_credentials_grant_id,
                                _i('Client credentials grant secret'),
                                ': ',
                                $credential->data->client_credentials_grant_secret,
                                _i('Password grant ID'),
                                ': ',
                                $credential->data->password_grant_id,
                                _i('Password grant secret'),
                                ': ',
                                $credential->data->password_grant_secret
                            );
                        }else{
                            $credential->setting ="";
                        }
                    }else{
                        $credential->setting ="";
                    }
                    break;
                }
                case Providers::$vivo_gaming:
                {
                    if(!is_null($credential->data)){
                        if((isset($credential->data->operator_id)) && (isset($credential->data->pass_key)) && (isset($credential->data->server_id))){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Operator ID'),
                                ': ',
                                $credential->data->operator_id,
                                _i('Pass key'),
                                ': ',
                                $credential->data->pass_key,
                                _i('Server ID'),
                                ': ',
                                $credential->data->server_id,
                            );
                        }else{
                            $credential->setting ="";
                        }
                    }else{
                        $credential->setting ="";
                    }
                    break;
                }
                case Providers::$caleta_gaming:
                case Providers::$one_touch:
                {
                    if(!is_null($credential->data)){
                        if(isset($credential->data->operator_id)){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Operator ID'),
                                ': ',
                                $credential->data->operator_id
                            );
                        }else{
                            $credential->setting ="";
                        }
                    }else{
                        $credential->setting ="";
                    }
                    break;
                }
                case Providers::$ortiz_gaming:
                {
                    if(!is_null($credential->data)){
                        if((isset($credential->data->operator_id)) && (isset($credential->data->client_id))){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Operator ID'),
                                ': ',
                                $credential->data->operator_id,
                                _i('Client ID'),
                                ': ',
                                $credential->data->client_id
                            );
                        }else{
                            $credential->setting ="";
                        }
                    }else{
                        $credential->setting ="";
                    }
                    break;
                }
                case Providers::$sisvenprol:
                {
                    if(!is_null($credential->data)){
                        if((isset($credential->data->client_id)) && (isset($credential->data->client_secret)) && (isset($credential->data->intermediary_id))){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Client ID'),
                                ': ',
                                $credential->data->client_id,
                                _i('Client secret'),
                                ': ',
                                $credential->data->client_secret,
                                _i('Intermediary ID'),
                                ': ',
                                $credential->data->intermediary_id
                            );
                        }else{
                            $credential->setting ="";
                        }
                    }else{
                        $credential->setting ="";
                    }
                    break;
                }
                case Providers::$xlive:
                {
                    if(!is_null($credential->data)){
                        if((isset($credential->data->client_id)) && (isset($credential->data->client_secret))){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Client ID'),
                                ': ',
                                $credential->data->client_id,
                                _i('Client secret'),
                                ': ',
                                $credential->data->client_secret
                            );
                        }else{
                            $credential->setting ="";
                        }
                    }else{
                        $credential->setting ="";
                    }
                    break;
                }
                case Providers::$lega_jackpot:
                {
                    if(!is_null($credential->data)){
                        if(isset($credential->data->site)){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Site'),
                                ': ',
                                $credential->data->site
                            );
                        }else{
                            $credential->setting ="";
                        }
                    }else{
                        $credential->setting ="";
                    }
                    break;
                }
                case Providers::$inmejorable:{
                    $credential->setting = sprintf(
                        '<ul><li><strong>%s</strong>%s%s</li></ul>',
                        _i('Api key'),
                        ': ',
                        $credential->data->api_key
                    );
                    if(isset($credential->data->url)){
                        $credential->setting .= sprintf(
                            '<ul><li><strong>%s</strong>%s%s</li></ul>',
                            _i('Url'),
                            ': ',
                            $credential->data->url
                        );
                    }
                    if(!is_null($credential->data)){
                        if(isset($credential->data->api_key)){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Api key'),
                                ': ',
                                $credential->data->api_key
                            );
                        }else{
                            $credential->setting ="";
                        }
                        if(isset($credential->data->url)){
                            $credential->setting .= sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Url'),
                                ': ',
                                $credential->data->url
                            );
                        }else{
                            $credential->setting ="";
                        }
                    }else{
                        $credential->setting ="";
                    }
                    break;
                }
                case Providers::$platipus:
                {
                    $credential->setting = sprintf(
                        '<ul><li><strong>%s</strong>%s%s</li></ul>',
                        _i('Api key'),
                        ': ',
                        $credential->data->api_key
                    );
                    if(!is_null($credential->data)){
                        if(isset($credential->data->api_key)){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Api key'),
                                ': ',
                                $credential->data->api_key
                            );
                        }else{
                            $credential->setting ="";
                        }
                    }else{
                        $credential->setting ="";
                    }
                    break;
                }
                case Providers::$virtual_generation:
                {
                    if(!is_null($credential->data)){
                        if((isset($credential->data->private_key)) && (isset($credential->data->merchant_code))){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Private key'),
                                ': ',
                                $credential->data->private_key,
                                _i('Merchant code'),
                                ': ',
                                $credential->data->merchant_code
                            );
                        }else{
                            $credential->setting = '';
                        }
                    }else{
                        $credential->setting = '';
                    }
                    break;
                }
                case Providers::$ka_gaming:
                {
                    if(!is_null($credential->data)){
                        if((isset($credential->data->partner_name)) && (isset($credential->data->partner_access_key))){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Partner Name'),
                                ': ',
                                $credential->data->partner_name,
                                _i('Partner Access Key'),
                                ': ',
                                $credential->data->partner_access_key
                            );
                        }else{
                            $credential->setting = '';
                        }
                    }else{
                        $credential->setting = '';
                    }
                    break;
                }
                case Providers::$gamzix:
                {
                    if(!is_null($credential->data)){
                        if((isset($credential->data->code)) && (isset($credential->data->code_egt))){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Code'),
                                ': ',
                                $credential->data->code,
                                _i('Code EGT'),
                                ': ',
                                $credential->data->code_egt
                            );
                        }else{
                            $credential->setting = '';
                        }
                    }else{
                        $credential->setting = '';
                    }
                    break;
                }
                case Providers::$pragmatic_play:
                {
                    if(!is_null($credential->data)){
                        if((isset($credential->data->secure_login)) && (isset($credential->data->key)) && (isset($credential->data->url_launch)) && (isset($credential->data->url_api))){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Secure login'),
                                ': ',
                                $credential->data->secure_login,
                                _i('Key'),
                                ': ',
                                $credential->data->key,
                                _i('Launch URL'),
                                ': ',
                                $credential->data->url_launch,
                                _i('API URL'),
                                ': ',
                                $credential->data->url_api
                            );
                        }else{
                            $credential->setting = '';
                        }
                    }else{
                        $credential->setting = '';
                    }
                    break;
                }
                case Providers::$vibra:
                {
                    if(!is_null($credential->data)){
                        if(isset($credential->data->site_id)){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Site ID'),
                                ': ',
                                $credential->data->site_id
                            );
                        }else{
                            $credential->setting = '';
                        }
                    }else{
                        $credential->setting = '';
                    }
                    break;
                }
                case Providers::$fbm_gaming:
                {
                    if(!is_null($credential->data)){
                        if(isset($credential->data->casino_id)){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Casino ID'),
                                ': ',
                                $credential->data->casino_id
                            );
                        }else{
                            $credential->setting = '';
                        }
                    }else{
                        $credential->setting = '';
                    }
                    break;
                }
                case Providers::$greentube:
                {
                    if(!is_null($credential->data)){
                        if((isset($credential->data->secret_key)) && (isset($credential->data->authorization))){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Secret key'),
                                ': ',
                                $credential->data->secret_key,
                                _i('Authorization'),
                                ': ',
                                $credential->data->authorization
                            );
                        }else{
                            $credential->setting = '';
                        }
                    }else{
                        $credential->setting = '';
                    }
                    break;
                }
                case Providers::$ocb_slots:
                case Providers::$mascot_gaming:
                {
                    if(!is_null($credential->data)){
                        if((isset($credential->data->bank_group)) && (isset($credential->data->restore_policy)) && (isset($credential->data->start_balance))){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Bank group'),
                                ': ',
                                $credential->data->bank_group,
                                _i('Restore policy'),
                                ': ',
                                $credential->data->restore_policy,
                                _i('Start balance'),
                                ': ',
                                $credential->data->start_balance
                            );
                        }else{
                            $credential->setting = '';
                        }
                    }else{
                        $credential->setting = '';
                    }
                    break;
                }
                case Providers::$ezugi:
                case Providers::$lucky_spins:
                case Providers::$evolution_slots:
                case Providers::$evolution:
                {
                    if (isset($credential->data->operator_id) && isset($credential->data->secret_key)) {
                        $credential->setting = sprintf(
                            '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                            _i('Operator ID'),
                            ': ',
                            $credential->data->operator_id,
                            _i('Secret key'),
                            ': ',
                            $credential->data->secret_key
                        );
                    } else {
                        $credential->setting ="";
                    }
                    break;
                }
                case Providers::$dlv:
                case Providers::$iq_soft:
                case Providers::$live_player:
                case Providers::$sw3:
                case Providers::$kalamba:
                {
                    $credential->setting ="";
                    break;
                }
                case Providers::$golden_race:
                case Providers::$spinmatic:
                case Providers::$wnet_games:
                case Providers::$veneto_sportbook:
                {
                    if(!is_null($credential->data)){
                        if(isset($credential->data->private_key)){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Private key'),
                                ': ',
                                $credential->data->private_key
                            );
                        }else{
                            $credential->setting = '';
                        }
                    }else{
                        $credential->setting = '';
                    }
                    break;
                }
                case Providers::$tv_bet:
                case Providers::$event_bet:
                {
                    if(!is_null($credential->data)){
                        if((isset($credential->data->client_id)) && (isset($credential->data->secret_key))){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Client ID'),
                                ': ',
                                $credential->data->client_id,
                                _i('Secret Key'),
                                ': ',
                                $credential->data->secret_key
                            );
                        }else{
                            $credential->setting = '';
                        }
                    }else{
                        $credential->setting = '';
                    }
                    break;
                }
                case Providers::$center_horses:
                case Providers::$sportbook:
                case Providers::$vls:
                case Providers::$andes_sportbook:
                case Providers::$color_spin:
                {
                    if(!is_null($credential->data)){
                        if(isset($credential->data->client_token)){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Client token'),
                                ': ',
                                $credential->data->client_token
                            );
                        }else{
                            $credential->setting = '';
                        }
                    }else{
                        $credential->setting = '';
                    }
                    break;
                }
                case Providers::$branka:
                case Providers::$branka_originals:
                {
                    if(!is_null($credential->data)){
                        if((isset($credential->data->public_key)) && (isset($credential->data->secret_key))){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Public Key'),
                                ': ',
                                $credential->data->public_key,
                                _i('Secret Key'),
                                ': ',
                                $credential->data->secret_key
                            );
                        }else{
                            $credential->setting = '';
                        }
                    }else{
                        $credential->setting = '';
                    }
                    break;
                }
                case Providers::$salsa_gaming:
                case Providers::$patagonia:
                case Providers::$pg_soft:
                case Providers::$booongo:
                case Providers::$game_art:
                case Providers::$booming_games:
                case Providers::$kiron_interactive:
                case Providers::$hacksaw_gaming:
                case Providers::$triple_cherry:
                case Providers::$espresso_games:
                {
                    if(!is_null($credential->data)){
                        if((isset($credential->data->pn)) && (isset($credential->data->key))){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Pn'),
                                ': ',
                                $credential->data->pn,
                                _i('Key'),
                                ': ',
                                $credential->data->key
                            );
                        }else{
                            $credential->setting = '';
                        }
                    }else{
                        $credential->setting = '';
                    }
                    break;
                }
                case Providers::$digitain:
                {
                    if(!is_null($credential->data)){
                        if((isset($credential->data->private_key)) && (isset($credential->data->partner_id)) && (isset($credential->data->url_script))){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Private key'),
                                ': ',
                                $credential->data->private_key,
                                _i('Partner ID'),
                                ': ',
                                $credential->data->partner_id,
                                _i('URl Script'),
                                ': ',
                                $credential->data->url_script,
                            );
                        }else{
                            $credential->setting ="";
                        }
                    }else{
                        $credential->setting ="";
                    }
                    break;
                }
                case Providers::$beter:
                {
                    if(!is_null($credential->data)){
                        if((isset($credential->data->private_key)) && (isset($credential->data->secret_key)) && (isset($credential->data->script))){
                            $credential->setting = sprintf(
                                '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                                _i('Private key'),
                                ': ',
                                $credential->data->private_key,
                                _i('Secret key'),
                                ': ',
                                $credential->data->secret_key,
                                _i('Script'),
                                ': ',
                                $credential->data->script,
                            );
                        }else{
                            $credential->setting ="";
                        }
                    }else{
                        $credential->setting ="";
                    }
                    break;
                }
            }
        }
    }

    /**
     * Format credentials
     * @param integer $provider Provider ID
     * @param array $credentials Credentials data
     */
    public function formatCredentials($credentials)
    {
        $providersRepo = new ProvidersRepo();
        foreach ($credentials as $credential) {
            $provider = $providersRepo->find($credential->provider_id);
            if(($credential->provider_id!==Providers::$dlv) && ($credential->provider_id!==Providers::$iq_soft)){
                $credential->client = sprintf(
                    '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm">%s</a>',
                    route('configurations.credentials.details', [ $credential->client_id, $credential->provider_id, $credential->currency_iso]),
                    $credential->client
                );
            }else{
                $credential->client;
            }
           $percentage = ($credential->percentage == 0.0 || is_null($credential->percentage) ) ? 0 : ($credential->percentage * 100);
           $credential->percentage = number_format($percentage,2);
           $credential->actions = sprintf(
               '<button type="button" class="btn u-btn-3d btn-sm u-btn-primary mr-2 delete" id="delete" data-route="%s"><i class="hs-admin-trash"></i> %s</button>',
               route('configurations.credentials.delete',[$credential->client_id, $credential->provider_id, $credential->currency_iso]),
               _i('Delete')
           );
           $credential->type = ProviderTypes::getName($credential->provider_type_id);
           $credential->provider = $provider->name;
        }

    }

    /**
     * Format type providers
     *
     * @param array $types Types providers data
     */
    public function formatTypeProviders($types)
    {
        foreach ($types as $type) {
            $type->name = ProviderTypes::getName($type->id);
        }
    }
}
