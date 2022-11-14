<?php

namespace App\IQSoft\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Class IQSoftTicket
 *
 * Class to define the iq_soft_tickets table attributes
 *
 * @package App\IQSoft\Entities
 * @author  Eborio Linarez
 */
class IQSoftTicket extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'iq_soft_tickets';

    /**
     * Get data attribute
     *
     * @param string $data Ticket data
     * @return mixed
     */
    public function getDataAttribute($data)
    {
        return json_decode($data);
    }
}
