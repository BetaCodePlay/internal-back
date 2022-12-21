<?php

namespace Dotworkers\Bonus\Events;

class WalletBonus
{
    public $wallet;

    public $currency;

    public $campaign;

    public $bonus;

    public function __construct($wallet, $currency, $campaign, $bonus)
    {
        $this->wallet = $wallet;
        $this->currency = $currency;
        $this->campaign = $campaign;
        $this->bonus = $bonus;
    }
}