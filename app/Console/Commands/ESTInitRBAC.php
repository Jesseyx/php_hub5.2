<?php

namespace App\Console\Commands;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;

class ESTInitRBAC extends BaseCommand
{
    // Entrust is an RBAC library... RBAC = "Role Based Access Control"

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'est:init-rbac';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Initialize Role Based Access Control';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 如果没有第一个用户的话，创建新的，允许此命令多次运行
        $user = User::first();
        if (!$user) {
            $this->error('Users table is empty');
            return;
        }

        // 创始人
        $founder    = Role::addRole('Founder', 'Founder');
        // 维护者
        $maintainer = Role::addRole('Maintainer', 'Maintainer');
        // 贡献者
        $contributor = Role::addRole('Contributor', 'Contributor');

        $visit_admin   = Permission::addPermission('visit_admin', 'Visit Admin');
        $manage_users  = Permission::addPermission('manage_users', 'Manage Users');
        $manage_topics = Permission::addPermission('manage_topics', 'Manage Topics');

        $this->attachPermissions($founder, [
            $visit_admin,
            $manage_users,
            $manage_topics,
        ]);

        $this->attachPermissions($maintainer, [
            $visit_admin,
            $manage_topics,
        ]);

        if (!$user->hasRole($founder->name)) {
            $user->attachRole($founder);
        }

        $this->info('--');
        $this->info('Initialize RABC success -- ID: 1 and Name “{$user->name}” has founder permission');
        $this->info('--');
    }

    /**
     * @param Role         $role
     * @param Permission[] $permissions
     */
    private function attachPermissions(Role $role, array $permissions)
    {
        $attach = [];

        $permissions = new Collection($permissions);

        $detach = [];
        foreach ($role->perms()->get() as $permission) {
            if ($permissions->where('name', $permission->name)->isEmpty()) {
                $detach[] = $permission;
            }
        }

        foreach ($permissions as $permission) {
            if (!$role->hasPermission($permission->name)) {
                $attach[] = $permission;
            }
        }

        $role->detachPermissions($detach);
        $role->attachPermissions($attach);
    }
}
