<?php

namespace App\Core\Repositories;

use App\Core\Entities\ManualExchange;

/**
 * Class ManualExchangesRepo
 *
 * This class allows to interact with ManualExchange entity
 *
 * @package App\Core\Repositories
 * @author  Eborio Linarez
 */
class ManualExchangesRepo
{
    /**
     * Get all exchanges
     *
     * @return mixed
     */
    public function all()
    {
        $exchanges = ManualExchange::orderBy('currency_iso', 'ASC')
            ->get();
        return $exchanges;
    }

    /**
     * Update exchange
     *
     * @param int $id Exchange ID
     * @param array $data Exchange data
     * @return mixed
     */
    public function update($id, $data)
    {
        $exchange = ManualExchange::find($id);
        $exchange->fill($data);
        $exchange->save();
        return $exchange;
    }
}
