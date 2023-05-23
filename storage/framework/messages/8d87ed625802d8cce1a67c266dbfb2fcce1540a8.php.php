<?php

namespace Dotworkers\Bonus\Events;

use Dotworkers\Sessions\Sessions;

class RolloverComplete
{
    public $user;

    public $providerType;

    public $currency;

    public $campaignId;

    public $campaignName;

    public $balanceConvert;

    public $whitelabel;

    public $rolloverAmount;

    public $rolloverType;

    public function __construct($user, $providerType, $campaignData, $whitelabel, $rolloverAmount)
    {
        $this->user = $user;
        $this->providerType = $providerType;
        $this->currency = $campaignData->currency_iso;
        $this->balanceConvert = $campaignData->data->max_balance_convert;
        $this->whitelabel = $whitelabel;
        $this->rolloverAmount = $rolloverAmount;
        $this->campaignId = $campaignData->id;
        $this->rolloverType = $campaignData->rollover_type_id;

        $firstLocale = array_keys((array)$campaignData->translations)[0];
        $language = Sessions::findUserByID($user)->language;

        if (!is_null($language)) {
            $name = isset($campaignData->translations->$language) ? $campaignData->translations->$language->name : $campaignData->translations->$firstLocale->name;
        } else {
            $name = $campaignData->translations->$firstLocale->name;
        }

        $this->campaignName = $name;
    }
}