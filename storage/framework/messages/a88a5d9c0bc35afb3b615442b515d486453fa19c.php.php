<?php

namespace App\CRM\Enums;

/**
 * Class TypeUserLoadSegment
 *
 * This class allows to define segments
 *
 * @package App\CRM\Enums
 * @author Carlos Hurtado
 */
class TypeUserLoadSegment
{
    /**
     * Search
     *
     * @var int
     */
    public static $search = 1;

    /**
     * Excel
     *
     * @var int
     */
    public static $excel = 2;

    /**
     * All user
     *
     * @var int
     */
    public static $all_user = 3;
}
