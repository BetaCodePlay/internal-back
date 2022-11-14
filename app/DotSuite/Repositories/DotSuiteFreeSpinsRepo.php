<?php


namespace App\DotSuite\Repositories;

use App\DotSuite\Entities\DotSuiteFreeSpin;
use App\DotSuite\Enums\FreeSpinsStatus;

/**
 * Class DotSuiteFreeSpinsRepo
 *
 * This class allows to interact with DotSuite free spin entity
 *
 * @package App\DotSuite\Repositories
 * @author  Damelys Espinoza
 */
class DotSuiteFreeSpinsRepo
{
    /**
     * All list promotions id and currency
     *
     * @param int $id Promotion id.
     * @param string $currency Currency Iso
     * @return mixed
     */
    public function allListPromotionsIdAndCurrency($id, $currency)
    {
        $freeSpins = DotSuiteFreeSpin::where('data->promotion_id', $id)
            ->where('currency_iso', $currency)
            ->whitelabel()
            ->first();
        return $freeSpins;
    }

    /**
     * Find by free spin
     *
     * @param int $id Promotion id.
     * @param string $currency Currency Iso
     * @param int $provider Provider ID
     * @param int $whitelabel Whitelabel ID
     * @return mixed
     */
    public function firstPromotionIdAndCurrency($id, $currency, $provider, $whitelabel)
    {
        $freeSpins = DotSuiteFreeSpin::where('data->promotion_id', $id)
            ->where('currency_iso', $currency)
            ->where('provider_id', $provider)
            ->where('whitelabel_id', $whitelabel)
            ->first();
        return $freeSpins;
    }

    /**
     * Get list by whitelabel
     *
     * @param int $whitelabel Whitelabel ID
     * @param int $provider Provider ID
     * @return mixed
     */
    public function getListByWhitelabel($whitelabel, $provider)
    {
        $freeSpins = DotSuiteFreeSpin::where('whitelabel_id', $whitelabel)
            ->where('provider_id', $provider)
            ->where('status',  FreeSpinsStatus::$pending_to_play)->get();
        return $freeSpins;
    }

    /**
     * Get free rounds caleta gaming
     *
     * @param int $whitelabel Whitelabel ID
     * @param int $provider Provider ID
     * @param string $currency Currency Iso
     * @return mixed
     */
    public function getFreeRoundsCaletaGaming( $currency, $whitelabel, $provider)
    {
        $getList = DotSuiteFreeSpin::where('whitelabel_id', $whitelabel)
            ->where('provider_id', $provider)
            ->where('currency_iso', $currency)
            ->where('status',  FreeSpinsStatus::$enable)
            ->get();
        return $getList;
    }

    /**
     * Get free rounds caleta gaming
     *
     * @param int $whitelabel Whitelabel ID
     * @param int $provider Provider ID
     * @param string $currency Currency Iso
     * @return mixed
     */
    public function getFreeSpins( $currency, $whitelabel, $provider, $reference, $status)
    {
        $freeSpins = DotSuiteFreeSpin::where('whitelabel_id', $whitelabel)
            ->where('provider_id', $provider)
            ->where('currency_iso', $currency)
            ->where('status', $status);

        if(!is_null($reference)){
            $freeSpins->whereRaw("data::json->>'code_reference' = ?", $reference);

        }
        $data = $freeSpins->get();
        return $data;
    }

    /**
     * Get list by whitelabel
     *
     * @param int $whitelabel Whitelabel ID
     * @param int $provider Provider ID
     * @param string $currency Currency Iso
     * @return mixed
     */
    public function getListPragmatic( $currency, $whitelabel, $provider)
    {
        $getListPragmatic = DotSuiteFreeSpin::where('whitelabel_id', $whitelabel)
            ->where('provider_id', $provider)
            ->where('currency_iso', $currency)
            ->where('status',  FreeSpinsStatus::$pending_to_play)
            ->get();
        return $getListPragmatic;
    }

    /**
     * Get triple cherry for player
     *
     * @param int $whitelabel Whitelabel ID
     * @param int $provider Provider ID
     * @return mixed
     */
    public function getTripleCherryForPlayer($whitelabel, $provider)
    {
        $promotions = DotSuiteFreeSpin::select('id', 'data->new_campaign_id AS new_campaign_id')
            ->where('whitelabel_id', $whitelabel)
            ->where('provider_id', $provider)
            ->where('status',  FreeSpinsStatus::$pending_to_play)
            ->get();
        return $promotions;
    }

    /**
     * Get triple cherry promotion
     *
     * @param int $whitelabel Whitelabel ID
     * @param int $provider Provider ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function getTripleCherryPromotion($whitelabel, $provider, $currency, $status)
    {
        $promotions = DotSuiteFreeSpin::select('id', 'data->promotion_id AS promotion_id', 'currency_iso', 'data->operator AS operator',
            'data->name AS name', 'free_spins', 'status')
            ->where('whitelabel_id', $whitelabel)
            ->where('provider_id', $provider)
            ->where('currency_iso', $currency)
            ->where('status', $status)
            ->get();
        return $promotions;
    }

    /**
     * Get triple cherry for player users
     *
     * @param int $whitelabel Whitelabel ID
     * @param int $provider Provider ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function getTripleCherryForPlayerUsers($whitelabel, $provider, $currency)
    {
        $users = DotSuiteFreeSpin::where('dotsuite_free_spins.whitelabel_id', $whitelabel)
            ->where('provider_id', $provider)
            ->where('currency_iso', $currency)
            ->where('dotsuite_free_spins.status',  FreeSpinsStatus::$pending_to_play)
            ->get();

        return $users;
    }

    /**
     * Get triple cherry for player users
     *
     * @param int $whitelabel Whitelabel ID
     * @param int $provider Provider ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function getTripleCherryForPlayerUsersCancel($whitelabel, $provider, $currency)
    {
        $users = DotSuiteFreeSpin::where('dotsuite_free_spins.whitelabel_id', $whitelabel)
            ->where('provider_id', $provider)
            ->where('currency_iso', $currency)
            ->whereIn('dotsuite_free_spins.status',  [FreeSpinsStatus::$pending_to_play, FreeSpinsStatus::$played])
            ->get();

        return $users;
    }

    /**
     * Find by free spin
     *
     * @param var $bonusCode Bonus code
     * @return mixed
     */
    public function findByFreeSpin($bonusCode)
    {
        $freeSpins = DotSuiteFreeSpin::where('data->bonus_code', $bonusCode)
                ->whitelabel()
                ->first();
        return $freeSpins;
    }

    /**
     * Store free spins
     *
     * @param array $data Free spins data
     * @return mixed
     */
    public function store($data)
    {
        $freeSpins = DotSuiteFreeSpin::create($data);
        return $freeSpins;
    }

    /**
     *  Update DotSuite free spin
     *
     * @param int $id Free spin Id
     * @param array $data Free spin data
     * @return mixed
     */
    public function update($id, $data)
    {
        $freeSpins = DotSuiteFreeSpin::find($id);
        $freeSpins->fill($data);
        $freeSpins->save();
        return $freeSpins;
    }
}
