<?php

namespace App\Whitelabels\Entities;

use Dotworkers\Configurations\Entities\Component;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Whitelabel
 *
 * This class allows to interact with whitelabels table
 *
 * @package App\Whitelabels\Entities
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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'domain', 'status', 'url', 'data'];

    /**
     * Casts
     *
     * @var string[]
     */
    protected $casts = [
        'data' => 'array'
    ];

    /**
     * Relationship with Component entity
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function configurations()
    {
        return $this->belongsToMany(Component::class, 'configurations', 'whitelabel_id', 'component_id')->withPivot(['data'])->withTimestamps();
    }
}
