<?php

namespace App\Whitelabels\Collections;

use App\Whitelabels\Enums\Status;

/**
 * Class WhitelabelsCollection
 *
 * This class allows to format whitelabels data
 *
 * @package App\Whitelabels\Collections
 * @author  Eborio Linarez
 */
class WhitelabelsCollection
{
    /**
     * Format status
     *
     * @param array $whitelabels Whitelabels data
     * @param array $status Status data
     */
    public function formatStatus($whitelabels, $status)
    {
        foreach ($whitelabels as $whitelabel) {
            $whitelabelStatus = $whitelabel->status;
            $whitelabel->status = sprintf(
                '<select name="status" class="form-control change-whitelabels" data-whitelabel="%s">',
                $whitelabel->id
            );

            foreach ($status as $item) {
                $selected = $item->id == $whitelabelStatus ? 'selected' : '';
                $whitelabel->status .= sprintf(
                    '<option value="%s" %s>%s</option>',
                    $item->id,
                    $selected,
                    Status::getName($item->id)
                );
            }
            $whitelabel->status .= '</select>';
            $whitelabel->status_wl = Status::getName($whitelabelStatus);
        }
    }
}
