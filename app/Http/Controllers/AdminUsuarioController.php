<?php

namespace App\Http\Controllers;

// MODELOS Y HERRAMIENTAS: Importación de base de datos, logs y seguridad
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class AdminUsuarioController extends Controller
{
    /**
     * LISTADO PRINCIPAL: Carga y muestra los usuarios paginados de 2 en 2.
     */
    public function index()
    {
        try {
            $usuarios = User::where('status',1)->orderBy('name')->simplePaginate(20);
            return view('admin.usuarios.index', compact('usuarios'));

        } catch (Exception $err) {
            // CAPTURA DE ERROR: Guarda la falla real en secreto y avisa al usuario
            Log::error('Error obteniendo la lista de usuarios: ' . $err->getMessage());
            return back()->withInput()->with('error', 'Ocurrió un error al cargar la lista.');
        }
    }

    /**
     * VISTA CREAR: Muestra el formulario vacío para registrar un usuario.
     */
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

    /**
     * ACCIÓN GUARDAR: Valida los datos del formulario e inserta el nuevo usuario.
     */
    public function store(Request $request)
    {
        // FILTRO DE SEGURIDAD: Revisa que las cajas de texto cumplan las reglas (correo único y rol válido)
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role'     => ['required', 'string', 'in:admin,cliente']
        ]);

        try {
            // INSERCIÓN: Crea la fila en la tabla 'users' encriptando la contraseña por seguridad
            User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make('password'), 
                'role'     => $request->role,
            ]);

            return redirect()->route('admin.usuarios.index')->with('success', 'Usuario creado.');

        } catch (Exception $err) {
            Log::error('Error creando un usuario: ' . $err->getMessage());
            return back()->withInput()->with('error', 'Ocurrió un error al crear.');
        }
    }

    /**
     * VISTA DETALLE: Busca y muestra la ficha técnica de un usuario por su ID.
     */
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

    /**
     * VISTA EDITAR: Busca al usuario y abre el formulario relleno con sus datos actuales.
     */
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

    /**
     * ACCIÓN ACTUALIZAR: Valida y sobreescribe los cambios en la base de datos.
     */
    public function update(Request $request, string $id)
    {
        // FILTRO DE EDICIÓN: El correo debe ser único, pero ignora el ID del usuario editado para que no choque consigo mismo
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
            
            // CAMBIO DE CONTRASEÑA: Si la caja no está vacía, la valida, la encripta y la actualiza
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

    /**
     * ACCIÓN ELIMINAR(desactivar): Cambia a desactivado
     */
    public function destroy(string $id)
    {
        try {
            $usuario = User::find($id);

            // BORRADO LÓGICO: En lugar de usar ->delete(), cambiamos su estado a 0 (Inactivo/Desactivado)
            $usuario->status = 0;
            $usuario->save();

            return redirect()->route('admin.usuarios.index')->with('success', 'Cuenta del usuario desactivada.');

        } catch (Exception $err) {
            Log::error('Error al desactivar un usuario: ' . $err->getMessage());
            return back()->withInput()->with('error', 'Ocurrió un error al intentar desactivar.');
        }
    }
}