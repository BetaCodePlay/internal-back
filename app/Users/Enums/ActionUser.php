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

    public static $blocked_branch = 8;

    public static $direct_lock = 9;

    public static $update_email = 10;

    public static function getName($action): string {
        return match ($action) {
            self::$active => _i('active'),
            self::$inactive => _i('inactive'),
            self::$delete => _i('Removed'),
            self::$hide => _i('Hidden'),
            self::$locked_login_attempts => _i('Blocked for session attempt'),
            self::$locked_higher => _i('Blocked by a superior'),
            self::$blocked_branch => _i('Blocked by branch'),
            self::$direct_lock => _i('Direct lock'),
            self::$changed_password => _i('Password change process'),
            self::$update_email => _i('Update email'),
            default => _i('Undefined Action...')
        };
    }

    public static function isBlocked($action): bool {
        return match ($action) {
            self::$active, self::$changed_password, self::$hide, self::$update_email => true,
            self::$locked_login_attempts, self::$locked_higher, self::$blocked_branch, self::$direct_lock => false,
            self::$inactive, self::$delete => null,
            default => true
        };
    }


}
