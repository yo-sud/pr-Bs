@extends('layouts.admin')

@section('title', 'Pedido #'.$pedido->id.' - Administración')

@section('contenido')

<a href="{{ route('admin.pedidos.index') }}"
   class="inline-flex items-center gap-1 text-sm font-semibold text-[#B8500C] hover:underline">
    &larr; Volver a pedidos
</a>

@php
    $badgePago = match($pedido->estado_pago) {
        'pagado'      => 'bg-emerald-100 text-emerald-800',
        'fallido'     => 'bg-red-100 text-red-800',
        'reembolsado' => 'bg-sky-100 text-sky-800',
        default       => 'bg-amber-100 text-amber-800',
    };
    $badgePedido = match($pedido->estado_pedido) {
        'pagado'     => 'bg-sky-100 text-sky-800',
        'preparando' => 'bg-amber-100 text-amber-800',
        'enviado'    => 'bg-indigo-100 text-indigo-800',
        'entregado'  => 'bg-emerald-100 text-emerald-800',
        'cancelado'  => 'bg-red-100 text-red-800',
        default      => 'bg-gray-100 text-gray-600',
    };
@endphp

<div class="flex flex-wrap items-start justify-between gap-4 mt-5 mb-7">
    <div>
        <h2 class="font-serif text-2xl font-semibold text-amber-900">Pedido #{{ $pedido->id }}</h2>
        <p class="text-sm text-stone-500 mt-0.5">
            Registrado el {{ $pedido->created_at->format('d/m/Y') }} a las {{ $pedido->created_at->format('H:i') }}
        </p>
    </div>
    <div class="flex items-center gap-2 flex-wrap">
        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold uppercase {{ $badgePago }}">
            Pago: {{ $pedido->estado_pago }}
        </span>
    </div>
</div>


@if ($errors->any())
    <div class="mb-6 rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800 space-y-1">
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif

