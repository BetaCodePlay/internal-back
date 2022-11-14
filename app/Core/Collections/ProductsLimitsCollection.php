<?php

namespace App\Core\Collections;

use App\Core\Enums\ProductsLimits;

/**
 * Class ProductsLimitsCollection
 *
 * This class allows to format pages data
 *
 * @package App\Core\Collections
 * @author  Eborio Linarez
 */
class ProductsLimitsCollection
{
    /**
     * Format all
     *
     * @param array $limits Limits data
     */
    public function formatAll($limits)
    {
        $timezone = session('timezone');
        foreach ($limits as $limit) {
            $limit->limits = '<ul>';
            $limit->limits .= sprintf(
               '<li><strong>%s:</strong> %s</li>',
                _i('Min bet'),
                $limit->data->min_bet
            );
            $limit->limits .= sprintf(
                '<li><strong>%s:</strong> %s</li>',
                _i('Max bet'),
                $limit->data->max_bet
            );
            $limit->limits .= sprintf(
                '<li><strong>%s:</strong> %s</li>',
                _i('Max selections'),
                $limit->data->max_selections
            );
            $limit->limits .= sprintf(
                '<li><strong>%s:</strong> %s</li>',
                _i('Max selections not favorites'),
                $limit->data->max_selections_not_favorites
            );
            $limit->limits .= sprintf(
                '<li><strong>%s:</strong> %s</li>',
                _i('Straight bet limit'),
                $limit->data->straight_bet_limit
            );
            $limit->limits .= sprintf(
                '<li><strong>%s:</strong> %s</li>',
                _i('Parlay bet limit'),
                $limit->data->parlay_bet_limit
            );
            $limit->limits .= '</ul>';

            $limit->title = ProductsLimits::getName($limit->id);
            $limit->created = $limit->created_at->setTimezone($timezone)->format('d-m-Y H:i:s');
            $limit->updated = $limit->updated_at->setTimezone($timezone)->format('d-m-Y H:i:s');
            $limit->actions = sprintf(
                '<a href="%s" class="btn u-btn-3d btn-sm u-btn-bluegray mr-2"><i class="hs-admin-pencil"></i> %s</a>',
                route('providers-limits.edit', [$limit->whitelabel_id, $limit->currency_iso, $limit->provider_id]),
                _i('Edit')
            );
        }
    }

    /**
     * Format details
     *
     * @param object $limit Limit data
     */
    public function formatDetails($limit)
    {
        $limit->title = ProductsLimits::getName($limit->id);
    }
}
