<?php

namespace Dotworkers\Configurations\Repositories;

use Dotworkers\Configurations\Entities\TemplateElement;

/**
 * Class TemplateElementsRepo
 *
 * This class allows to manage TemplateElement entity data
 *
 * @package Dotworkers\Configurations\Repositories
 * @author  Eborio Linarez
 */
class TemplateElementsRepo
{
    /**
     * Find element by name
     *
     * @param string $name Element name
     * @return mixed
     */
    public function findByName($name)
    {
        $element = TemplateElement::where('name', $name)
            ->first();
        return $element;
    }
}