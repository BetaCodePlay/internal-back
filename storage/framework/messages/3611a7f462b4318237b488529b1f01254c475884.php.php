<?php

namespace App\Core\Repositories;

use Dotworkers\Configurations\Entities\EmailType;
use Illuminate\Support\Facades\DB;

/**
 * Class EmailConfigurationsRepo
 *
 * This class allows to interact with email types entity
 *
 * @package App\Core\Repositories
 * @author Carlos Hurtado
 */
class EmailConfigurationsRepo
{
    /**
     * Get all email types
     *
     * @return mixed
     */
    public function all()
    {
        $emailTypes = EmailType::select('email_types.id','email_types.name')
            ->orderBy('id', 'ASC')
            ->get();
        return $emailTypes;
    }

    /**
     * Find email type by ID
     *
     * @param int $id Email ID
     * @return mixed
     */
    public function find($id)
    {
        $emailType = EmailType::select('email_types.id')
            ->where('email_types.id', $id)
            ->first();
        return $emailType;
    }

    /**
     * Find email configurations by whitelabel and type
     *
     * @param string $whitelabel Whitelabel ID
     * @param int $emailType Email type ID
     * @return mixed
     */
    public function findEmailConfigurations($whitelabel, $emailType)
    {
        $emailConfiguration = EmailType::select('email_types.name', 'email_type_whitelabel.title', 'email_type_whitelabel.subtitle', 'email_type_whitelabel.content',
            'email_type_whitelabel.button', 'email_type_whitelabel.footer', 'email_type_whitelabel.email_type_id')
            ->join('email_type_whitelabel', 'email_types.id', '=', 'email_type_whitelabel.email_type_id')
            ->where('email_type_whitelabel.whitelabel_id', $whitelabel)
            ->where('email_type_whitelabel.email_type_id', $emailType)
            ->first();
        return $emailConfiguration;
    }

    /**
     * Update email configurations by whitelabel and type
     *
     * @param string $whitelabel Whitelabel ID
     * @param int $emailType Email type ID
     * @param array $data Email configuration data
     * @return mixed
     */
    public function updateEmailConfigurations($whitelabel, $emailType, $emailData)
    {
        $emailConfiguration = DB::table('email_type_whitelabel')
            ->updateOrInsert(
                ['email_type_whitelabel.email_type_id' => $emailType, 'email_type_whitelabel.whitelabel_id' => $whitelabel],
                $emailData
            );
        return $emailConfiguration;
    }
}
