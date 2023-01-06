<?php

namespace App\Audits\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Audit
 *
 * Class to define the AuditTypes table attributes
 *
 * @package App\Audits\Entities
 * @author  Gabriel Santiago
 */
class AuditType extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'audit_types';
    /**
     *
     *
     * @var array
     */
    protected $fillable = ['name'];
}
