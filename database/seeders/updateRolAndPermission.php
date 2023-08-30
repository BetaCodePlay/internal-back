<?php

namespace Database\Seeders;

use Dotworkers\Security\Enums\Roles;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class updateRolAndPermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $permission = DB::table('permissions')->insert([
//            'descriptions'=>'Update Rol Admin',
//            'depends'=>null,
//        ]);
//
//        DB::table('permission_role')->insert([
//            'permission_id'=>$permission->id,
//            'role_id'=>Roles::$super_admin,
//        ]);
    }
}
