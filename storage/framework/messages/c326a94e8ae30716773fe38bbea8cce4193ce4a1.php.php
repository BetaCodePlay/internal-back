<?php

namespace Dotworkers\Configurations\Enums;

/**
 * Class PaymentMethodsCategories
 * @package Dotworkers\Configurations\Enums
 */
class PaymentMethodsCategories
{
    /**
     * Cash
     *
     * @var int
     */
    public static $cash = 1;

    /**
     * Wire transfers
     *
     * @var int
     */
    public static $wire_transfers = 2;

    /**
     * Credit or debit cards
     *
     * @var int
     */
    public static $credit_debit_cards = 3;

    /**
     * Electronic wallets
     *
     * @var int
     */
    public static $electronic_wallets = 4;

    /**
     * Promo codes
     *
     * @var int
     */
    public static $promo_codes = 5;

    /**
     * Prepaid cards
     *
     * @var int
     */
    public static $prepaid_cards = 6;

    /**
     * Cryptocurrencies
     *
     * @var int
     */
    public static $cryptocurrencies = 7;

    /**
     * Get payment method category name
     *
     * @param $category
     * @return mixed
     */
    public static function getName($category)
    {
        switch ($category) {
            case self::$cash: {
                $name = _i('Cash');
                break;
            }
            case self::$wire_transfers: {
                $name = _i('Wire transfers');
                break;
            }
            case self::$credit_debit_cards: {
                $name = _i('Credit / Debit cards');
                break;
            }
            case self::$electronic_wallets: {
                $name = _i('Electronic wallets');
                break;
            }
            case self::$promo_codes: {
                $name = _i('Promo codes');
                break;
            }
            case self::$prepaid_cards: {
                $name = _i('Prepaid cards');
                break;
            }
            case self::$cryptocurrencies: {
                $name = _i('Cryptocurrencies');
                break;
            }
        }
        return $name;
    }
}