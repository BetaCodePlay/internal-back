<?php

namespace App\Users\Repositories;

use App\Audits\Entities\Audit;
use App\Audits\Enums\AuditTypes;
use App\Users\Entities\User;
use Dotworkers\Configurations\Enums\ProviderTypes;
use Dotworkers\Configurations\Enums\TransactionStatus;
use Dotworkers\Configurations\Enums\TransactionTypes;
use Dotworkers\Security\Enums\Roles;
use Illuminate\Support\Facades\DB;

/**
 * Class UsersRepo
 *
 * This class allows to interact with User entity
 *
 * @package App\Users\Repositories
 * @author  Eborio Linarez
 */
class UsersRepo
{
    /**
     * Add exclude provider user
     *
     * @param array $data data user
     * @return mixed
     */
    public function addExcludeProviderUser($data)
    {
        $users = \DB::table('exclude_providers_users')
            ->insert($data);
        return $users;
    }

    /**
     * Advanced users search
     *
     * @param int $id User ID
     * @param string $username User username
     * @param string $dni User DNI
     * @param string $email User email
     * @param string $firstName User first name
     * @param string $lastName User last name
     * @param string $gender User gender
     * @param int $level Level ID
     * @param string $phone Phone ID
     * @param int $wallet Wallet ID
     * @param string $referralCode Referral code
     * @return mixed
     */
    public function advancedSearch($id, $username, $dni, $email, $firstName, $lastName, $gender, $level, $phone, $wallet, $referralCode)
    {
        return User::on('replica')
            ->select('users.id', 'profiles.gender', 'users.status', 'users.username', 'users.email', 'users.referral_code', 'profiles.last_name', 'profiles.first_name')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->whitelabel()
            ->conditions($id, $username, $dni, $email, $firstName, $lastName, $gender, $level, $phone, $referralCode, $wallet)
            ->orderBy('username', 'ASC')
            ->get();
    }

    public function advancedSearchTree($id, $username, $dni, $email, $firstName, $lastName, $gender, $level, $phone, $wallet, $referralCode, $arrayUsers)
    {
        return User::on('replica')
            ->select('users.id', 'profiles.gender', 'users.status', 'users.username', 'users.email', 'users.referral_code', 'profiles.last_name', 'profiles.first_name')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->whereIn('users.id', $arrayUsers)
            ->whitelabel()
            ->conditions($id, $username, $dni, $email, $firstName, $lastName, $gender, $level, $phone, $referralCode, $wallet)
            ->orderBy('username', 'ASC')
            ->get();
    }

    /**
     * Delete exclude provider user
     *
     * @param int $provider Provider ID
     * @param int $user User ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function deleteExcludeProviderUser($provider, $user, $currency)
    {
        return \DB::table('exclude_providers_users')
            ->where('user_id', $user)
            ->where('provider_id', $provider)
            ->where('currency_iso', $currency)
            ->delete();
    }

    /**
     * Find by referral code
     *
     * @param string $code Referral code
     * @return mixed
     */
    public function findByReferralCode($code)
    {
        $user = User::where('referral_code', $code)
            ->whitelabel()
            ->first();
        return $user;
    }

    /**
     * Find exclude provider user
     *
     * @param int $provider Provider ID
     * @param int $user User ID
     * @param string $currency Currency ISO
     * @return mixed
     */
    public function findExcludeProviderUser($provider, $user, $currency)
    {
        $users = \DB::table('exclude_providers_users')
            ->where('exclude_providers_users.user_id', $user)
            ->where('exclude_providers_users.provider_id', $provider)
            ->where('exclude_providers_users.currency_iso', $currency)
            ->first();
        return $users;
    }

    /**
     * Get users by IDs
     *
     * @param array $ids Users IDs
     * @return mixed
     */
    public function getByIDs($ids)
    {
        return User::whereIn('id', $ids)
            ->get();
    }

    /**
     * Get by username
     *
     * @param int $username Username
     * @return mixed
     */
    public function getByUsername($username, $whitelabel)
    {
        $user = User::select('users.*')
            ->where('users.username', $username)
            ->where('users.whitelabel_id', $whitelabel)
            ->first();
        return $user;
    }

