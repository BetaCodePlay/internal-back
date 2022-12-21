<?php


namespace App\Users\Enums;

/**
 * Class DocumentStatus
 *
 * This class allows to define static document status
 *
 * @package App\Users\Enums
 * @author  Damelys Espinoza
 */
class DocumentStatus
{
    /**
     * Pending to upload
     *
     * @var integer
     */
    public static $pending_to_upload = 1;

    /**
     * Awaiting verification
     *
     * @var integer
     */
    public static $awaiting_verification = 2;

    /**
     * Approved
     *
     * @var integer
     */
    public static $approved = 3;

    /**
     * Rejected
     *
     * @var integer
     */
    public static $rejected = 4;

    /**
     * Get status name
     *
     * @param int $status Document status
     * @return array|string|null
     */
    public static function getName($status)
    {
        switch ($status) {
            case self::$pending_to_upload:
            {
                return _i('Pending to upload');
                break;
            }
            case self::$awaiting_verification:
            {
                return _i('Awaiting verification');
                break;
            }
            case self::$approved:
            {
                return _i('Approved');
                break;
            }
            default:
            {
                return _i('Delete');
                break;
            }
        }
    }
}
