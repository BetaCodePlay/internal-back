<?php

namespace App\Invoices\Enums;

/**
 * Class CurrencySymbols
 *
 * This class allows to define static currency symbols
 *
 * @package App\Invoices\Enums
 * @author  Gabriel Santiago
 */
class CurrencySymbols
{
    /**
     * Get symbol currency
     *
     * @param string $currency currency
     * @return array|string|null
     */
    public static function getSymbol($currency)
    {
        switch ($currency) {
            case 'BTC':
            {
                return '₿';
                break;
            }
            case 'ETH':
            {
                return 'Ξ';
                break;
            }
            case 'EUR':
            {
                return '€';
                break;
            }
            case 'YEN':
            {
                return '¥';
                break;
            }
            case 'GTQ':
            {
                return 'Q';
                break;
            }
            case 'ARG':
            case 'COP':
            case 'CLP':
            case 'MXC':
            case 'USD':
            case 'UYU':
            {
                return '$';
                break;
            }
            case 'BOB':
            {
                return 'Bs';
                break;
            }
            case 'BRL':
            {
                return 'R$';
                break;
            }
            case 'CRC':
            case 'SVC':
            {
                return '₡';
                break;
            }
            case 'DOP':
            {
                return 'RD$';
                break;
            }
            case 'ILS':
            {
                return '₪';
                break;
            }
            case 'NIO':
            {
                return 'C$';
                break;
            }
            case 'PAB':
            {
                return 'B/';
                break;
            }
            case 'PYG':
            {
                return '₲';
                break;
            }
            case 'PEN':
            {
                return 'S/';
                break;
            }
            case 'RUB':
            {
                return '₽';
                break;
            }
            case 'ZAR':
            {
                return 'R';
                break;
            }
            case 'SEK':
            {
                return 'kr';
                break;
            }
            case 'TND':
            {
                return 'د.ت';
                break;
            }
            case 'TRY':
            {
                return '₺';
                break;
            }
            case 'VEF':
            case 'VES':
            {
                return 'BsS';
                break;
            }
            case 'ZMW':
            {
                return 'ZMW';
                break;
            }
        }
    }
}
