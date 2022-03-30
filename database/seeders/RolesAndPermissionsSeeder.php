<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Dashboard
        $dashboard = Permission::firstOrCreate(['name' => 'dashboard']);

        // Permisos Clientes
        $clientes_index = Permission::firstOrCreate(['name' => 'clientes.index']);
        $clientes_create = Permission::firstOrCreate(['name' => 'clientes.create']);
        $clientes_edit = Permission::firstOrCreate(['name' => 'clientes.edit']);
        $clientes_show = Permission::firstOrCreate(['name' => 'clientes.show']);
        $clientes_destroy = Permission::firstOrCreate(['name' => 'clientes.destroy']);

        // Permisos Tickets
        $tickets_index = Permission::firstOrCreate(['name' => 'tickets.index']);
        $tickets_create = Permission::firstOrCreate(['name' => 'tickets.create']);
        $tickets_edit = Permission::firstOrCreate(['name' => 'tickets.edit']);
        $tickets_show = Permission::firstOrCreate(['name' => 'tickets.show']);
        $tickets_destroy = Permission::firstOrCreate(['name' => 'tickets.destroy']);

        $superadmin = Role::firstOrCreate(['name' => 'superadmin']);
        $admin = User::firstOrCreate([
            'name' => 'Administrador',
            'email' => 'gotelemedicinasas@gmail.com',
            'direccion' => 'Manzana 50 Lote 4, Barrio Mogambo - Montería, Córdoba',
            'telefono' => '3108357893',
            'celular' => '3108357893',
            'identificacion' => '901.515.694-0'],
            ['password' => bcrypt('G0Telemedicina')]
        );
        $admin->assignRole($superadmin);

        $cliente = Role::firstOrCreate(['name' => 'cliente']);
        $cliente->givePermissionTo([$dashboard, $tickets_index, $tickets_create, $tickets_edit, $tickets_show, $tickets_destroy]);
    }
}
