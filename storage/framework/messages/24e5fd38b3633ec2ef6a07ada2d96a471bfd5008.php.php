<?php

namespace Dotworkers\Configurations\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Component
 *
 * This class allows to interact with components table
 *
 * @package Dotworkers\Configurations\Entities
 * @author  Eborio Linarez
 */
class Component extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'components';
}
