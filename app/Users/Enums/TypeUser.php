<?php


namespace App\Users\Enums;


class TypeUser
{
    public static $agentMater = 1;
    public static $agentCajero = 2;

    public static $player = 5;

    public static function getName($type)
    {
        switch ($type) {
            case self::$agentMater:
            {
                return _i('Agent Master');
                break;
            }
            case self::$agentCajero:
            {
                return _i('Cashier Agent');
                break;
            }
            case self::$player:
            {
                return _i('Players');
                break;
            }
            default:
            {
                return _i('Undefined type...');
                break;
            }
        }
    }
}