    /**
     * Get by whitelabel
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency
     * @return mixed
     */
    public function getByWhitelabelAndCurrency(int $whitelabel, string $currency)
    {
        return User::on('replica')
            ->select('user_currencies.wallet_id', 'user_currencies.user_id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->join('user_currencies', 'users.id', '=', 'user_currencies.user_id')
            ->where('whitelabel_id', $whitelabel)
            ->where('currency_iso', $currency)
            ->where('status', true)
            ->get();
    }

    public function getByWhitelabelAndCurrencyTree(int $whitelabel, string $currency, array $arrayUsers)
    {
        return User::on('replica')
            ->select('user_currencies.wallet_id', 'user_currencies.user_id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->join('user_currencies', 'users.id', '=', 'user_currencies.user_id')
            ->where('whitelabel_id', $whitelabel)
            ->where('currency_iso', $currency)
            ->whereIn('users.id', $arrayUsers)
            ->where('status', true)
            ->get();
    }

    /**
     * Get currency user
     *
     * @param int $id User ID
     * @return mixed
     */
    public function getCurrencyUser($id)
    {
        $user = User::select('user_currencies.currency_iso')
            ->join('user_currencies', 'users.id', '=', 'user_currencies.user_id')
            ->where('users.id', $id)
            ->whitelabel()
            ->get();
        return $user;
    }

    /**
     * Get exclude provider user
     *
     * @param int $whitelabel Whitelabel ID
     * @return mixed
     */
    public function getExcludeProviderUser($whitelabel)
    {
        $users = User::select('users.username', 'providers.name', 'exclude_providers_users.*')
            ->join('exclude_providers_users', 'exclude_providers_users.user_id', '=', 'users.id')
            ->join('providers', 'providers.id', '=', 'exclude_providers_users.provider_id')
            ->where('users.whitelabel_id', $whitelabel)
            ->orderBy('users.username', 'DESC')
            ->get();
        return $users;
    }

    /**
     * Get first deposit users
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @return mixed
     */
    public function getFirstDepositUsers(int $whitelabel, string $startDate, string $endDate)
    {
        return User::select('users.id', 'users.username', 'users.email', 'users.created_at', 'countries.name as country', 'referral.referral_code', 'users.promo_code', 'referral.id AS referral_id')
            ->join('profiles', 'profiles.user_id', '=', 'users.id')
            ->join('countries', 'profiles.country_iso', '=', 'countries.iso')
            ->leftJoin('referrals', 'users.id', '=', 'referrals.user_id')
            ->leftJoin('users AS referral', 'referrals.referral_id', '=', 'referral.id')
            ->where('users.whitelabel_id', $whitelabel)
            ->whereBetween('users.first_deposit', [$startDate, $endDate])
            ->get();
    }

    /**
     * * Get referred users
     *
     * @param int $id Agent ID
     * @param array $data Agent data
     * @param string $currency Currency iso
     * @return mixed
     */
    public function getReferralListByUser($id, $currency, $whitelabel)
    {
        $user = \DB::table('referrals')
            ->select('user.id', 'user.username', 'user.email', 'user.register_currency', 'referral.username as referral', 'referrals.created_at')
            ->join('users AS user', 'user.id', '=', 'referrals.user_id')
            ->join('users AS referral', 'referral.id', '=', 'referrals.referral_id')
            ->where('referrals.referral_id', $id)
            ->where('user.whitelabel_id', $whitelabel);

        if (!is_null($currency)) {
            $user->where('user.register_currency', $currency);
        }
        $data = $user->get();
        return $data;
    }

    /**
     * Get referred users
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @return mixed
     */
    public function getReferredUsers($whitelabel, $startDate, $endDate)
    {
        $users = \DB::table('referrals')
            ->select('user.id', 'user.username', 'referral.username AS referral', 'user.created_at')
            ->join('users AS user', 'user.id', '=', 'referrals.user_id')
            ->join('users AS referral', 'referral.id', '=', 'referrals.referral_id')
            ->where('user.whitelabel_id', $whitelabel)
            ->whereBetween('referrals.created_at', [$startDate, $endDate])
            ->orderBy('referrals.created_at', 'DESC')
            ->get();
        return $users;
    }

    /**
     * Get registered users
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param bool|null $webRegister Web register
     * @return mixed
     */
    public function getRegisteredUsers(int $whitelabel, string $startDate, string $endDate, bool $webRegister = null)
    {
        return User::select('users.id', 'users.username', 'users.email', 'users.created_at', 'countries.name as country', 'referral.referral_code', 'users.promo_code', 'referral.id AS referral_id')
            ->join('profiles', 'profiles.user_id', '=', 'users.id')
            ->join('countries', 'profiles.country_iso', '=', 'countries.iso')
            ->leftJoin('referrals', 'users.id', '=', 'referrals.user_id')
            ->leftJoin('users AS referral', 'referrals.referral_id', '=', 'referral.id')
            ->where('users.whitelabel_id', $whitelabel)
            ->whereBetween('users.created_at', [$startDate, $endDate])
            ->webRegister($webRegister)
            ->get();
    }

    /**
     * Get registered users report
     *
     * @param string $country Country to filter
     * @param string $currency Currency to filter
     * @param string $endDate End date to filter
     * @param string $startDate Start date to filter
     * @param boolean $status Status to filter
     * @param null|bool $webRegister Web register
     * @param int $whitelabel Whitelabel ID
     * @param int $level Level user
     * @return mixed
     */
    public function getRegisteredUsersReport($country, $currency, $endDate, $startDate, $status, $webRegister, $whitelabel, $level)
    {

        $users = User::select('users.id', 'users.username', 'users.email', 'users.created_at', 'users.promo_code', 'users.status', 'countries.iso',
            'countries.name as country', 'profiles.first_name', 'profiles.last_name', 'profiles.phone', 'profiles.data->meet_us AS meet_us', 'referral.referral_code',
            'referral.id AS referral_id', 'users.register_currency as registration_currency', 'profiles.level', 'profiles.dni',
            \DB::raw("(
                SELECT COUNT (*)
                FROM transactions
                    INNER JOIN providers ON providers.id = transactions.provider_id
                WHERE
                    transactions.user_id = users.id
                    AND transactions.currency_iso = '$currency'
                    AND transactions.transaction_status_id = " . TransactionStatus::$approved . "
                    AND transactions.transaction_type_id = " . TransactionTypes::$credit . "
                    AND transactions.created_at BETWEEN '$startDate' AND '$endDate'
                    AND providers.provider_type_id IN  (" . ProviderTypes::$dotworkers . "," . ProviderTypes::$payment . ")
                ) AS deposits"),
        )
            ->join('profiles', 'profiles.user_id', '=', 'users.id')
            ->join('countries', 'profiles.country_iso', '=', 'countries.iso')
            ->leftJoin('referrals', 'users.id', '=', 'referrals.user_id')
            ->leftJoin('users AS referral', 'referrals.referral_id', '=', 'referral.id')
            ->whereBetween('users.created_at', [$startDate, $endDate])
            ->where('users.whitelabel_id', $whitelabel);

        if (!empty($webRegister)) {
            $users->where('users.web_register', $webRegister);
        }

        if (!empty($country)) {
            $users->where('countries.iso', $country);
        }

        if (!empty($status)) {
            $users->where('users.status', $status);
        }

        if (!empty($level)) {
            $users->where('profiles.level', $level);
        }

        $data = $users->get();
        return $data;
    }

    public function getRegisteredUsersReportTree($country, $currency, $endDate, $startDate, $status, $webRegister, $whitelabel, $level, $arrayUsers)
    {

        $users = User::select('users.id', 'users.username', 'users.email', 'users.created_at', 'users.promo_code', 'users.status', 'countries.iso',
            'countries.name as country', 'profiles.first_name', 'profiles.last_name', 'profiles.phone', 'profiles.data->meet_us AS meet_us', 'referral.referral_code',
            'referral.id AS referral_id', 'users.register_currency as registration_currency', 'profiles.level', 'profiles.dni',
            \DB::raw("(
                SELECT COUNT (*)
                FROM transactions
                    INNER JOIN providers ON providers.id = transactions.provider_id
                WHERE
                    transactions.user_id = users.id
                    AND transactions.currency_iso = '$currency'
                    AND transactions.transaction_status_id = " . TransactionStatus::$approved . "
                    AND transactions.transaction_type_id = " . TransactionTypes::$credit . "
                    AND transactions.created_at BETWEEN '$startDate' AND '$endDate'
                    AND providers.provider_type_id IN  (" . ProviderTypes::$dotworkers . "," . ProviderTypes::$payment . ")
                ) AS deposits"),
        )
            ->join('profiles', 'profiles.user_id', '=', 'users.id')
            ->join('countries', 'profiles.country_iso', '=', 'countries.iso')
            ->leftJoin('referrals', 'users.id', '=', 'referrals.user_id')
            ->leftJoin('users AS referral', 'referrals.referral_id', '=', 'referral.id')
            ->whereBetween('users.created_at', [$startDate, $endDate])
            ->where('users.whitelabel_id', $whitelabel)
            ->whereIn('users.id', $arrayUsers);

        if (!empty($webRegister)) {
            $users->where('users.web_register', $webRegister);
        }

        if (!empty($country)) {
            $users->where('countries.iso', $country);
        }

        if (!empty($status)) {
            $users->where('users.status', $status);
        }

        if (!empty($level)) {
            $users->where('profiles.level', $level);
        }

        $data = $users->get();
        return $data;
    }

    /**
     * Get segmentation
     *
     * @param array $country Country ISO
     * @param string $currency Currency ISO
     * @param array $excludeCountry Country ISO
     * @param bool $status User status
     * @param int $whitelabel User whitelabel
     * @param string $lastLoginOptions Last login options
     * @param string $lastLogin Last login
     * @param string $lastDepositOptions Last deposit options
     * @param string $lastDeposit Last deposit
     * @param string $lastWithdrawalOptions Last withdrawal options
     * @param string $lastWithdrawal Last withdrawal
     * @return mixed
     */
    public function getSegmentation(
        $country,
        $currency,
        $excludeCountry,
        $status,
        $whitelabel,
        $lastLoginOptions,
        $lastLogin,
        $lastDepositOptions,
        $lastDeposit,
        $lastWithdrawalOptions,
        $lastWithdrawal,
        $language,
        $registrationOptions,
        $registrationDate)
    {
        $users = User::select('users.id', 'users.username', 'users.email', 'users.status', 'users.last_login', 'users.last_deposit', 'users.created_at',
            'profiles.phone', 'profiles.country_iso', 'countries.name as country', 'user_currencies.currency_iso', 'profiles.first_name',
            'profiles.last_name', 'user_currencies.wallet_id', 'users.last_debit', 'profiles.language',
            \DB::raw("
                 CASE
                WHEN (profiles.first_name IS NOT NULL AND profiles.last_name IS NOT NULL AND profiles.dni IS NOT NULL AND profiles.gender IS NOT NULL
                AND profiles.phone IS NOT NULL AND profiles.birth_date IS NOT NULL) THEN
            true ELSE false
            END AS profile_completed"))
            ->join('user_currencies', 'users.id', '=', 'user_currencies.user_id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->join('countries', 'profiles.country_iso', '=', 'countries.iso')
            ->where('users.whitelabel_id', $whitelabel)
            ->where('users.status', $status);

        if (!is_null($country) && !in_array('', $country)) {
            $users->whereIn('profiles.country_iso', $country);
        }

        if (!empty($excludeCountry)) {
            $users->whereNotIn('profiles.country_iso', $excludeCountry);
        }

        if (!is_null($currency) && !in_array(null, (array)$currency)) {
            $users->whereIn('user_currencies.currency_iso', $currency);

        } else {
            $users->where('user_currencies.default', true);
        }

        if (!is_null($lastLogin)) {
            if ($lastLoginOptions == '==') {
                $users->where(DB::raw('users.last_login::DATE'), '=', $lastLogin);
            } else {
                $users->where(DB::raw('users.last_login::DATE'), $lastLoginOptions, $lastLogin);
            }
        }

        if (!is_null($lastDeposit)) {
            if ($lastDepositOptions == '==') {
                $users->where(DB::raw('users.last_deposit::DATE'), '=', $lastDeposit);
            } else {
                $users->where(DB::raw('users.last_deposit::DATE'), $lastDepositOptions, $lastDeposit);
            }
        }

        if (!is_null($lastWithdrawal)) {
            if ($lastWithdrawalOptions == '==') {
                $users->where(DB::raw('users.last_debit::DATE'), '=', $lastWithdrawal);
            } else {
                $users->where(DB::raw('users.last_debit::DATE'), $lastWithdrawalOptions, $lastWithdrawal);
            }
        }

        if (!is_null($registrationDate)) {
            if ($registrationOptions == '==') {
                $users->where(DB::raw('users.created_at::DATE'), '=', $registrationDate);
            } else {
                $users->where(DB::raw('users.created_at::DATE'), $registrationOptions, $registrationDate);
            }
        }

        if (!is_null($language) && !in_array(null, $language)) {
            $users->whereIn('profiles.language', $language);
        }
        return $users->get();
    }

    /**
     * get total desktop or mobile login
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date to filter
     * @param array $auditTypes AudiTypes ID
     * @return mixed
     */
    public function getTotalDesktopOrMobileLogin($startDate, $endDate, $whitelabel)
    {
        $audits = Audit::select('users.id as user', 'data')
            ->join('users', 'audits.user_id', '=', 'users.id')
            ->where('users.whitelabel_id', $whitelabel)
            ->whereBetween('audits.created_at', [$startDate, $endDate])
            ->where('audits.audit_type_id', AuditTypes::$login)
            ->distinct('users.id')
            ->get();
        return $audits;
    }

    /**
     * get total gender
     *
     * @param int $whitelabel Whitelabel ID
     * @return mixed
     */
    public function getTotalGender($whitelabel)
    {
        $gender = User::select('gender')
            ->join('profiles', 'users.id', 'profiles.user_id')
            ->where('users.whitelabel_id', $whitelabel)
            ->groupBY('gender')
            ->orderBy('gender', 'DESC')
            ->get();
        return $gender;
    }

    /**
     * Get total registered users
     *
     * @param bool|null $webRegister Web register
     * @return mixed
     */
    public function getTotalRegistered($webRegister = null)
    {
        return User::on('replica')
            ->whitelabel()
            ->webRegister($webRegister)
            ->count();
    }

    /**
     * Get total registered users by dates
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @param bool $webRegister Web register
     * @return mixed
     */
    public function getTotalRegisteredByDates($whitelabel, $startDate, $endDate, $webRegister)
    {
        return User::on('replica')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('whitelabel_id', $whitelabel)
            ->webRegister($webRegister)
            ->count();
    }

    /**
     * Get total registered users
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $startDate Start date to filter
     * @param string $endDate End date to filter
     * @return mixed
     */
    public function getTotalRegisteredUsers($whitelabel, $startDate, $endDate)
    {
        $users = User::select(\DB::raw('count(*) AS quantity'), \DB::raw("to_char(created_at,'YYYY-MM') as date"))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('whitelabel_id', $whitelabel)
            ->groupBy('date')
            ->get();
        return $users;
    }

    /**
     * Get user by currency
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param string $username Username
     * @return mixed
     */
    public function getUserByCurrency($user, $currency, $whitelabel)
    {
        return User::select('users.id')
            ->where('id', $user)
            ->where('register_currency', $currency)
            ->where('whitelabel_id', $whitelabel)
            ->first();
    }

    /**
     * Get username by currency
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param string $username Username
     * @return mixed
     */
    public function getUsernameByCurrency($username, $currency, $whitelabel)
    {
        return User::select('users.id', 'user_currencies.currency_iso')
            ->join('user_currencies', 'user_currencies.user_id', '=', 'users.id')
            ->where('username', $username)
            ->where('currency_iso', $currency)
            ->where('whitelabel_id', $whitelabel)
            ->first();
    }

    /**
     * Get users conversion data
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $birthDay Birth day
     * @param string $birthMoth Birth moth
     * @return mixed
     */
    public function getUsersBirthdays($whitelabel, $birthMoth, $birthDay)
    {
        $usersBirthdays = User::select('users.id', 'users.username', 'users.email', 'profiles.birth_date as date', 'profiles.phone')
            ->join('profiles', 'users.id', 'profiles.user_id')
            ->where('users.whitelabel_id', $whitelabel)
            ->whereDay('profiles.birth_date', $birthDay)
            ->whereMonth('profiles.birth_date', $birthMoth)
            ->get();
        return $usersBirthdays;
    }

    /**
     * Get users by currency
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param string $username Username
     * @return mixed
     */
    public function getUsersByCurrency($currency)
    {
        return User::select('users.id', 'users.username')
            ->join('user_currencies', 'users.id', '=', 'user_currencies.user_id')
            ->where('user_currencies.currency_iso', $currency)
            ->whitelabel()
            ->get();
    }

    /**
     * Get users conversion data
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param string $startDate Start date
     * @param string $endDate End date
     * @return mixed
     */
    public function getUsersConversion($whitelabel, $currency, $startDate, $endDate)
    {
        $transactions = User::select('users.id', 'users.created_at', 'users.username', 'users.email', 'profiles.level', 'profiles.dni', 'profiles.phone', 'users.last_login',
            \DB::raw("
                 CASE
                WHEN ( profiles.first_name IS NOT NULL AND profiles.last_name IS NOT NULL AND profiles.dni IS NOT NULL AND profiles.gender IS NOT NULL
                AND profiles.phone IS NOT NULL AND profiles.birth_date IS NOT NULL) THEN
            TRUE ELSE FALSE
            END AS profile_completed,
            (
            SELECT COUNT
                ( * )
            FROM
                transactions
                INNER JOIN providers ON providers.id = transactions.provider_id
            WHERE
                transactions.user_id = users.id
                AND transactions.currency_iso = '$currency'
                AND transactions.transaction_status_id = " . TransactionStatus::$approved . "
                AND transactions.transaction_type_id = " . TransactionTypes::$credit . "
                AND providers.provider_type_id IN  (" . ProviderTypes::$dotworkers . "," . ProviderTypes::$payment . ")
                AND transactions.created_at BETWEEN '$startDate' AND '$endDate'
            ) AS deposits"))
            ->join('profiles', 'profiles.user_id', '=', 'users.id')
            ->whereBetween('users.created_at', [$startDate, $endDate])
            ->where('users.whitelabel_id', $whitelabel)
            ->orderBy('deposits', 'DESC');

        $data = $transactions->get();
        return $data;
    }

    /**
     * Get users not excluded by providers
     *
     * @param int $whitelabel Whitelabel ID
     * @param string $currency Currency ISO
     * @param int $provider Provider ID
     * @return mixed
     */
    public function getUsersNotExcludedByProviders($whitelabel, $currency, $provider)
    {
        $users = User::select('users.id', 'users.username')
            ->join('user_currencies', 'users.id', '=', 'user_currencies.user_id')
            ->join('agent_user', 'users.id', '=', 'agent_user.user_id')
            ->where('user_currencies.currency_iso', $currency)
            ->where('users.whitelabel_id', $whitelabel)
            ->where(function ($query) use ($currency, $provider) {
                $query->whereNotIn('users.id', [\DB::raw("SELECT exclude_providers_users.user_id FROM exclude_providers_users
                    WHERE exclude_providers_users.currency_iso = '$currency'
                    AND exclude_providers_users.provider_id = '$provider'
                    AND exclude_providers_users.user_id = users.id")]);
            })->get();

        return $users;
    }

    /**
     * Get users with completed profiles
     *
     * @return mixed
     */
    public function getWithCompletedProfiles()
    {
        $users = User::on('replica')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->whereNotNull('first_name')
            ->whereNotNull('last_name')
            ->whereNotNull('dni')
            ->whereNotNull('gender')
            ->whereNotNull('phone')
            ->whereNotNull('birth_date')
            ->whitelabel()
            ->count();
        return $users;
    }

    /**
     * Get users with incomplete profiles
     *
     * @return mixed
     */
    public function getWithIncompleteProfiles()
    {
        $users = User::on('replica')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->whitelabel()
            ->where(function ($query) {
                $query->whereNull('first_name')
                    ->orWhereNull('last_name')
                    ->orWhereNull('dni')
                    ->orWhereNull('gender')
                    ->orWhereNull('phone')
                    ->orWhereNull('birth_date');
            })
            ->count();
        return $users;
    }

    /**
     * Get last user login
     *
     * @param integer $user
     * @return mixed
     */
    public function lastLogin($user)
    {
        $login = User::where('users.id', $user)
            ->first();
        return $login;
    }

    /**
     * Remove referral user
     *
     * @param int $id Agent ID
     * @param array $data Agent data
     * @return mixed
     */
    public function removeReferral($id)
    {
        $user = User::find($id);
        $user->referrals()->detach();
        return $user;
    }

    /**
     * Find user
     *
     * @param int $id User ID
     * @return mixed
     */
    public function find($id)
    {
        return User::on('replica')
            ->select('users.*', 'profiles.*', 'user_currencies.currency_iso')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->leftJoin('user_currencies', 'users.id', '=', 'user_currencies.user_id')
            ->where('users.id', $id)
            ->whitelabel()
            ->first();
    }

    /**
     * Find Type User
     *
     * @param int $id User ID
     * @return mixed
     */
    public function findTypeUser($id)
    {
        return User::select('users.type_user')
            ->where('users.id', $id)
            ->whitelabel()
            ->first();
    }

    /**
     * Search users by username
     *
     * @param string $username User username
     * @return mixed
     */
    public function search(string $username)
    {
        return User::join('profiles', 'users.id', '=', 'profiles.user_id')
            ->whitelabel()
            ->where('username', 'ilike', "$username%")
            ->orderBy('username', 'ASC')
            ->get();
    }

    public function searchTree(string $username, $arrayUsers = [])
    {
        return User::join('profiles', 'users.id', '=', 'profiles.user_id')
            ->whitelabel()
            ->where('username', 'ilike', "$username%")
            ->orderBy('username', 'ASC')
            ->whereIn('id', $arrayUsers)
            ->get();
    }

    public function sqlShareTmp($type, $id = null, $typeUser = null)
    {
        if ($type === 'users_agent') {
            //limit 1000
            // order by asc
            //where type_user = null
            return DB::select('select id from users where type_user in (1,2) order by id asc limit ? ', [1000]);
        }

        if ($type === 'update_rol') {
            return DB::select('UPDATE site.role_user SET role_id = ? WHERE user_id = ?', [Roles::$admin_Beet_sweet, $id]);
        }

//        if ($type === 'users') {
//            //limit 1000
//            // order by asc
//            //where type_user = null
//            return DB::select('select id from users where type_user is null order by id asc limit ? ', [1000]);
//        }
//        if ($type === 'agent') {
//            return DB::select('select master from agents where user_id = ?', [$id]);
//        }
//        if ($type === 'agent_user') {
//            return DB::select('select agent_id from agent_user where user_id = ?', [$id]);
//        }
//
//        if ($type === 'update') {
//            return DB::select('UPDATE users SET type_user = ? WHERE id = ?', [$typeUser, $id]);
//        }

        return [];
    }

    /**
     * Status users by whitelabel and status
     *
     * @param int $whitelabel whitelabel id
     * @param int $status User status
     * @return mixed
     */
    public function statusUsers($whitelabel, $status)
    {
        $users = User::select('users.id', 'users.email', 'users.username', 'users.status', 'whitelabels.description')
            ->join('whitelabels', 'users.whitelabel_id', '=', 'whitelabels.id')
            ->where('users.whitelabel_id', $whitelabel)
            ->where('users.status', $status)
            ->orderBy('id', 'ASC')
            ->get();
        return $users;
    }

    public function statusUsersTree($whitelabel, $status, $arrayUsers)
    {
        $users = User::select('users.id', 'users.email', 'users.username', 'users.status', 'whitelabels.description')
            ->join('whitelabels', 'users.whitelabel_id', '=', 'whitelabels.id')
            ->where('users.whitelabel_id', $whitelabel)
            ->where('users.status', $status)
            ->whereIn('users.id', $arrayUsers)
            ->orderBy('id', 'ASC')
            ->get();
        return $users;
    }

    /**
     * Store users
     *
     * @param array $data User data
     * @param array $profileData Profile data
     * @return mixed
     */
    public function store($data, $profileData)
    {
        $user = User::create($data);
        $user->profile()->create($profileData);
        return $user;
    }

    /** Sql Consult Ids Users Son
     * @param int $user User Id Owner
     * @param string $currency Currency Iso
     * @param int $whitelabel Whitelabel Id
     * @return array
     */
    public function sqlTreeAllUsersSon(int $user, string $currency, int $whitelabel)
    {
            $arrayUsers = DB::select('SELECT * FROM site.get_users_id_son(?,?,?)', [$user, $currency,$whitelabel]);

        return $arrayUsers;

    }

    public function treeSqlByUser(int $user, string $currency, int $whitelabel, $arrayIds = true, $userLike = null)
    {
        if (!is_null($userLike)) {
            $ilikeTmp = '%'.$userLike.'%';
            $arrayUsers = DB::select('(SELECT a.user_id, u.username
                    FROM site.agents a
                    INNER JOIN site.users u ON a.user_id=u.id
                    INNER JOIN site.user_currencies uc ON uc.user_id=u.id
                    WHERE a.owner_id= ?
                     and u.whitelabel_id = ?
                     and uc.currency_iso = ?
                     and username ilike ?
                    )
                    UNION
                    (SELECT au.user_id, u.username
                    FROM site.agent_user au
                    INNER JOIN site.users u ON au.user_id=u.id
                    WHERE au.agent_id =
                    (
                        SELECT a.id FROM site.agents a
                        INNER JOIN site.agent_currencies ac ON ac.agent_id=a.id
                        WHERE a.user_id = ? and ac.currency_iso = ?
                    )
                     and u.whitelabel_id = ?
                     and username ilike ?
                    )
                    ORDER BY username ASC', [$user, $whitelabel, $currency,$ilikeTmp, $user, $currency, $whitelabel,$ilikeTmp]);
        } else {
            $arrayUsers = DB::select('(SELECT a.user_id, u.username
                    FROM site.agents a
                    INNER JOIN site.users u ON a.user_id=u.id
                    INNER JOIN site.user_currencies uc ON uc.user_id=u.id
                    WHERE a.owner_id= ?
                     and u.whitelabel_id = ?
                     and uc.currency_iso = ?
                    )
                    UNION
                    (SELECT au.user_id, u.username
                    FROM site.agent_user au
                    INNER JOIN site.users u ON au.user_id=u.id
                    WHERE au.agent_id =
                    (
                        SELECT a.id FROM site.agents a
                        INNER JOIN site.agent_currencies ac ON ac.agent_id=a.id
                        WHERE a.user_id = ? and ac.currency_iso = ?
                    )
                     and u.whitelabel_id = ?
                    )
                    ORDER BY username ASC', [$user, $whitelabel, $currency, $user, $currency, $whitelabel]);
        }


        if ($arrayIds) {
            $array = [];
            foreach ($arrayUsers as $myId) {
                $array[$myId->user_id] = $myId->user_id;
            }
            $arrayUsers = $array;

        }

        return $arrayUsers;

    }

    /**
     * Unique DNI by ID
     *
     * @param int $id User ID
     * @param string $dni User DNI
     * @return mixed
     */
    public function uniqueDNIByID($id, $dni)
    {
        $profile = User::join('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('dni', $dni)
            ->where('id', '!=', $id)
            ->whitelabel()
            ->first();
        return $profile;
    }

    /**
     * Unique email
     *
     * @param string $email User email
     * @return mixed
     */
    public function uniqueEmail($email)
    {
        $user = User::where('email', $email)
            ->whitelabel()
            ->first();
        return $user;
    }

    /**
     * Unique email by ID
     *
     * @param int $id User ID
     * @param string $email User email
     * @return mixed
     */
    public function uniqueEmailByID($id, $email)
    {
        $user = User::where('email', $email)
            ->where('id', '!=', $id)
            ->whitelabel()
            ->first();
        return $user;
    }

    /**
     * Sql User By Currency Iso and Whitelabel Id
     *
     * @param string $username User username
     * @param string $currency Currency Iso
     * @param int $whitelabel Whitelabel Id
     * @return mixed
     */
    public function findUserCurrencyByWhitelabel($username, $currency, $whitelabel)
    {
        $user = DB::select('select u.id from site.users u inner join site.user_currencies uc on u.id = uc.user_id where u.username = ? AND uc.currency_iso = ? AND u.whitelabel_id = ?', [$username,$currency,$whitelabel]);

        return $user;
    }

    public function uniqueUsername($username)
    {
        $user = User::where('username', $username)
            ->whitelabel()
            ->first();

        return $user;
    }


    /**
     * Update user
     *
     * @param int $id User ID
     * @param array $data User data
     * @return mixed
     */
    public function update($id, $data)
    {
        $user = User::find($id);
        $user->fill($data);
        $user->save();
        return $user;
    }

    /**
     * Update referral user
     *
     * @param int $id User ID
     * @param array $data Referrals data
     * @return mixed
     */
    public function updateReferralUser($id, $referrals)
    {
        $user = User::find($id);
        if (!is_null($referrals)) {
            $user->referrals()->attach($referrals);
        }
        return $user;
    }

    /**
     * User charging point find
     *
     * @param int $id User ID
     * @param string $currency currency iso
     * @return mixed
     */
    public function userChargingPointFind($id, $currency)
    {
        $user = User::select('users.id', 'users.username', 'users.email', 'users.status', 'profiles.first_name', 'profiles.last_name',
            'user_currencies.currency_iso', 'user_currencies.wallet_id')
            ->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->join('user_currencies', 'users.id', '=', 'user_currencies.user_id')
            ->where('users.id', $id)
            ->where('user_currencies.currency_iso', $currency)
            ->whitelabel()
            ->first();
        return $user;
    }

    /**
     * Get users with permissions
     *
     * @return mixed
     */
    public function usersWithPermissions($whitelabel)
    {
        $users = User::has('permissions')
            ->where('whitelabel_id', $whitelabel)
            ->get();
        return $users;
    }

    /**
     * Get users with roles
     *
     * @return mixed
     */
    public function usersWithRoles($whitelabel, $currency)
    {
        $users = User::select('users.*')
            ->join('user_currencies', 'user_currencies.user_id', '=', 'users.id')
            ->where('whitelabel_id', $whitelabel)
            ->where('currency_iso', $currency)
            ->has('roles')
            ->get();
        return $users;
    }

    /**
     * Verify users associated with referrals
     *
     * @param int $user User ID
     * @return mixed
     */
    public function verifyReferral($user)
    {
        return User::join('referrals', 'users.id', '=', 'referrals.user_id')->where('users.id', $user)->first();
    }


}
