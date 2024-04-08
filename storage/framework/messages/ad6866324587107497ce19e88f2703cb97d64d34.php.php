<?php

namespace Dotworkers\Configurations\Repositories;

use Dotworkers\Configurations\Entities\EmailType;
/**
 * Class EmailConfigurationsRepo
 *
 * This class allows to interact with email_types table
 *
 * @package Dotworkers\Configurations\Repositories
 * @author  Eborio Linarez
 */
class EmailConfigurationsRepo
{
    /**
     * Search mail configuration by enum and whitelabel
     *
     * @param string $whitelabel Whitelabel ID
     * @param string $emailConfiguration Email Configuration
     * @return mixed
     */
    public function getEmailContents($whitelabel, $emailConfiguration)
    {
        $configuration = EmailType::select('email_types.name', 'email_type_whitelabel.title', 'email_type_whitelabel.subtitle', 'email_type_whitelabel.content', 'email_type_whitelabel.button', 'email_type_whitelabel.footer', 'email_type_whitelabel.language', 'email_type_whitelabel.data')
            ->join('email_type_whitelabel', 'email_types.id', '=', 'email_type_whitelabel.email_type_id')
            ->where('email_type_whitelabel.whitelabel_id', $whitelabel)
            ->where('email_type_whitelabel.email_type_id', $emailConfiguration)
            ->first();
        return $configuration;
    }
}