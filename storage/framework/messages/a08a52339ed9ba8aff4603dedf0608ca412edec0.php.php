<?php

namespace App\CRM\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class EmailTemplateType
 *
 * This class allows to interact with emailTemplateTypes table
 *
 * @package App\CRM\Entities
 * @author  Carlos Hurtado
 */
class EmailTemplateTypes extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'email_template_types';

    /**
     * Fillable fields
     *
     * @var array
     */
    protected $fillable = ['name', 'status', 'data'];
}
