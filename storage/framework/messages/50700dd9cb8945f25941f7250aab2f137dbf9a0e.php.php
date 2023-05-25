<?php

namespace Dotworkers\Configurations\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Whitelabel
 *
 * This class allows to interact with whitelabels table
 *
 * @package Dotworkers\Configurations\Entities
 * @author  Eborio Linarez
 */
class Whitelabel extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'whitelabels';

    /**
     * Relationship with Whitelabel entity
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function components()
    {
        return $this->belongsToMany(Component::class, 'configurations', 'whitelabel_id', 'component_id');
    }

    /**
     * Get data attribute
     *
     * @param string $data JSON data
     * @return mixed
     */
    public function getDataAttribute($data)
    {
        return json_decode($data);
    }

    /**
     * Set data attribute
     *
     * @param array $data Configuration array
     */
    public function setDataAttribute($data)
    {
        $this->attributes['data'] = json_encode($data);
    }
}
