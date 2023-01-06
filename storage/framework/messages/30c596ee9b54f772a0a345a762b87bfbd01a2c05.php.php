<?php

namespace App\BetPay\Collections;


/**
 * Class ClientsCollection
 *
 * This class allows to format client data
 *
 * @package App\Betpay\Collections
 * @author  Carlos Hurtado
 */
class ClientsCollection
{

    /**
     * Format client data
     * @param array $clients Clients data
     */
    public function formatClientsAll($clients)
    {
        $data = collect();
        foreach($clients as $client) {
            if($client->password_client == false) {
                $itemObject = new \stdClass();
                $itemObject->id = $client->id;
                $itemObject->name = $client->name;
                $data->push($itemObject);
            }
        }
        return $data;
    }
}
