<?php

namespace App\Core\Collections;

use Dotworkers\Configurations\Enums\ProviderTypes;

/**
 * Class ProviderTypesCollection
 *
 * This class allows to format provider types data
 *
 * @package App\Core\Collections
 * @author  Eborio Linarez
 */
class ProviderTypesCollection
{
    /**
     * Format provider types
     *
     * @param array $providerTypes Provider types data
     */
    public function formatProviderTypes($providerTypes)
    {
        foreach ($providerTypes as $providerType) {
            $providerType->name = ProviderTypes::getName($providerType->id);
        }
    }
}
