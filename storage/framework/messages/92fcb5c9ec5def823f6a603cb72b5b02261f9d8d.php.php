<?php

namespace App\Store\Enums;

/**
 * Class Actions
 *
 * This class allows to define static actions
 *
 * @package App\Users\Enums
 * @author  Carlos Hurtado
 */
class Actions
{
    /**
     * Get action name
     *
     * @param int $action Action ID
     * @return mixed|string|null
     */
    public static function getName(int $action)
    {
        $name = null;
        switch ($action) {
            case 1:
            {
                $name = _i('Deposit');
                break;
            }
            case 2:
            {
                $name = _i('Login');
                break;
            }
            case 3:
            {
                $name = _i('Bet');
                break;
            }
        }
        return $name;
    }
}
