<?php

namespace App\Core\Collections;

use Dotworkers\Configurations\Enums\Providers;

class ProvidersCollection
{
    /**
     * Format providers cash flow data
     *
     * @param array $providers providers data
     * @return array
     */
    public function formatProviders($providers)
    {
        if (count((array)$providers) > 0) {
            foreach ($providers as $key => $provider) {
                $provider->status = sprintf(
                    '<div class="checkbox checkbox-primary">
                          <input class="update_checkbox %s" id="status_%s" value="" type="checkbox" %s data-id="%s" data-name="status" data-url="" />
                                            <label for="status_%s">&nbsp;</label>
                    </div>', ($provider->status ? 'active' : ''), $provider->id, ($provider->status ? 'checked' : ''), $provider->id, $provider->id
                );
            }
        }

    }

}
