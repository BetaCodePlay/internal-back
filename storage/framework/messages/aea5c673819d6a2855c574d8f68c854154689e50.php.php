<?php

namespace Dotworkers\Configurations\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class TemplateElement
 *
 * This class allows to interact with template_elements table
 *
 * @package Dotworkers\Configurations\Entities
 * @author  Eborio Linarez
 */
class TemplateElement extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'template_elements';

    /**
     * Get data attribute
     *
     * @param string $data Data string
     * @return mixed
     */
    public function getDataAttribute($data)
    {
        return json_decode($data);
    }
}
