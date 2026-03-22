<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = Role::create(['name'=>'ADMINISTRADOR']);

          //rutas para configuraciones
          /*Permission::create(['name'=>'admin.configuracion.index'])->syncRoles([$admin]);
          Permission::create(['name'=>'admin.configuracion.create'])->syncRoles([$admin]);
          Permission::create(['name'=>'admin.configuracion.store'])->syncRoles([$admin]);
          Permission::create(['name'=>'admin.configuracion.show'])->syncRoles([$admin]);
          Permission::create(['name'=>'admin.configuracion.edit'])->syncRoles([$admin]);
          Permission::create(['name'=>'admin.configuracion.update'])->syncRoles([$admin]);
          Permission::create(['name'=>'admin.configuracion.destroy'])->syncRoles([$admin]);
          */
    }
}
