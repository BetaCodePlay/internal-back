<?php

namespace App\Agents\Repositories;

use App\Agents\Entities\AgentCurrency;

/**
 * Class AgentCurrenciesRepo
 *
 * This class allows to interact with AgentCurrency entity
 *
 * @package App\Agents\Repositories
 * @author  Eborio Linarez
 */
class AgentCurrenciesRepo
{
    /**
     * Store user currency
     *
     * @param array $agentData Agent currency data
     * @param array $balanceData Balance data
     * @return mixed
     */
    public function store($agentData, $balanceData)
    {
        $currency = AgentCurrency::updateOrCreate(
            $agentData,
            $balanceData
        );
        return $currency;
    }
}
