<?php

namespace App\Agents\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class AgentCurrency
 *
 * Class to define the agent_currencies table attributes
 *
 * @package App\Agents\Entities
 * @author  Eborio Linarez
 */
class AgentCurrency extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'agent_currencies';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['agent_id', 'currency_iso', 'balance'];
}
