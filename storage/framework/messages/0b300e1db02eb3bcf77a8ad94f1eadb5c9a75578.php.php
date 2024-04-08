<?php

namespace App\Core\Enums;

class Languages
{
    /**
     * English
     *
     * @var string
     */
    private static $en_US = 'en_US';

    /**
     * Spanish
     *
     * @var string
     */
    private static $es_ES = 'es_ES';

    /**
     * Portuguese
     *
     * @var string
     */
    private static $pt_BR = 'pt_BR';

    /**
     * Hebrew
     *
     * @var string
     */
    private static $he_IL = 'he_IL';

    /**
     * French
     *
     * @var string
     */
    private static $fr_FR = 'fr_FR';

    /**
     * Turkish
     *
     * @var string
     */
    private static $tr_TR = 'tr_TR';

    /**
     * British English
     *
     * @var string
     */
    private static $en_GB = 'en_GB';

    /**
     * Chilean Spanish
     *
     * @var string
     */
    private static $es_CL = 'es_CL';

    /**
     * Argentinian Spanish
     *
     * @var string
     */
    private static $es_AR = 'es_AR';

    /**
     * Get language name
     *
     * @param string $iso Language ISO
     * @return string
     */
    public static function getName($iso)
    {
        switch ($iso) {
            case self::$en_US:
            case self::$en_GB:
            {
                return _i('English');
                break;
            }
            case self::$es_ES:
            case self::$es_CL:
            {
                return _i('Spanish');
                break;
            }
            case self::$pt_BR:
            {
                return _i('Portuguese');
                break;
            }
            case self::$he_IL:
            {
                return _i('Hebrew');
                break;
            }
            case self::$fr_FR:
            {
                return _i('French');
                break;
            }
            case self::$tr_TR:
            {
                return _i('Turkish');
                break;
            }
            case self::$es_AR:
            {
                return _i('Argentinian');
                break;
            }
        }
    }
}
