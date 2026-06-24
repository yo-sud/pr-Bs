<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Buscamos los proveedores ordenados por el nuevo campo y paginados
            $proveedores = Proveedor::query()
                ->withCount('libros')
                ->orderBy('nombre_empresa')
                ->simplePaginate(15);

            return view('admin.proveedores.index', compact('proveedores'));

        } catch (Exception $err) {
            Log::error('Error obteniendo la lista de proveedores: ' . $err->getMessage());
            return back()->withInput()
                         ->with('error', 'Ocurrió un error al intentar obtener la lista de proveedores.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Retorna la vista para crear un proveedor nuevo
        return view('admin.proveedores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Autorización integrada (Solo administradores)
        if (!$request->user()?->isAdmin()) {
            abort(403, 'Acción no autorizada.');
        }

        // Validación estricta usando tus nuevos campos del Modelo
        $request->validate([
            'nombre_empresa'     => ['required', 'string', 'max:150'],
            'ruc'                => ['nullable', 'string', 'size:11', 'unique:proveedores,ruc'],
            'telefono'           => ['nullable', 'string', 'max:20'],
            'correo'             => ['nullable', 'email', 'max:100', 'unique:proveedores,correo'],
            'contacto_ejecutivo' => ['nullable', 'string', 'max:100'],
            'observaciones'      => ['nullable', 'string'],
        ]);

        try {
            Proveedor::create([
                'nombre_empresa'     => $request->nombre_empresa,
                'ruc'                => $request->ruc,
                'telefono'           => $request->telefono,
                'correo'             => $request->correo,
                'contacto_ejecutivo' => $request->contacto_ejecutivo,
                'observaciones'      => $request->observaciones,
                'activo'             => true // Inicia activo por defecto
            ]);

            return redirect()->route('proveedores.index')
                             ->with('success', 'Proveedor creado exitosamente.');

        } catch (Exception $err) {
            Log::error('Error creando un proveedor: ' . $err->getMessage());
            return back()->withInput()
                         ->with('error', 'Ocurrió un error al crear un proveedor.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $proveedor = Proveedor::find($id);

            return view('admin.proveedores.show', compact('proveedor'));

        } catch (Exception $err) {
            Log::error('Error obteniendo un proveedor: ' . $err->getMessage());
            return back()->withInput()
                         ->with('error', 'Ocurrió un error al intentar obtener un proveedor.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $proveedor = Proveedor::find($id);

            return view('admin.proveedores.edit', compact('proveedor'));

        } catch (Exception $err) {
            Log::error('Error obteniendo el proveedor para editar: ' . $err->getMessage());
            return back()->withInput()
                         ->with('error', 'Ocurrió un error al intentar obtener el proveedor.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Autorización integrada (Solo administradores)
        if (!$request->user()?->isAdmin()) {
            abort(403, 'Acción no autorizada.');
        }

        // Validación ignorando el registro actual para campos únicos
        $request->validate([
            'nombre_empresa'     => ['required', 'string', 'max:150'],
            'ruc'                => ['nullable', 'string', 'size:11', Rule::unique('proveedores', 'ruc')->ignore($id)],
            'telefono'           => ['nullable', 'string', 'max:20'],
            'correo'             => ['nullable', 'email', 'max:100', Rule::unique('proveedores', 'correo')->ignore($id)],
            'contacto_ejecutivo' => ['nullable', 'string', 'max:100'],
            'observaciones'      => ['nullable', 'string'],
            'activo'             => ['required', 'boolean'], // Permite activar/desactivar en el formulario
        ]);

        try {
            $proveedor = Proveedor::find($id);

            $proveedor->nombre_empresa     = $request->nombre_empresa;
            $proveedor->ruc                = $request->ruc;
            $proveedor->telefono           = $request->telefono;
            $proveedor->correo             = $request->correo;
            $proveedor->contacto_ejecutivo = $request->contacto_ejecutivo;
            $proveedor->observaciones      = $request->observaciones;
            $proveedor->activo             = $request->activo;
            
            $proveedor->save();

            return redirect()->route('proveedores.index')
                             ->with('success', 'Proveedor actualizado exitosamente.');

        } catch (Exception $err) {
            Log::error('Error al actualizar un proveedor: ' . $err->getMessage());
            return back()->withInput()
                         ->with('error', 'Ocurrió un error al actualizar el proveedor.');
        }
    }

    /**
     * Remove the specified resource from storage (Borrado Lógico / Desactivar).
     */
    public function destroy(string $id)
    {
        try {
            $proveedor = Proveedor::find($id);
            
            // Aplicamos tu requerimiento de solo desactivar cambiando el estado a false
            $proveedor->activo = false;
            $proveedor->save();

            return redirect()->route('proveedores.index')
                             ->with('success', 'Proveedor desactivado correctamente.');

        } catch (Exception $err) {
            Log::error('Error al desactivar el proveedor: ' . $err->getMessage());
            return back()->withInput()
                         ->with('error', 'Ocurrió un error al intentar desactivar el proveedor.');
        }
    }
}