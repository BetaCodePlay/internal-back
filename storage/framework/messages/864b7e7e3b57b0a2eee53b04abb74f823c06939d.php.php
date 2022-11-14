<?php

namespace App\Users\Import;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

/**
 * Class TransactionByLot
 *
 * This class allows manage users imports
 *
 * @package App\Users\Import
 * @author  Carlos Hurtado
 */
class ImportUsers implements ToCollection, WithHeadingRow, WithValidation, SkipsOnFailure, SkipsOnError
{
    use Importable, SkipsFailures, SkipsErrors;

    /**
     * @var array
     */
    public $data = [];

    /**
     * @param Collection $rows
     * @return array
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $totalObject = new \stdClass();
            $totalObject->username = $row['username'];
            $this->data[] = $totalObject;
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
            'username' => 'required'
        ];
    }
}
