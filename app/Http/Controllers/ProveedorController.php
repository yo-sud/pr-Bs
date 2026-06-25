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
    public function index(Request $request)
    {
        try {
            $query = Proveedor::query()->withCount('libros')->orderBy('nombre_empresa');

            if ($search = $request->input('search')) {
                $query->where(function ($q) use ($search) {
                    $q->where('nombre_empresa', 'like', "%{$search}%")
                      ->orWhere('contacto_ejecutivo', 'like', "%{$search}%")
                      ->orWhere('correo', 'like', "%{$search}%");
                });
            }

            $proveedores = $query->paginate(15)->withQueryString();

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
            'direccion'          => ['nullable', 'string', 'max:200'],
            'observaciones'      => ['nullable', 'string'],
        ]);

        try {
            Proveedor::create([
                'nombre_empresa'     => $request->nombre_empresa,
                'ruc'                => $request->ruc,
                'telefono'           => $request->telefono,
                'correo'             => $request->correo,
                'contacto_ejecutivo' => $request->contacto_ejecutivo,
                'direccion'          => $request->direccion,
                'observaciones'      => $request->observaciones,
                'activo'             => true,
            ]);

            return redirect()->route('admin.proveedores.index')
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
            'direccion'          => ['nullable', 'string', 'max:200'],
            'observaciones'      => ['nullable', 'string'],
            'activo'             => ['boolean'],
        ]);

        try {
            $proveedor = Proveedor::find($id);

            $proveedor->nombre_empresa     = $request->nombre_empresa;
            $proveedor->ruc                = $request->ruc;
            $proveedor->telefono           = $request->telefono;
            $proveedor->correo             = $request->correo;
            $proveedor->contacto_ejecutivo = $request->contacto_ejecutivo;
            $proveedor->direccion          = $request->direccion;
            $proveedor->observaciones      = $request->observaciones;
            $proveedor->activo             = $request->boolean('activo', true);

            $proveedor->save();

            return redirect()->route('admin.proveedores.index')
                             ->with('success', 'Proveedor actualizado exitosamente.');

        } catch (Exception $err) {
            Log::error('Error al actualizar un proveedor: ' . $err->getMessage());
            return back()->withInput()
                         ->with('error', 'Ocurrió un error al actualizar el proveedor.');
        }
    }

    public function toggleStatus(Proveedor $proveedor)
    {
        try {
            $proveedor->activo = !$proveedor->activo;
            $proveedor->save();

            $mensaje = $proveedor->activo ? 'Proveedor activado.' : 'Proveedor desactivado.';
            return back()->with('success', $mensaje);

        } catch (Exception $err) {
            Log::error('Error al cambiar estado del proveedor: ' . $err->getMessage());
            return back()->with('error', 'Ocurrió un error al cambiar el estado.');
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

            return redirect()->route('admin.proveedores.index')
                             ->with('success', 'Proveedor desactivado correctamente.');

        } catch (Exception $err) {
            Log::error('Error al desactivar el proveedor: ' . $err->getMessage());
            return back()->withInput()
                         ->with('error', 'Ocurrió un error al intentar desactivar el proveedor.');
        }
    }
}