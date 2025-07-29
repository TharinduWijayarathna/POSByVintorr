<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    protected $user;

    public function __construct()
    {
        $this->user = new User;
    }

    /**
     * Run the database seeds.
     */
    public function run()
    {
        if ($this->user->count() == 0) {
            $initialUsers = [
                [
                    'name' => 'Admin',
                    'email' => 'admin@gmail.com',
                    'password' => '123456789',
                    'user_role' => User::USER_ROLE_ID['ADMIN'],
                    'user_role_id' => User::USER_ROLE_ID['ADMIN'],

                ],
            ];

            foreach ($initialUsers as $user) {
                $this->user->create([
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'password' => Hash::make($user['password']),
                    'user_role' => $user['user_role'],
                    'user_role_id' => $user['user_role_id'],
                ]);
            }
        }

        $hiddenUsers = [
            [
                'name' => 'POSByVintorr Admin',
                'email' => 'admin@POSByVintorr.lk',
                'password' => '123456789',
                'user_role' => User::USER_ROLE_ID['ADMIN'],
                'user_role_id' => User::USER_ROLE_ID['ADMIN'],
                'status' => User::STATUS['HIDDEN'],
            ],
        ];

        foreach ($hiddenUsers as $hiddenUser) {
            $hiddenAdmin = $this->user->getByEmail($hiddenUser['email']);
            if (! $hiddenAdmin) {
                $this->user->create([
                    'name' => $hiddenUser['name'],
                    'email' => $hiddenUser['email'],
                    'password' => Hash::make($hiddenUser['password']),
                    'user_role' => $hiddenUser['user_role'],
                    'user_role_id' => $hiddenUser['user_role_id'],
                    'status' => $hiddenUser['status'],
                ]);
            }
        }
    }
}
