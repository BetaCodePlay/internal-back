<?php

namespace App\CRM\Import;

use App\Users\Repositories\UsersRepo;
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
class UsersImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsOnError
{
    use Importable, SkipsFailures, SkipsErrors;

    public $whitelabel;

    public $segment;

    public $data = [];

    public $duplicate = 0;

    public $notDuplicate = 0;

    /**
     * Create a new import instance.
     *
     * @return void
     */
    public function __construct($whitelabel, $usersId)
    {
        $this->whitelabel = $whitelabel;
        $this->segmentUsersId = $usersId;
    }

    /**
     * Collection
     *
     * @param array $rows Transaction data
     */
    public function collection(Collection $users)
    {
        $userRepo = new UsersRepo();
        foreach ($users as $user) {
            $dataUser = $userRepo->getByUsername($user['username'], $this->whitelabel);
            if (!is_null($dataUser)) {
                 $dataUser->id;
                if (in_array($dataUser->id, $this->segmentUsersId)) {
                    $this->duplicate++;
                } else {
                    if(!is_null($user)) {
                        $this->notDuplicate++;
                        $this->segmentUsersId[] = $dataUser->id;
                    }
                }
            }
        }
        $this->data['usersId'] = $this->segmentUsersId;
        $this->data['duplicate'] = $this->duplicate;
        $this->data['notDuplicate'] = $this->notDuplicate;
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
        ];
    }
}
