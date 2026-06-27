<?php

namespace App\Http\Controllers;

use App\Models\Repartidor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AdminRepartidorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            // Buscamos los repartidores filtrados o paginados según tu necesidad
            $repartidores = Repartidor::query()
                ->orderBy('nombre_empresa')
                ->simplePaginate(15);

            // Métrica de ejemplo para las tarjetas superiores de tu vista index
            $repartidoresCount = Repartidor::count();
            $repartidoresActivosCount = Repartidor::where('activo', true)->count();
            $pedidosAsignadosCount = 15; // Aquí puedes meter tu lógica real de pedidos en el futuro

            return view('admin.repartidores.index', compact(
                'repartidores', 
                'repartidoresCount', 
                'repartidoresActivosCount', 
                'pedidosAsignadosCount'
            ));

        } catch (Exception $err) {
            Log::error('Error obteniendo la lista de repartidores: ' . $err->getMessage());
            return back()->withInput()
                         ->with('error', 'Ocurrió un error al intentar obtener la lista de repartidores.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.repartidores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación manual estricta adaptada a los campos de tu migración
        $request->validate([
            'nombre_empresa'           => ['required', 'string', 'max:150'],
            'contacto_ejecutivo'       => ['nullable', 'string', 'max:150'],
            'ruc'                      => ['nullable', 'string', 'size:11', 'unique:repartidores,ruc'],
            'telefono'                 => ['nullable', 'string', 'max:20'],
            'correo'                   => ['nullable', 'email', 'max:100', 'unique:repartidores,correo'],
            'tiempo_entrega_estimado'  => ['nullable', 'string', 'max:100'], // Tu campo clave (Zona/Tiempo)
            'observaciones'            => ['nullable', 'string'],
        ]);

        try {
            Repartidor::create([
                'nombre_empresa'          => $request->nombre_empresa,
                'contacto_ejecutivo'      => $request->contacto_ejecutivo,
                'ruc'                     => $request->ruc,
                'telefono'                => $request->telefono,
                'correo'                  => $request->correo,
                'tiempo_entrega_estimado' => $request->tiempo_entrega_estimado,
                'observaciones'           => $request->observaciones,
                'activo'                  => true // Activo por defecto al crearse
            ]);

            return redirect()->route('admin.repartidores.index')
                             ->with('success', 'Repartidor creado exitosamente.');

        } catch (Exception $err) {
            Log::error('Error creando un repartidor: ' . $err->getMessage());
            return back()->withInput()
                         ->with('error', 'Ocurrió un error al crear un repartidor.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $repartidor = Repartidor::find($id);

            return view('admin.repartidores.show', compact('repartidor'));

        } catch (Exception $err) {
            Log::error('Error obteniendo un repartidor: ' . $err->getMessage());
            return back()->withInput()
                         ->with('error', 'Ocurrió un error al intentar obtener un repartidor.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $repartidor = Repartidor::find($id);

            return view('admin.repartidores.edit', compact('repartidor'));

        } catch (Exception $err) {
            Log::error('Error obteniendo el repartidor para editar: ' . $err->getMessage());
            return back()->withInput()
                         ->with('error', 'Ocurrió un error al intentar obtener el repartidor.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validación ignorando el registro actual para campos únicos (RUC y Correo)
        $request->validate([
            'nombre_empresa'           => ['required', 'string', 'max:150'],
            'contacto_ejecutivo'       => ['nullable', 'string', 'max:150'],
            'ruc'                      => ['nullable', 'string', 'size:11', Rule::unique('repartidores', 'ruc')->ignore($id)],
            'telefono'                 => ['nullable', 'string', 'max:20'],
            'correo'                   => ['nullable', 'email', 'max:100', Rule::unique('repartidores', 'correo')->ignore($id)],
            'tiempo_entrega_estimado'  => ['nullable', 'string', 'max:100'],
            'observaciones'            => ['nullable', 'string'],
            'activo'                   => ['required', 'boolean'], // Permite activar/desactivar en el formulario
        ]);

        try {
            $repartidor = Repartidor::find($id);

            $repartidor->nombre_empresa          = $request->nombre_empresa;
            $repartidor->contacto_ejecutivo      = $request->contacto_ejecutivo;
            $repartidor->ruc                     = $request->ruc;
            $repartidor->telefono                = $request->telefono;
            $repartidor->correo                  = $request->correo;
            $repartidor->tiempo_entrega_estimado = $request->tiempo_entrega_estimado;
            $repartidor->observaciones           = $request->observaciones;
            $repartidor->activo                  = $request->activo;
            
            $repartidor->save();

            return redirect()->route('admin.repartidores.index')
                             ->with('success', 'Repartidor actualizado exitosamente.');

        } catch (Exception $err) {
            Log::error('Error al actualizar un repartidor: ' . $err->getMessage());
            return back()->withInput()
                         ->with('error', 'Ocurrió un error al actualizar el repartidor.');
        }
    }

    /**
     * Remove the specified resource from storage (Borrado Lógico / Solo Desactivar).
     */
    public function destroy(string $id)
    {
        try {
            $repartidor = Repartidor::find($id);
            
            // Lógica solicitada: Solo cambiamos el estado a inactivo
            $repartidor->activo = false;
            $repartidor->save();

            return redirect()->route('admin.repartidores.index')
                             ->with('success', 'Repartidor desactivado correctamente.');

        } catch (Exception $err) {
            Log::error('Error al desactivar el repartidor: ' . $err->getMessage());
            return back()->withInput()
                         ->with('error', 'Ocurrió un error al intentar desactivar el repartidor.');
        }
    }
}