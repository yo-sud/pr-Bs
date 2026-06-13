<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class AdminUsuarioController extends Controller
{
    public function index()
    {
        try {
            $usuarios = User::where('status',1)->orderBy('name')->simplePaginate(20);
            return view('admin.usuarios.index', compact('usuarios'));

        } catch (Exception $err) {
            //Guarda la falla real en secreto y avisa al usuario
            Log::error('Error obteniendo la lista de usuarios: ' . $err->getMessage());
            return back()->withInput()->with('error', 'Ocurrió un error al cargar la lista.');
        }
    }

    public function create()
    {
        try {
            // Al no depender de otra tabla, va directo a la vista sin buscar nada más
            return view('admin.usuarios.create');

        } catch (Exception $err) {
            Log::error('Error al cargar formulario: ' . $err->getMessage());
            return back()->withInput()->with('error', 'Ocurrió un error al cargar el formulario.');
        }
    }

    public function store(Request $request)
    {
        // Revisa que las cajas de texto cumplan las reglas (correo único y rol válido)
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role'     => ['required', 'string', 'in:admin,cliente']
        ]);

        try {
            // Crea la fila en la tabla 'users' encriptando la contraseña por seguridad
            User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make('12345678'), 
                'role'     => $request->role,
                'status'   => 1,
            ]);

            return redirect()->route('admin.usuarios.index')->with('success', 'Usuario creado.');

        } catch (Exception $err) {
            Log::error('Error creando un usuario: ' . $err->getMessage());
            return back()->withInput()->with('error', 'Ocurrió un error al crear.');
        }
    }

    public function show(string $id)
    {
        try {
            $usuario = User::find($id);
            return view('admin.usuarios.show', compact('usuario'));

        } catch (Exception $err) {
            Log::error('Error obteniendo un usuario: ' . $err->getMessage());
            return back()->withInput()->with('error', 'Ocurrió un error al buscar el detalle.');
        }
    }

    public function edit(string $id)
    {        
        try {
            $usuario = User::find($id);
            return view('admin.usuarios.edit', compact('usuario'));

        } catch (Exception $err) {
            Log::error('Error obteniendo usuario para editar: ' . $err->getMessage());
            return back()->withInput()->with('error', 'Ocurrió un error al cargar los datos.');
        }
    }

    public function update(Request $request, string $id)
    {
        // El correo debe ser único, pero ignora el ID del usuario editado para que no choque consigo mismo
        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'role'  => ['required', 'string', 'in:admin,cliente']
        ]);

        try {
            $usuario = User::find($id);

            // Reemplaza los datos de texto básicos
            $usuario->name = $request->name;
            $usuario->email = $request->email;
            $usuario->role = $request->role;
            
            // Si la caja no está vacía, la valida, la encripta y la actualiza
            if ($request->filled('password')) {
                $request->validate(['password' => ['string', 'min:8']]);
                $usuario->password = Hash::make($request->password);
            }

            $usuario->save();
            return redirect()->route('admin.usuarios.index')->with('success', 'Usuario actualizado.');

        } catch (Exception $err) {
            Log::error('Error al actualizar un usuario: ' . $err->getMessage());
            return back()->withInput()->with('error', 'Ocurrió un error al guardar cambios.');
        }
    }

    public function destroy(string $id)
    {
        try {
            if (auth()->id() == $id) {
                return back()->with('error', '¡Cuidado, estas desactivando la cuenta principal!');
            }
            $usuario = User::find($id);
            // En lugar de usar ->delete(), cambiamos su estado a 0 (Inactivo/Desactivado)
            $usuario->status = 0;
            $usuario->save();

            return redirect()->route('admin.usuarios.index')->with('success', 'Cuenta del usuario desactivada.');

        } catch (Exception $err) {
            Log::error('Error al desactivar un usuario: ' . $err->getMessage());
            return back()->withInput()->with('error', 'Ocurrió un error al intentar desactivar la cuenta.');
        }
    }
}