<div class="grid grid-cols-1 xl:grid-cols-[1fr_380px] gap-6 items-start">

    {{-- COLUMNA IZQUIERDA --}}
    <div class="space-y-6">

        {{-- Productos --}}
        <section class="bg-white rounded-xl border border-amber-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-amber-100 bg-amber-50/40">
                <h3 class="font-serif font-semibold text-amber-900">Productos pedidos</h3>
            </div>
            <div class="divide-y divide-amber-50 px-6">
                @foreach ($pedido->detalles as $detalle)
                    <div class="py-3.5 flex justify-between gap-4 text-sm">
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-stone-800">{{ $detalle->titulo }}</p>
                            @if ($detalle->isbn)
                                <p class="text-xs text-stone-400 mt-0.5">ISBN: {{ $detalle->isbn }}</p>
                            @endif
                            <p class="text-xs text-stone-500 mt-1 tabular-nums">
                                {{ $detalle->cantidad }} × S/ {{ number_format((float) $detalle->precio_unitario, 2) }}
                            </p>
                        </div>
                        <strong class="text-stone-800 whitespace-nowrap tabular-nums self-start pt-0.5">
                            S/ {{ number_format((float) $detalle->subtotal, 2) }}
                        </strong>
                    </div>
                @endforeach
            </div>

            {{-- Desglose financiero --}}
            <div class="mx-6 mb-5 mt-1 border border-amber-100 rounded-lg bg-amber-50/30 px-4 py-3 space-y-2 text-sm">
                <div class="flex justify-between text-stone-500">
                    <span>Subtotal</span>
                    <span class="tabular-nums">S/ {{ number_format((float) $pedido->subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-stone-500">
                    <span>Envío</span>
                    <span class="tabular-nums">S/ {{ number_format((float) $pedido->envio, 2) }}</span>
                </div>
                <div class="flex justify-between font-bold text-stone-900 text-base border-t border-amber-100 pt-2 mt-1">
                    <span>Total</span>
                    <span class="tabular-nums">S/ {{ number_format((float) $pedido->total, 2) }}</span>
                </div>
            </div>
        </section>

        {{-- Historial de estados --}}
        <section class="bg-white rounded-xl border border-amber-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-amber-100 bg-amber-50/40">
                <h3 class="font-serif font-semibold text-amber-900">Historial de estados</h3>
            </div>
            <div class="px-6 py-5">
                @forelse ($pedido->historialEstados->sortByDesc('created_at') as $cambio)
                    @php
                        $histBadge = match($cambio->estado_nuevo) {
                            'pagado'     => 'bg-sky-100 text-sky-800',
                            'preparando' => 'bg-amber-100 text-amber-800',
                            'enviado'    => 'bg-indigo-100 text-indigo-800',
                            'entregado'  => 'bg-emerald-100 text-emerald-800',
                            'cancelado'  => 'bg-red-100 text-red-800',
                            default      => 'bg-stone-100 text-stone-600',
                        };
                    @endphp
                    <div class="relative flex gap-4 pb-6 last:pb-0">
                        {{-- Línea vertical conectora --}}
                        @unless ($loop->last)
                            <div class="absolute left-[7px] top-4 bottom-0 w-px bg-amber-100"></div>
                        @endunless
                        {{-- Punto --}}
                        <div class="mt-1 w-3.5 h-3.5 rounded-full bg-[#B8500C] border-2 border-white ring-2 ring-amber-200 flex-shrink-0 z-10"></div>
                        {{-- Contenido --}}
                        <div class="flex-1 min-w-0 -mt-0.5">
                            <div class="flex flex-wrap items-center gap-1.5 mb-1">
                                @if ($cambio->estado_anterior)
                                    <span class="text-[11px] font-medium uppercase text-stone-400">{{ $cambio->estado_anterior }}</span>
                                    <span class="text-stone-300 text-xs">→</span>
                                @endif
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-bold uppercase {{ $histBadge }}">
                                    {{ $cambio->estado_nuevo }}
                                </span>
                            </div>
                            <p class="text-xs text-stone-400">
                                {{ $cambio->created_at->format('d/m/Y H:i') }} · {{ $cambio->usuario?->name ?: 'Sistema' }}
                            </p>
                            @if ($cambio->observacion)
                                <p class="text-sm text-stone-600 mt-2 bg-amber-50/60 border border-amber-100 rounded-lg px-3 py-2 leading-relaxed">
                                    {{ $cambio->observacion }}
                                </p>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-stone-400">Sin cambios registrados.</p>
                @endforelse
            </div>
        </section>

    </div>

    {{-- COLUMNA DERECHA --}}
    <aside class="space-y-6">

        {{-- Cliente --}}
        <section class="bg-white rounded-xl border border-amber-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-amber-100 bg-amber-50/40">
                <h3 class="font-serif font-semibold text-amber-900">Cliente</h3>
            </div>
            <div class="px-6 py-4">
                @if ($pedido->usuario)
                    <div class="space-y-2.5 text-sm">
                        <div class="flex items-center gap-2.5">
                            <span class="material-symbols-outlined text-[18px] text-amber-400 flex-shrink-0">person</span>
                            <span class="font-semibold text-stone-800">{{ $pedido->usuario->name }}</span>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <span class="material-symbols-outlined text-[18px] text-amber-400 flex-shrink-0">mail</span>
                            <a href="mailto:{{ $pedido->usuario->email }}"
                               class="text-[#B8500C] hover:underline break-all">
                                {{ $pedido->usuario->email }}
                            </a>
                        </div>
                        <div class="flex items-center gap-2.5 text-stone-500">
                            <span class="material-symbols-outlined text-[18px] text-amber-400 flex-shrink-0">calendar_today</span>
                            <span>Miembro desde {{ $pedido->usuario->created_at->format('d/m/Y') }}</span>
                        </div>
                    </div>
                @else
                    <p class="text-sm text-stone-400">Usuario eliminado.</p>
                @endif
            </div>
        </section>

        {{-- Dirección de entrega --}}
        <section class="bg-white rounded-xl border border-amber-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-amber-100 bg-amber-50/40">
                <h3 class="font-serif font-semibold text-amber-900">Dirección de entrega</h3>
            </div>
            <div class="px-6 py-4">
                <p class="text-sm text-stone-600 whitespace-pre-line leading-relaxed">{{ $pedido->direccion }}</p>
            </div>
        </section>

        {{-- Empresa de reparto --}}
        <section class="bg-white rounded-xl border border-amber-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-amber-100 bg-amber-50/40">
                <h3 class="font-serif font-semibold text-amber-900">Empresa de reparto</h3>
            </div>
            <div class="px-6 py-4">
                @if ($pedido->repartidor)
                    @php
                        $ciudadRep = $pedido->repartidor->ciudad ?? '';
                        $hayDesajuste = $ciudadPedido && $ciudadRep && strtolower($ciudadRep) !== strtolower($ciudadPedido);
                    @endphp

                    @if ($hayDesajuste)
                        <div class="mb-3 flex items-start gap-2 rounded-lg bg-amber-50 border border-amber-200 px-3 py-2 text-xs text-amber-800">
                            <span class="material-symbols-outlined text-[16px] mt-0.5 flex-shrink-0">warning</span>
                            <span>Esta empresa cubre <strong>{{ $ciudadRep }}</strong>, pero el pedido es para <strong>{{ $ciudadPedido }}</strong>.</span>
                        </div>
                    @endif

                    <p class="font-semibold text-[#421605] text-base mb-3">{{ $pedido->repartidor->nombre_empresa }}
                        @if ($ciudadRep)
                            <span class="text-xs font-normal text-stone-400 ml-1">— {{ $ciudadRep }}</span>
                        @endif
                    </p>
                    <div class="space-y-2 text-sm text-stone-600">
                        <div class="flex items-center gap-2.5">
                            <span class="material-symbols-outlined text-[18px] text-amber-400 flex-shrink-0">badge</span>
                            <span>RUC: <strong class="text-stone-800">{{ $pedido->repartidor->ruc }}</strong></span>
                        </div>
                        @if ($pedido->repartidor->contacto_ejecutivo)
                            <div class="flex items-center gap-2.5">
                                <span class="material-symbols-outlined text-[18px] text-amber-400 flex-shrink-0">person</span>
                                <span>{{ $pedido->repartidor->contacto_ejecutivo }}</span>
                            </div>
                        @endif
                        @if ($pedido->repartidor->telefono)
                            <div class="flex items-center gap-2.5">
                                <span class="material-symbols-outlined text-[18px] text-amber-400 flex-shrink-0">call</span>
                                <span>{{ $pedido->repartidor->telefono }}</span>
                            </div>
                        @endif
                        @if ($pedido->repartidor->correo)
                            <div class="flex items-center gap-2.5">
                                <span class="material-symbols-outlined text-[18px] text-amber-400 flex-shrink-0">mail</span>
                                <a href="mailto:{{ $pedido->repartidor->correo }}"
                                   class="text-[#B8500C] hover:underline break-all">
                                    {{ $pedido->repartidor->correo }}
                                </a>
                            </div>
                        @endif
                        @if ($pedido->repartidor->tiempo_entrega_estimado)
                            <div class="flex items-center gap-2.5">
                                <span class="material-symbols-outlined text-[18px] text-amber-400 flex-shrink-0">schedule</span>
                                <span>{{ $pedido->repartidor->tiempo_entrega_estimado }}</span>
                            </div>
                        @endif
                        @if ($pedido->repartidor->observaciones)
                            <p class="mt-3 bg-amber-50/60 border border-amber-100 rounded-lg px-3 py-2 text-xs text-stone-500 leading-relaxed">
                                {{ $pedido->repartidor->observaciones }}
                            </p>
                        @endif
                    </div>
                @else
                    <div class="flex items-center gap-2 text-sm text-stone-400">
                        <span class="material-symbols-outlined text-[18px]">local_shipping</span>
                        <span>Sin empresa asignada aún</span>
                    </div>
                @endif
            </div>
        </section>

        {{-- Línea de tiempo --}}
        <section class="bg-white rounded-xl border border-amber-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-amber-100 bg-amber-50/40">
                <h3 class="font-serif font-semibold text-amber-900">Línea de tiempo</h3>
            </div>
            <div class="px-6 py-4 space-y-2.5 text-sm">
                <div class="flex justify-between gap-4">
                    <span class="text-stone-500 flex-shrink-0">Creado</span>
                    <span class="font-medium text-stone-700 tabular-nums text-right">{{ $pedido->created_at->format('d/m/Y H:i') }}</span>
                </div>
                @if ($pedido->pagado_at)
                    <div class="flex justify-between gap-4">
                        <span class="text-stone-500 flex-shrink-0">Pago confirmado</span>
                        <span class="font-medium text-emerald-700 tabular-nums text-right">{{ $pedido->pagado_at->format('d/m/Y H:i') }}</span>
                    </div>
                @endif
                @if ($pedido->enviado_at)
                    <div class="flex justify-between gap-4">
                        <span class="text-stone-500 flex-shrink-0">Enviado</span>
                        <span class="font-medium text-indigo-700 tabular-nums text-right">{{ $pedido->enviado_at->format('d/m/Y H:i') }}</span>
                    </div>
                @endif
                @if ($pedido->entregado_at)
                    <div class="flex justify-between gap-4">
                        <span class="text-stone-500 flex-shrink-0">Entregado</span>
                        <span class="font-medium text-emerald-700 tabular-nums text-right">{{ $pedido->entregado_at->format('d/m/Y H:i') }}</span>
                    </div>
                @endif
                @if ($pedido->cancelado_at)
                    <div class="flex justify-between gap-4">
                        <span class="text-stone-500 flex-shrink-0">Cancelado</span>
                        <span class="font-medium text-red-700 tabular-nums text-right">{{ $pedido->cancelado_at->format('d/m/Y H:i') }}</span>
                    </div>
                @endif
            </div>
        </section>


        {{-- Formulario de avance de estado --}}
        @if ($pedido->estado_pago === 'pagado' && in_array($pedido->estado_pedido, ['pagado', 'preparando', 'enviado'], true))
            @php
                $siguienteEstado = match ($pedido->estado_pedido) {
                    'pagado'     => 'preparando',
                    'preparando' => 'enviado',
                    'enviado'    => 'entregado',
                };
                $siguienteBadge = match ($siguienteEstado) {
                    'preparando' => 'bg-amber-100 text-amber-800',
                    'enviado'    => 'bg-indigo-100 text-indigo-800',
                    default      => 'bg-emerald-100 text-emerald-800',
                };
            @endphp
            <form method="POST"
                  action="{{ route('admin.pedidos.update-status', $pedido) }}"
                  class="bg-white rounded-xl border border-amber-100 shadow-sm overflow-hidden">
                @csrf
                @method('PATCH')
                <input type="hidden" name="estado" value="{{ $siguienteEstado }}">
                <div class="px-6 py-4 border-b border-amber-100 bg-amber-50/40">
                    <h3 class="font-serif font-semibold text-amber-900">Actualizar despacho</h3>
                </div>
                <div class="px-6 py-4 space-y-4">
                    <p class="text-sm text-stone-600">
                        Siguiente estado:
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-bold uppercase {{ $siguienteBadge }}">
                            {{ $siguienteEstado }}
                        </span>
                    </p>
                    @if ($siguienteEstado === 'enviado')
                        @php
                            $repsLocales = $repartidores->filter(fn($r) => $r->ciudad && strtolower($r->ciudad) === strtolower($ciudadPedido));
                            $repsOtros   = $repartidores->filter(fn($r) => !$r->ciudad || strtolower($r->ciudad) !== strtolower($ciudadPedido));
                        @endphp
                        <div>
                            <label class="block text-sm font-medium text-stone-700 mb-1">
                                Empresa de reparto
                                @if ($ciudadPedido)
                                    <span class="text-xs font-normal text-stone-400">— pedido en {{ $ciudadPedido }}</span>
                                @endif
                            </label>
                            <select name="repartidor_id" class="w-full rounded-lg border-amber-200 text-sm focus:border-amber-500 focus:ring-amber-500">
                                <option value="">— Sin asignar —</option>
                                @if ($repsLocales->isNotEmpty())
                                    <optgroup label="✓ Cubren {{ $ciudadPedido }}">
                                        @foreach ($repsLocales as $rep)
                                            <option value="{{ $rep->id }}" {{ $pedido->repartidor_id == $rep->id ? 'selected' : '' }}>
                                                {{ $rep->nombre_empresa }}
                                                @if ($rep->tiempo_entrega_estimado) ({{ $rep->tiempo_entrega_estimado }}) @endif
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif
                                @if ($repsOtros->isNotEmpty())
                                    <optgroup label="Otras ciudades">
                                        @foreach ($repsOtros as $rep)
                                            <option value="{{ $rep->id }}" {{ $pedido->repartidor_id == $rep->id ? 'selected' : '' }}>
                                                {{ $rep->nombre_empresa }}{{ $rep->ciudad ? ' ('.$rep->ciudad.')' : '' }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            </select>
                        </div>
                    @endif
                    <div>
                        <label class="block text-sm font-medium text-stone-700 mb-1">
                            Observación <span class="text-red-500">*</span>
                        </label>
                        <textarea name="observacion" rows="3" required minlength="3" maxlength="500"
                            placeholder="Describe el avance del pedido..."
                            class="w-full rounded-lg border-amber-200 text-sm resize-none focus:border-amber-500 focus:ring-amber-500">{{ old('observacion') }}</textarea>
                        @error('observacion')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <button class="w-full rounded-lg bg-[#B8500C] text-white py-2.5 text-sm font-semibold hover:bg-[#9a3f08] transition-colors">
                        Marcar como {{ $siguienteEstado }}
                    </button>
                </div>
            </form>
        @endif

    </aside>
</div>
@endsection
