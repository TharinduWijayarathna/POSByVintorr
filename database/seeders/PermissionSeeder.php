<?php

namespace Database\Seeders;

use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    protected $permission;
    // protected $db;

    public function __construct(DatabaseManager $db)
    {
        $this->permission = new Permission;
        // $this->db = $db;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $this->db->setDefaultConnection('mysql_main');
        $permissions = [
            // User
            [
                'name' => 'read_user',
                'group_name' => 'User Privileges',
            ],
            [
                'name' => 'create_user',
                'group_name' => 'User Privileges',
            ],
            [
                'name' => 'view_user',
                'group_name' => 'User Privileges',
            ],
            [
                'name' => 'update_user',
                'group_name' => 'User Privileges',
            ],
            [
                'name' => 'delete_user',
                'group_name' => 'User Privileges',
            ],
            [
                'name' => 'restore_user',
                'group_name' => 'User Privileges',
            ],

        ];

        foreach ($permissions as $permission) {
            $old_permission = $this->permission->where('name', $permission['name'])->first();
            if (! $old_permission) {
                $this->permission->create($permission);
            }
        }
    }
}
