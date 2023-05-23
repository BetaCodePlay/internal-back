<?php

namespace App\CRM\Enums;

/**
 * Class EmailTemplatesStatus
 *
 * This class allows to define email templates status
 *
 * @package App\CRM\Enums
 * @author  Carlos Hurtado
 */
class EmailTemplatesTypes
{
    /**
     * Email template marketing
     *
     * @var int
     */
    public static $email_template_marketing = 1;

    /**
     * Email template index
     *
     * @var int
     */
    public static $email_template_transactions = 2;

    /**
     * Email template index
     *
     * @var int
     */
    public static $email_template_transactions_approved = 3;

    /**
     * Email template index
     *
     * @var int
     */
    public static $email_template_transactions_rejected = 4;


}
