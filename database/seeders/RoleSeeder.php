<?php

namespace Database\Seeders;

use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    protected $role;

    protected $db;

    public function __construct(DatabaseManager $db)
    {
        $this->role = new Role;
        $this->db = $db;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->db->setDefaultConnection('mysql_main');
        $roles = [
            [
                'name' => 'Admin',
                'guard_name' => 'web',
            ],
            [
                'name' => 'Cashier',
                'guard_name' => 'web',
            ],
        ];

        foreach ($roles as $role) {
            $old_role = $this->role->where('name', $role['name'])->first();
            if (! $old_role) {
                $this->role->create($role);
            }
        }
    }
}
