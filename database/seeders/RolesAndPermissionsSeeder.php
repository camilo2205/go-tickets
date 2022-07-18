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
        $dashboard = Permission::firstOrCreate(['name' => 'dashboard', 'description' => 'Permite navegar en el Inicio']);

        // Permisos Clientes
        $clientes_index = Permission::firstOrCreate(['name' => 'clientes.index', 'description' => 'Permite ver el listado de clientes.']);
        $clientes_create = Permission::firstOrCreate(['name' => 'clientes.create', 'description' => 'Permite crear un cliente.']);
        $clientes_edit = Permission::firstOrCreate(['name' => 'clientes.edit', 'description' => 'Permite editar un cliente.']);
        $clientes_show = Permission::firstOrCreate(['name' => 'clientes.show', 'description' => 'Permite ver un cliente.']);
        $clientes_destroy = Permission::firstOrCreate(['name' => 'clientes.destroy', 'description' => 'Permite eliminar un cliente.']);

        // Permisos Tickets
        $tickets_index = Permission::firstOrCreate(['name' => 'tickets.index', 'description' => 'Permite ver el listado de tickets.']);
        $tickets_create = Permission::firstOrCreate(['name' => 'tickets.create', 'description' => 'Permite crear un ticket.']);
        $tickets_edit = Permission::firstOrCreate(['name' => 'tickets.edit', 'description' => 'Permite editar un ticket.']);
        $tickets_show = Permission::firstOrCreate(['name' => 'tickets.show', 'description' => 'Permite ver un ticket.']);
        $tickets_destroy = Permission::firstOrCreate(['name' => 'tickets.destroy', 'description' => 'Permite eliminar un ticket.']);

        $superadmin = Role::firstOrCreate(['name' => 'superadmin', 'description' => 'SuperAdmin']);
        $admin = User::firstOrCreate(
            [
                'name' => 'Administrador',
                'email' => 'gotelemedicinasas@gmail.com',
                'direccion' => 'Manzana 50 Lote 4, Barrio Mogambo - Montería, Córdoba',
                'telefono' => '3108357893',
                'celular' => '3108357893',
                'identificacion' => '901.515.694-0'
            ],
            ['password' => bcrypt('G0Telemedicina')]
        );
        $admin->assignRole($superadmin);

        $cliente = Role::firstOrCreate(['name' => 'cliente', 'description' => 'Cliente']);
        $cliente->givePermissionTo([$dashboard, $tickets_index, $tickets_create, $tickets_edit, $tickets_show, $tickets_destroy]);
    }
}
