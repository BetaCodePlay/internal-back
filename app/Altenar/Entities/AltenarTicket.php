<?php

namespace App\Altenar\Entities;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class AltenarTicket
 *
 * Class to define the altenar_tickets table attributes
 *
 * @package App\Altenar\Entities
 * @author  Miguel Sira
 * @mixin Builder
 */
class AltenarTicket extends Model
{
    /**
     * Table
     *
     * @var string
     */
    protected $table = 'altenar_tickets';

    /**
     * Get data attribute
     *
     * @param string $data Ticket data
     * @return mixed
     */
    public function getDataAttribute(string $data)
    {
        return json_decode($data);
    }
}
