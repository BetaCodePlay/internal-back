<?php

namespace App\Audits\Collections;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Audits\Repositories\AuditsRepo;
use App\Audits\Enums\AuditTypes;

/**
 * Class AuditsCollection
 *
 * This class allows formatting Audits
 *
 * @package App\Audits\Collections
 * @author  Mayinis Torrealba
 */
class AuditsCollection
{
    /**
     * Format audits search
     *
     *  @param array $audits Audits data
     */
    public function formatSearch($audits)
    {
        $auditsRepo = new AuditsRepo();
        foreach ($audits as $audit) {
            $type = $auditsRepo->findByType($audit->audit_type_id);
            $audit->user = sprintf(
                '<a href="%s" class="btn u-btn-3d u-btn-primary btn-sm" target="_blank">%s</a>',
                route('users.details', $audit->user_id),
                $audit->user_id
            );
            $audit->username = $audit->username;
            $audit->type = $type->name;
            switch ($type->id) {
                case AuditTypes::$login:
                case AuditTypes::$dotpanel_login:
                {
                    if(!is_null($audit->data)){
                        if((isset($audit->data->ip)) && (isset($audit->data->mobile))){
                        $audit->details = sprintf(
                            '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                            _i('Ip'),
                            ': ',
                            $audit->data->ip,
                            _i('Mobile'),
                            ': ',
                            $audit->data->mobile
                        );
                        }else{
                            $audit->details = '';
                        }
                    }else{
                        $audit->details = '';
                    }
                    break;
                }
                case AuditTypes::$user_modification:
                {
                    if(!is_null($audit->data)){
                        if((isset($audit->data->ip)) && (isset($audit->data->user_id)) && (isset($audit->data->username)) && (isset($audit->data->user_data))){
                        $audit->details = sprintf(
                            '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                            _i('Ip'),
                            ': ',
                            $audit->data->ip,
                            _i('User Id'),
                            ': ',
                            $audit->data->user_id,
                            _i('Username'),
                            ': ',
                            $audit->data->username,
                            _i('User Data'),
                            ': ',
                            json_encode($audit->data->user_data)
                        );
                    }else{
                        $audit->details ="";
                    }
                }else{
                    $audit->details ="";
                }
                    break;
                }
                case AuditTypes::$user_creation:
                {
                    if(!is_null($audit->data)){
                        if((isset($audit->data->ip)) && (isset($audit->data->user_id)) && (isset($audit->data->username)) && (isset($audit->data->currency_iso)) && (isset($audit->data->user_data)) && (isset($audit->data->profile_data))){
                        $audit->details = sprintf(
                            '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                            _i('Ip'),
                            ': ',
                            $audit->data->ip,
                            _i('User Id'),
                            ': ',
                            $audit->data->user_id,
                            _i('Username'),
                            ': ',
                            $audit->data->username,
                            _i('Currency'),
                            ': ',
                            $audit->data->currency_iso,
                            _i('User Data'),
                            ': ',
                            json_encode($audit->data->user_data),
                            _i('Profile Data'),
                            ': ',
                            json_encode($audit->data->profile_data)
                        );
                        }else{
                            $audit->details = '';
                        }
                    }else{
                        $audit->details = '';
                    }
                    break;
                }
                case AuditTypes::$user_status:
                {
                    if(!is_null($audit->data)){
                        if((isset($audit->data->ip)) && (isset($audit->data->user_id)) && (isset($audit->data->username)) && (isset($audit->data->old_status)) && (isset($audit->data->new_status)) && (isset($audit->data->description))){
                        $audit->details = sprintf(
                            '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                            _i('Ip'),
                            ': ',
                            $audit->data->ip,
                            _i('User Id'),
                            ': ',
                            $audit->data->user_id,
                            _i('Username'),
                            ': ',
                            $audit->data->username,
                            _i('Old Status'),
                            ': ',
                            $audit->data->old_status,
                            _i('New Status'),
                            ': ',
                            $audit->data->new_status,
                            _i('Description'),
                            ': ',
                            $audit->data->description
                        );
                    }else{
                        $audit->details ="";
                    }
                }else{
                    $audit->details ="";
                }
                    break;
                }
                case AuditTypes::$user_password:
                {
                    if(!is_null($audit->data)){
                        if((isset($audit->data->ip)) && (isset($audit->data->user_id)) && (isset($audit->data->username)) && (isset($audit->data->password))){
                        $audit->details = sprintf(
                            '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                            _i('Ip'),
                            ': ',
                            $audit->data->ip,
                            _i('User Id'),
                            ': ',
                            $audit->data->user_id,
                            _i('Username'),
                            ': ',
                            $audit->data->username,
                            _i('Password'),
                            ': ',
                            $audit->data->password
                        );
                        }else{
                            $audit->details = '';
                        }
                    }else{
                        $audit->details = '';
                    }
                    break;
                }
                 case AuditTypes::$support_login:
                {
                    if((!is_null($credential->data))){
                     $audit->details = $audit->data;
                    }
                    break;
                }
                case AuditTypes::$manual_transactions:
                case AuditTypes::$bonus_transactions:
                case AuditTypes::$points_transactions:
                case AuditTypes::$agent_user_password:
                {
                    if(!is_null($audit->data)){
                        if((isset($audit->data->ip)) && (isset($audit->data->user_id)) && (isset($audit->data->username)) && (isset($audit->data->transaction))){
                        $audit->details = sprintf(
                            '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                            _i('Ip'),
                            ': ',
                            $audit->data->ip,
                            _i('User Id'),
                            ': ',
                            $audit->data->user_id,
                            _i('Username'),
                            ': ',
                            $audit->data->username,
                            _i('Transaction'),
                            ': ',
                            json_encode($audit->data->transaction)
                        );
                        }else{
                            $audit->details = '';
                        }
                    }else{
                        $audit->details = '';
                    }
                    break;
                }
                case AuditTypes::$manual_adjustments:
                {
                    if(!is_null($audit->data)){
                        if((isset($audit->data->ip)) && (isset($audit->data->user_id)) && (isset($audit->data->username)) && (isset($audit->data->transaction)) && (isset($audit->data->user_data))){
                        $audit->details = sprintf(
                            '<ul><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li><li><strong>%s</strong>%s%s</li></ul>',
                            _i('Ip'),
                            ': ',
                            $audit->data->ip,
                            _i('User Id'),
                            ': ',
                            $audit->data->user_id,
                            _i('Username'),
                            ': ',
                            $audit->data->username,
                            _i('Transaction'),
                            ': ',
                            json_encode($audit->data->transaction),
                            _i('User Data'),
                            ': ',
                            json_encode($audit->data->user_data),
                        );
                        }else{
                            $audit->details = '';
                        }
                    }else{
                        $audit->details = '';
                    }
                    break;
                }
            }
        }
    }

}
