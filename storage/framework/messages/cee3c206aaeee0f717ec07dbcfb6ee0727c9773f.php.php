<?php

namespace App\Whitelabels\Repositories;

use App\Whitelabels\Entities\Whitelabel;
use Dotworkers\Configurations\Enums\Components;

/**
 * Class WhitelabelsRepo
 *
 * This class allows to interact with Whitelabel entity
 *
 * @package App\Whitelabels\Repositories
 * @author
 */
class WhitelabelsRepo
{
    /**
     * Get all whitelabels
     *
     * @return mixed
     */
    public function all()
    {
        $whitelabels = Whitelabel::orderBy('description', 'ASC')
            ->get();
        return $whitelabels;
    }


    /**
     * Find whitelabels
     *
     * @param int $whitelabel Whitelabels type ID
     * @return mixed
     */
    public function find($whitelabel)
    {
        $whitelabels = Whitelabel::find($whitelabel);
        return $whitelabels;
    }

    /**
     * Get by status
     *
     * @param array $status Whitelabels status
     * @return mixed
     */
    public function getByStatus($status)
    {
        $whitelabels = Whitelabel::select('whitelabels.*')
            ->whereIn('status', $status)
            ->orderBy('description', 'ASC')
            ->get();
        return $whitelabels;
    }

    /**
     * Store whitelabel and configurations
     *
     * @param array $data Whitelabel data
     * @param array $accessComponentData Access component data
     * @param array $designComponent Design component data
     * @param array $currenciesComponent Currencies component data
     * @param array $emailComponent Email component data
     * @param array $servicesComponent Services component data
     * @return mixed
     */
    public function store($data, $accessComponentData, $designComponent, $currenciesComponent, $emailComponent, $servicesComponent)
    {
        $whitelabel = Whitelabel::create($data);
        $whitelabel->configurations()->attach(Components::$access, ['data' => json_encode($accessComponentData)]);
        $whitelabel->configurations()->attach(Components::$design, ['data' => json_encode($designComponent)]);
        $whitelabel->configurations()->attach(Components::$payments, ['data' => json_encode($currenciesComponent)]);
        $whitelabel->configurations()->attach(Components::$providers, ['data' => json_encode($emailComponent)]);
        $whitelabel->configurations()->attach(Components::$services, ['data' => json_encode($servicesComponent)]);
        return $whitelabel;
    }

    /**
     * Update whitelabels
     *
     * @param int $id Whitelabel ID
     * @param array $data Whitelabel data
     * @return mixed
     */
    public function update($id, $data)
    {
        $whitelabel = Whitelabel::find($id);
        $whitelabel->fill($data);
        $whitelabel->save();
        return $whitelabel;
    }
}
