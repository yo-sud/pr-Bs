<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\Repartidor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AdminRepartidorController extends Controller
{
    public function index()
    {
        try {
            $repartidores = Repartidor::query()
                ->withCount(['pedidos' => fn ($q) => $q
                    ->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                ])
                ->orderBy('nombre_empresa')
                ->simplePaginate(15);

            $repartidoresCount = Repartidor::count();
            $repartidoresActivosCount = Repartidor::where('activo', true)->count();
            $pedidosAsignadosCount = Pedido::whereNotNull('repartidor_id')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

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

    public function create()
    {
        return view('admin.repartidores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre_empresa'           => ['required', 'string', 'max:150'],
            'ciudad'                   => ['nullable', 'string', 'max:150'],
            'contacto_ejecutivo'       => ['nullable', 'string', 'max:150'],
            'ruc'                      => ['nullable', 'string', 'size:11', 'unique:repartidores,ruc'],
            'telefono'                 => ['nullable', 'string', 'max:20'],
            'correo'                   => ['nullable', 'email', 'max:100', 'unique:repartidores,correo'],
            'tiempo_entrega_estimado'  => ['nullable', 'string', 'max:100'],
            'observaciones'            => ['nullable', 'string'],
        ]);

        try {
            Repartidor::create([
                'nombre_empresa'          => $request->nombre_empresa,
                'ciudad'                  => $request->ciudad,
                'contacto_ejecutivo'      => $request->contacto_ejecutivo,
                'ruc'                     => $request->ruc,
                'telefono'                => $request->telefono,
                'correo'                  => $request->correo,
                'tiempo_entrega_estimado' => $request->tiempo_entrega_estimado,
                'observaciones'           => $request->observaciones,
                'activo'                  => true,
            ]);

            return redirect()->route('admin.repartidores.index')
                             ->with('success', 'Repartidor creado exitosamente.');

        } catch (Exception $err) {
            Log::error('Error creando un repartidor: ' . $err->getMessage());
            return back()->withInput()
                         ->with('error', 'Ocurrió un error al crear un repartidor.');
        }
    }

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

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nombre_empresa'           => ['required', 'string', 'max:150'],
            'ciudad'                   => ['nullable', 'string', 'max:150'],
            'contacto_ejecutivo'       => ['nullable', 'string', 'max:150'],
            'ruc'                      => ['nullable', 'string', 'size:11', Rule::unique('repartidores', 'ruc')->ignore($id)],
            'telefono'                 => ['nullable', 'string', 'max:20'],
            'correo'                   => ['nullable', 'email', 'max:100', Rule::unique('repartidores', 'correo')->ignore($id)],
            'tiempo_entrega_estimado'  => ['nullable', 'string', 'max:100'],
            'observaciones'            => ['nullable', 'string'],
            'activo'                   => ['required', 'boolean'],
        ]);

        try {
            $repartidor = Repartidor::find($id);

            $repartidor->nombre_empresa          = $request->nombre_empresa;
            $repartidor->ciudad                  = $request->ciudad;
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

    public function toggleStatus(Repartidor $repartidor)
    {
        $repartidor->activo = !$repartidor->activo;
        $repartidor->save();

        $msg = $repartidor->activo ? 'Empresa activada correctamente.' : 'Empresa desactivada correctamente.';
        return back()->with('success', $msg);
    }

    public function destroy(string $id)
    {
        try {
            $repartidor = Repartidor::find($id);

            // Desactiva sin eliminar el registro.
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
