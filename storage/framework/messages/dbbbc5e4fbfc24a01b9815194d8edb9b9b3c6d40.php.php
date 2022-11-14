<?php

namespace App\DotSuite\Import;

use App\Users\Entities\User;
use App\Users\Repositories\UsersRepo;
use Dotworkers\Configurations\Configurations;
use Dotworkers\Configurations\Enums\Codes;
use Dotworkers\Wallet\Wallet;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

/**
 * Class UserWalletImport
 *
 * This class allows to manage user Wallet requests
 *
 * @package App\DotSuite\Import
 * @author Carlos Hurtado
 */
class UserWalletImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsOnError
{
    use Importable, SkipsFailures, SkipsErrors;

    public $excludeUsers;

    public $currency;

    public $data = [];

    public $provider;

    /**
     * Create a new import instance.
     *
     * @return void
     */
    public function __construct($excludeUsers, $currency, $provider)
    {
        $this->excludeUsers = $excludeUsers;
        $this->currency = $currency;
        $this->provider = $provider;
    }

    /**
     * Collection
     *
     * @param array $rows Transaction data
     */
    public function collection(Collection $users)
    {
        $userRepo = new UsersRepo();
        $whitelabel = Configurations::getWhitelabel();
        foreach ($users as $user) {
            $userData = $userRepo->getUsernameByCurrency($user['username'], $user['currency_iso'], $whitelabel);
            if (!is_null($userData)) {
                $user = $userData->id;
                $userExcluder = $userRepo->findExcludeProviderUser($this->provider, $user, $this->currency);
                if(is_null($userExcluder)){
                    $userCurrency = $userData->currency_iso;
                    $walletData = Wallet::getByClient($user, $userCurrency);
                    if (!is_null($this->excludeUsers) && !in_array(null, $this->excludeUsers)) {
                        if (!in_array($user, $this->excludeUsers)) {
                            if ($walletData->code == Codes::$ok) {
                                $totalObject = new \stdClass();
                                $totalObject->userWallet = $walletData->data->wallet->id;
                                $totalObject->userId = $user;
                                $this->data[] = $totalObject;
                            }
                        }
                    } else {
                        if ($walletData->code == Codes::$ok) {
                            $totalObject = new \stdClass();
                            $totalObject->userWallet = $walletData->data->wallet->id;
                            $totalObject->userId = $user;
                            $this->data[] = $totalObject;
                        }
                    }
                }
            }
        }
        return $this->data;
    }

    /**
     * Rules
     *
     */
    public function rules(): array
    {
        return [
            'username' => 'required',
            'currency_iso' => 'required',
        ];
    }
}
