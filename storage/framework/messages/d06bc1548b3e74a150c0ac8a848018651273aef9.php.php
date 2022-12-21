<?php

namespace App\Core\Enums;

/**
 * Class ImagesPositions
 *
 * This class allows to define static images positions
 *
 * @package App\Core\Enums
 * @author  Eborio Linarez
 */
class ImagesPositions
{
    /**
     * Left 1
     *
     * @var string
     */
    public static $left_1 = 'left-1';

    /**
     * Left 2
     *
     * @var string
     */
    public static $left_2 = 'left-2';

    /**
     * Left 3
     *
     * @var string
     */
    public static $left_3 = 'left-3';

    /**
     * Right 1
     *
     * @var string
     */
    public static $right_1 = 'right-1';

    /**
     * Right 2
     *
     * @var string
     */
    public static $right_2 = 'right-2';

    /**
     * Right 3
     *
     * @var string
     */
    public static $right_3 = 'right-3';

    /**
     * Top 1
     *
     * @var string
     */
    public static $top_1 = 'top-1';

    /**
     * Center 1
     *
     * @var string
     */
    public static $center_1 = 'center-1';

    /**
     * Center 2
     *
     * @var string
     */
    public static $center_2 = 'center-2';

    /**
     * Logo light
     *
     * @var string
     */
    public static $logo_light = 'logo-light';

    /**
     * Logo dark
     *
     * @var string
     */
    public static $logo_dark = 'logo-dark';

    /**
     * Mobile light
     *
     * @var string
     */
    public static $mobile_light = 'mobile-light';

    /**
     * Mobile dark
     *
     * @var string
     */
    public static $mobile_dark = 'mobile-dark';

    /**
     * Favicon
     *
     * @var string
     */
    public static $favicon = 'favicon';

    /**
     * Background 1
     *
     * @var string
     */
    public static $background_1 = 'background-1';

    /**
     * Background 2
     *
     * @var string
     */
    public static $background_2 = 'background-2';

    /**
     * Logo 1
     *
     * @var string
     */
    public static $logo_1 = 'logo-1';

    /**
     * Get image position description
     *
     * @param string $position Position
     * @return string
     */
    public static function get($position)
    {
        switch ($position) {
            case self::$left_1:
            {
                $description = _i('Left 1');
                break;
            }
            case self::$left_2:
            {
                $description =  _i('Left 2');
                break;
            }
            case self::$left_3:
            {
                $description =  _i('Left 3');
                break;
            }
            case self::$right_1:
            {
                $description =  _i('Right 1');
                break;
            }
            case self::$right_2:
            {
                $description =  _i('Right 2');
                break;
            }
            case self::$right_3:
            {
                $description =  _i('Right 3');
                break;
            }
            case self::$top_1: {
                $description =  _i('Top 1');
                break;
            }
            case self::$center_1: {
                $description =  _i('Center 1');
                break;
            }
            case self::$center_2: {
                $description =  _i('Center 2');
                break;
            }
            case self::$logo_light: {
                $description =  _i('Logo for light backgrounds');
                break;
            }
            case self::$logo_dark: {
                $description =  _i('Logo for dark backgrounds');
                break;
            }
            case self::$mobile_light: {
                $description =  _i('Logo mobile for light backgrounds');
                break;
            }
            case self::$mobile_dark: {
                $description =  _i('Logo mobile for dark backgrounds');
                break;
            }
            case self::$background_1: {
                $description =  _i('Background 1');
                break;
            }
            case self::$background_2: {
                $description =  _i('background 2');
                break;
            }
            case self::$logo_1: {
                $description =  _i('Logo');
                break;
            }
            case self::$favicon: {
                $description =  _i('Favicon');
                break;
            }
            default: {
                $description = _i('Undefined');
            }
        }
        return $description;
    }
}
