<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use App\Rules\EmailDomainRule;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'name'     => $row['name'],
            'email'    => $row['email'],
            'password' => bcrypt($row['password']),
        ]);
    }

    public function rules(): array
    {
        return [
            '*.name'     => 'required|min:3|max:200',
            '*.email'    => 'required|email|unique:users,email', new EmailDomainRule(),
            '*.password' => 'required|min:8|max:200',
        ];
    }
}

