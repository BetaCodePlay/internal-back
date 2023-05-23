<?php

namespace App\CRM\Commands;

use App\CRM\Collections\SegmentsCollection;
use App\CRM\Repositories\SegmentsRepo;
use App\Users\Collections\UsersCollection;
use App\Users\Repositories\UsersRepo;
use Illuminate\Console\Command;

/**
 * Class UpdateSegments
 *
 *
 * @package App\CRM\Commands
 */
class UpdateSegments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crm:update-segments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'segments';

    /**
     *
     * @param SegmentsRepo $segmentsRepo
     * @param UsersRepo $usersRepo
     * @param UsersCollection $usersCollection
     * @param SegmentsCollection $segmentsCollection
     */
    public function handle(SegmentsCollection $segmentsCollection, SegmentsRepo $segmentsRepo, UsersRepo $usersRepo)
    {
        $segments = $segmentsRepo->allByWhitelabelAndActive();
        foreach ($segments as $segment) {
            if (!is_null($segment->filter)) {
                $filter = $segment->filter;
                $country = $filter['country'] ?? null;
                $excludeCountry = $filter['exclude_country'] ?? null;
                $balanceOptions = $filter['balance_options'] ?? null;
                $balance = $filter['balance'] ?? null;
                $depositsOptions = $filter['deposits_options'] ?? null;
                $deposits = $filter['deposits'] ?? null;
                $lastLoginOptions = $filter['last_login_options'] ?? null;
                $lastLogin = $filter['last_login'] ?? null;
                $lastDepositOptions = $filter['last_deposit_options'] ?? null;
                $lastDeposit = $filter['last_deposit'] ?? null;
                $lastWithdrawalOptions = $filter['last_withdrawal_options'] ?? null;
                $lastWithdrawal = $filter['last_withdrawal'] ?? null;
                $registrationOptions = $filter['registration_options'] ?? null;
                $registrationDate = $filter['registration_date'] ?? null;
                $playedOptions = $filter['played_options'] ?? null;
                $played = $filter['played'] ?? null;
                $fullProfile = $filter['full_profile'] ?? null;
                $currency = $filter['currency'] ?? null;
                $status = $filter['status'] ?? null;
                $language = $filter['language'] ?? null;

                $users = $usersRepo->getSegmentation($country, $currency, $excludeCountry, $status, $segment->whitelabel_id, $lastLoginOptions, $lastLogin, $lastDepositOptions, $lastDeposit, $lastWithdrawalOptions, $lastWithdrawal, $language, $registrationOptions, $registrationDate);
                $usersData = $segmentsCollection->formatSegmentationData($segment->whitelabel_id, $users, $depositsOptions, $deposits, $balanceOptions, $balance, $playedOptions, $played, $fullProfile, $filter);
                $usersDiff = array_diff($segment->data, $usersData['ids']);

                if (count($usersDiff) > 0) {
                    $data = [
                        'data' => $usersData['ids']
                    ];
                    $segmentsRepo->udpate($segment->id, $data);
                }
            }
        }
    }
}
