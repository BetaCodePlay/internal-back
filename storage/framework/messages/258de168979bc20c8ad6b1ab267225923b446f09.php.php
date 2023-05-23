<?php

namespace App\CRM\Repositories;

use App\CRM\Entities\EmailTemplateTypes;

/**
 * Class EmailTemplatesRepo
 *
 * This class allows to interact with EmailTemplateTypes entity
 *
 * @package App\CRM\Repositories
 * @author  Carlos Hurtado
 */
class EmailTemplateTypesRepo
{
    /**
     * Get all email template types
     *
     * @return mixed
     */
    public function all()
    {
        return EmailTemplateTypes::all();
    }
}
