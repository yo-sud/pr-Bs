@extends('layouts.app')

@section('title', 'BookShop - Libros Populares')

@section('content')
<main class="px-[7%] py-12 flex-grow bg-[#FDFBF7]">
    <div class="border-b border-[#6E7E80]/10 pb-6 mb-10">
        <h1 class="font-serif text-3xl md:text-4xl font-bold text-[#421605] mb-2">Libros Populares</h1>
        <p class="text-sm text-[#8A7A71]">Los títulos más vendidos por nuestra comunidad.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
        @foreach ($libros as $libro)
            <x-book-card :libro="$libro" badge="Tendencia" />
        @endforeach
    </div>

    <div class="mt-12">
        {{ $libros->links() }}
    </div>
</main>
@endsection
