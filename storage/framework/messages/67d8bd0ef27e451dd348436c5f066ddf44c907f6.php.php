<?php


namespace App\BonusSystem;

use App\BonusSystem\Repositories\CampaignsRepo;
use App\BonusSystem\Repositories\RolloversTypesRepo;
use Carbon\Carbon;
use Dotworkers\Configurations\Enums\ProviderTypes;
use Dotworkers\Configurations\Utils;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Campaigns
 *
 * This class allows manage campaigns static functions
 *
 * @package App\BonusSystem
 * @author  Damelys Espinoza
 */
class Campaigns
{
    /**
     * Determines if a new version of the campaign should be generated
     *
     * @param $request
     * @return bool|Response
     */
    public static function version($request)
    {
        try {
            $campaignsRepo = new CampaignsRepo();
            $rolloversTypesRepo = new RolloversTypesRepo();
            $campaignData = $campaignsRepo->find($request->id);
            $rolloversData = $rolloversTypesRepo->find($request->rollover_id);
            $timezone = session('timezone');
            $version = false;
            $requestData = $request->all();

            foreach ($requestData as $data) {
                switch ($data) {
                    case $request->start_date:
                    {
                        $requestStartDate = Carbon::createFromFormat('d-m-Y h:i a', $request->start_date, $timezone)->setTimezone('UTC')->format('Y-m-d H:i:s');
                        $version = $requestStartDate != $campaignData->start_date;
                        break;
                    }
                    case $request->end_date:
                    {
                        $requestEndDate = !is_null($request->end_date) ? Carbon::createFromFormat('d-m-Y h:i a', $request->end_date, $timezone)->setTimezone('UTC')->format('Y-m-d H:i:s') : null;
                        $version = $requestEndDate != $campaignData->end_date;
                        break;
                    }
                    case $request->currency:
                    {
                        $version = $request->currency != $campaignData->currency_iso;
                        break;
                    }
                    case $request->allocation_criteria:
                    {
                        if (isset($campaignData->data->allocation_criteria) && !is_null($campaignData->data->allocation_criteria)) {
                            $countAllocationCriteria = count($request->allocation_criteria);
                            $countAllocationCriteriaData = count($campaignData->data->allocation_criteria);
                            if ($countAllocationCriteria == $countAllocationCriteriaData) {
                                foreach ($request->allocation_criteria as $key => $allocationCriteria) {
                                    if (!is_null($allocationCriteria)) {
                                        $criteria = $campaignData->data->allocation_criteria;

                                        if ($criteria[$key] != $allocationCriteria) {
                                            $version = true;
                                        }
                                    }
                                }
                            } else {
                                $version = true;
                            }
                        }
                        break;
                    }
                    case $request->deposit_type:
                    {
                        // NO GENERA
                        $version = (isset($campaignData->data->deposit_type) && $request->deposit_type != $campaignData->data->deposit_type);
                        break;
                    }
                    case $request->min_deposit:
                    {
                        $min = (float)$request->min_deposit;
                        if (isset($campaignData->data->min) && $min != $campaignData->data->min) {
                            $version = true;
                        } else {
                            if (!isset($campaignData->data->min) && !is_null($request->min_deposit)) {
                                $version = true;
                            }
                        }
                        break;
                    }
                    case $request->users_restriction_type:
                    {
                        if (!is_null($request->users_restriction_type) && !isset($campaignData->data->users_restriction_type)) {
                            $version = true;
                        } else {
                            if (isset($campaignData->data->users_restriction_type) && $request->users_restriction_type != $campaignData->data->users_restriction_type) {
                                $version = true;
                            }
                        }
                        break;
                    }
                    case $request->bonus_code:
                    {
                        $codeBonus = strtoupper($request->bonus_code);
                        if (isset($campaignData->data->code) && $codeBonus != $campaignData->data->code) {
                            $version = true;
                        } else {
                            if (!isset($campaignData->data->code) && !is_null($request->bonus_code)) {
                                $version = true;
                            }
                        }
                        break;
                    }
                    case $request->bonus:
                    {
                        if (isset($campaignData->data->bonus) && (float)$request->bonus != $campaignData->data->bonus) {
                            $version = true;
                        } else {
                            if (!isset($campaignData->data->bonus) && !is_null($request->bonus)) {
                                $version = true;
                            }
                        }
                        break;
                    }
                    case $request->percentage:
                    {
                        if (!is_null($request->percentage)) {
                            $percentage = $request->percentage / 100;
                        } else {
                            $percentage = null;
                        }
                        if (isset($campaignData->data->percentage) && $percentage != $campaignData->data->percentage) {
                            $version = true;
                        } else {
                            if (!isset($campaignData->data->percentage) && !is_null($request->percentage)) {
                                $version = true;
                            }
                        }
                        break;
                    }
                    case $request->limit:
                    {
                        $limit = (float)$request->limit;
                        if (isset($campaignData->data->limit) && $limit != $campaignData->data->limit) {
                            $version = true;
                        } else {
                            if (!isset($campaignData->data->limit) && !is_null($request->limit)) {
                                $version = true;
                            }
                        }
                        break;
                    }
                    case $request->max_balance_convert:
                    {
                        $maxBalanceConvert = (float)$request->max_balance_convert;
                        if (isset($campaignData->data->max_balance_convert) && $maxBalanceConvert != $campaignData->data->max_balance_convert) {
                            $version = true;
                        } else {
                            if (!isset($campaignData->data->max_balance_convert) && !is_null($request->max_balance_convert)) {
                                $version = true;
                            }
                        }
                        break;
                    }
                    case $request->bonus_type_awarded:
                    {
                        if ($request->bonus_type_awarded != $campaignData->data->bonus_type_awarded) {
                            $version = true;
                        }
                        break;
                    }
                    case $request->include_payment_methods:
                    {
                        if (isset($campaignData->data->include_payment_methods) && !is_null($campaignData->data->include_payment_methods)) {
                            $countIncludePaymentMethods = count($request->include_payment_methods);
                            $countIncludePaymentMethodsData = count($campaignData->data->include_payment_methods);
                            if ($countIncludePaymentMethods == $countIncludePaymentMethodsData) {
                                foreach ($request->include_payment_methods as $key => $includePaymentMethods) {
                                    if (!is_null($includePaymentMethods)) {
                                        $includePayment = $campaignData->data->include_payment_methods;

                                        if ($includePayment[$key] != $includePaymentMethods) {
                                            $version = true;
                                        }
                                    }
                                }
                            } else {
                                $version = true;
                            }
                        } else {
                            if (!isset($campaignData->data->include_payment_methods) && !is_null($request->include_payment_methods)) {
                                $version = true;
                            }
                        }
                        break;
                    }
                    case $request->exclude_payment_methods:
                    {
                        if (isset($campaignData->data->exclude_payment_methods) && !is_null($campaignData->data->exclude_payment_methods)) {
                            $countExcludePaymentMethods = count($request->exclude_payment_methods);
                            $countExcludePaymentMethodsData = count($campaignData->data->exclude_payment_methods);
                            if ($countExcludePaymentMethods == $countExcludePaymentMethodsData) {
                                foreach ($request->exclude_payment_methods as $key => $excludePaymentMethods) {
                                    if (!is_null($excludePaymentMethods)) {
                                        $excludePayment = $campaignData->data->exclude_payment_methods;

                                        if ($excludePayment[$key] != $excludePaymentMethods) {
                                            $version = true;
                                        }
                                    }
                                }
                            } else {
                                $version = true;
                            }
                        } else {
                            if (!isset($campaignData->data->exclude_payment_methods) && !is_null($request->exclude_payment_methods)) {
                                $version = true;
                            }
                        }
                        break;
                    }
                    case $request->bonus_type:
                    {
                        if (isset($campaignData->bonus_type_id) && $request->bonus_type != $campaignData->bonus_type_id) {
                            $version = true;
                        } else {
                            if (!isset($campaignData->bonus_type_id) && !is_null($request->bonus_type)) {
                                $version = true;
                            }
                        }
                        break;
                    }
                    case $request->odd:
                    {
                        if (isset($campaignData->data->odd) && $request->odd != $campaignData->data->odd) {
                            $version = true;
                        } else {
                            if (!isset($campaignData->data->odd) && !is_null($request->odd)) {
                                $version = true;
                            }
                        }
                        break;
                    }
                    case $request->provider_type:
                    {
                        if (!is_null($rolloversData)) {
                            if ($request->provider_type != $rolloversData->provider_type_id) {
                                $version = true;
                            }
                        } else {
                            if (!is_null($request->provider_type) && $request->complete_rollovers == 'yes') {
                                $version = true;
                            }
                        }
                        break;
                    }
                    case $request->days:
                    {
                        if (!is_null($rolloversData)) {
                            if ($request->days != $rolloversData->days) {
                                $version = true;
                            }
                        } else {
                            if (!is_null($request->days)) {
                                $version = true;

                            }
                        }
                        break;
                    }
                    case $request->exclude_providers:
                    {
                        if (!is_null($rolloversData) && !is_null($rolloversData->exclude_providers)) {
                            $countExcludeProviders = $request->exclude_providers;
                            $countExcludeProvidersData = $rolloversData->exclude_providers;
                            if ($countExcludeProviders == $countExcludeProvidersData) {
                                foreach ($request->exclude_providers as $key => $excludeProviders) {
                                    if (!is_null($excludeProviders)) {
                                        $exclude = $rolloversData->exclude_providers;

                                        if ($exclude[$key] != $excludeProviders) {
                                            $version = true;
                                        }
                                    }
                                }
                            } else {
                                $version = true;
                            }
                        }
                        break;
                    }
                    case $request->promo_codes:
                    {
                        if (!empty($request->promo_codes) && isset($campaignData->data->promo_codes) && $campaignData->data->promo_codes != []) {
                            $countPromoCodes = count($request->promo_codes);
                            $countPromoCodesData = count($campaignData->data->promo_codes);
                            if ($countPromoCodes == $countPromoCodesData) {
                                foreach ($request->promo_codes as $key => $promoCode) {
                                    if (!is_null($promoCode['promo_code'])) {
                                        $code = strtoupper($promoCode['promo_code']);
                                        $campaignCodes = $campaignData->data->promo_codes;

                                        if ($campaignCodes[$key]->promo_code != $code) {
                                            $version = true;
                                        }
                                    }
                                }
                            } else {
                                $version = true;
                            }
                        } else {
                            if (!is_null($request->promo_codes) && count($campaignData->data->promo_codes) == 0) {
                                $version = true;
                            }
                        }
                        break;
                    }
                    case $request->bet:
                    {
                        if (isset($campaignData->data->total_bets)) {
                            if ($request->bet != $campaignData->data->total_bets) {
                                $version = true;
                            }
                        } else {
                            if (!is_null($request->bet)) {
                                $version = true;

                            }
                        }
                        break;
                    }
                    case $request->provider_type_bet:
                    {
                        if (isset($campaignData->data->provider_type)) {
                            if ($request->provider_type_bet != $campaignData->data->provider_type) {
                                $version = true;
                            }
                        } else {
                            if (!is_null($request->provider_type_bet)) {
                                $version = true;

                            }
                        }
                        break;
                    }
                    case $request->exclude_providers_bet:
                    {
                        if (!isset($campaignData->data->exclude_providers)) {
                            $countExcludeProviders = $request->exclude_providers_bet;
                            $countExcludeProvidersData = $campaignData->data->exclude_providers;
                            if ($countExcludeProviders == $countExcludeProvidersData) {
                                foreach ($request->exclude_providers_bet as $key => $excludeProviders) {
                                    if (!is_null($excludeProviders)) {
                                        $exclude = $campaignData->data->exclude_providers;

                                        if ($exclude[$key] != $excludeProviders) {
                                            $version = true;
                                        }
                                    }
                                }
                            } else {
                                $version = true;
                            }
                        }
                        break;
                    }
                    default:
                    {
                        $completeRollovers = $request->complete_rollovers == 'yes';
                        if ($completeRollovers != $campaignData->data->rollovers) {
                            $version = true;
                            break;
                        }

                        if (isset($campaignData->data->include_payment_methods) && !isset($request->include_payment_methods)) {
                            $version = true;
                            break;
                        }

                        if (isset($campaignData->data->exclude_payment_methods) && !isset($request->exclude_payment_methods)) {
                            $version = true;
                            break;
                        }

                        if (!isset($campaignData->data->simple) && !isset($request->bet_type)) {
                            if ($request->provider_type == ProviderTypes::$sportbook) {
                                $version = true;
                                break;
                            }
                        } else {
                            if ($request->provider_type == ProviderTypes::$sportbook) {
                                if ($request->bet_type != 1) {
                                    if ($request->bet_type == 'true') {
                                        $betType = true;
                                    } else {
                                        $betType = false;
                                    }
                                } else {
                                    $betType = null;
                                }
                                if (isset($campaignData->data->simple)) {
                                    if ($betType != $campaignData->data->simple) {
                                        $version = true;
                                        break;
                                    }
                                }
                            }
                        }

                        if (isset($campaignData->data->promo_codes) && count($campaignData->data->promo_codes) > 1 && !isset($request->promo_codes)) {
                            $version = true;
                            break;
                        }

                        if (is_null($rolloversData) && isset($request->exclude_providers)) {
                            $version = true;
                            break;
                        } else {
                            if (!is_null($rolloversData) && isset($request->exclude_providers) && !is_null($request->exclude_providers)) {
                                $version = true;
                                break;
                            }
                        }
                        break;
                    }
                }
            }
            return $version;

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }

    /**
     * Without version
     *
     * @param $request
     * @return bool|Response
     */
    public static function withoutVersion($request)
    {
        try {
            $campaignsRepo = new CampaignsRepo();
            $campaignData = $campaignsRepo->find($request->id);
            $externalName = $request->internal_name;

            $campaignOriginal = [
                'name' => $externalName,
                'translations' => json_decode($request->translations)
            ];
            $campaignVersion = [
                'name' => $externalName,
                'translations' => $request->translations
            ];
            if (!is_null($campaignData->parent_campaign)) {
                $campaignsRepo->update($campaignData->parent_campaign, $campaignOriginal);
                $campaignsRepo->updateWithoutVersion($campaignData->parent_campaign, $campaignVersion);
            }

        } catch (\Exception $ex) {
            \Log::error(__METHOD__, ['exception' => $ex]);
            return Utils::failedResponse();
        }
    }
}
