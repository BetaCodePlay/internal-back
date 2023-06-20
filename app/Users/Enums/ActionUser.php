<?php


namespace App\Users\Enums;


class ActionUser
{
    public static $active = 1;

    public static $inactive = 2;

    public static $delete = 3;

    public static $hide = 4;

    public static $locked_login_attempts = 5;

    public static $locked_higher = 6;

    public static $changed_password = 7;

    public static function getName($action)
    {
        switch ($action) {
            case self::$active:
            {
                return _i('active');
                break;
            }
            case self::$inactive:
            {
                return _i('inactive');
                break;
            }
            case self::$delete:
            {
                return _i('Removed');
                break;
            }
            case self::$hide:
            {
                return _i('Hidden');
                break;
            }
            case self::$locked_login_attempts:
            {
                return _i('Blocked for session attempt');
                break;
            }
            case self::$locked_higher:
            {
                return _i('Blocked by a superior');
                break;
            }
            case self::$changed_password:
            {
                return _i('Proceso de cambio de contraseña');
                //return _i('Password change process');
                //return _i('Changed password');
                break;
            }
            default:
            {
                return _i('Undefined Action...');
                break;
            }
        }
    }
}
