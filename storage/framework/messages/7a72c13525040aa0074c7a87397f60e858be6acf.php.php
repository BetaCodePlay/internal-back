<?php

namespace App\Notifications\Import;

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
 * Class UsersNotificationImport
 *
 * This class allows to manage notifications requests
 *
 * @package App\Notifications\Import
 * @author Carlos Hurtado
 */
class UsersNotificationImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsOnError
{
    use Importable, SkipsFailures, SkipsErrors;

    public $whitelabel;

    public $data = [];

    /**
     * Create a new import instance.
     *
     * @return void
     */
    public function __construct($whitelabel)
    {
        $this->whitelabel = $whitelabel;
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
                $this->data[] = $dataUser->id;
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
        ];
    }
}
