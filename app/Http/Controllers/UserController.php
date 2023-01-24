<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:users.index')->only('index');
        $this->middleware('can:users.create')->only(['create', 'store']);
        $this->middleware('can:users.edit')->only(['edit', 'update']);
        $this->middleware('can:users.show')->only('show');
        $this->middleware('can:users.destroy')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();
        $permisos = Permission::all();
        return view('users.create', compact('roles', 'permisos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(User::$rules);

        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'identificacion' => $request->identificacion,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono,
                'celular' => $request->celular,
                'password' => bcrypt($request->password)
            ]);
            $user->syncRoles($request->roles);
            $user->syncPermissions($request->permisos);
            DB::commit();
            return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
        } catch (\Exception $e) {
            Log::alert("Error al crear usuario", [$e->getMessage() => $e]);
            DB::rollback();
            return redirect()->back()->withInput()->with("error", 'Error no controlado, contacte al adminsitrador del sistema.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $permisos = Permission::all();
        return view('users.edit', compact('user', 'roles', 'permisos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'identificacion' => 'required',
            'name' => 'required',
            'direccion' => 'required',
            'telefono' => 'required',
            'celular' => 'required',
            'email' => [
                'required', 'email',
                Rule::unique('users', 'email')->ignore($user->id)
            ],
            'roles' => 'required'
        ]);

        try {
            DB::beginTransaction();
            if ($request->password) {
                $password = bcrypt($request->password);
                $user->update($request->except('password'));  
                $user->update(compact('password'));   
            } else {
                $user->update($request->except('password'));
            }
            $user->syncRoles($request->roles);
            $user->syncPermissions($request->permisos);

            DB::commit();
            return redirect()->route('users.index')->with('success', 'Usuario actualizado correctamente.');
        } catch (\Exception $e) {
            Log::alert("Error al crear usuario", [$e->getMessage() => $e]);
            DB::rollback();
            return redirect()->back()->withInput()->with("error", 'Error no controlado, contacte al adminsitrador del sistema.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
