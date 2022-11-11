<div class="container py-6">
    <x-slot name="header">
        <div class="flex items-center justify-between">

            {{-- GO BACK BUTTON --}}
            <a href="{{ route('admin.tenderings.show-detail', $tender->id) }}">
                <x-jet-button>
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </x-jet-button>
            </a>

            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalle de
                <span class="font-bold">Concurso #{{ $tender->id }}</span>
                finalizado
            </h2>

            {{-- PDF BUTTON --}}
            <a href="#">
                <x-jet-danger-button>
                    <i class="fas fa-file-pdf mr-2"></i>
                    Descargar PDF
                </x-jet-danger-button>
            </a>
        </div>
    </x-slot>

    <div class="px-6 py-6 bg-white rounded-lg shadow">

        {{-- ESTADÍSTICAS --}}
        <div class="grid grid-cols-4 gap-4">

            {{-- Solicitudes enviadas --}}
            <div class="bg-slate-200 rounded-lg p-6 hover:cursor-pointer" wire:click="filter('requested')">
                <p class="text-center text-xl">
                    {{ $requestedSuppliers }}
                </p>
                <p class="text-center uppercase font-bold">Solicitudes enviadas</p>
                <p class="text-center text-xl mt-2 uppercase">
                    <i class="fas fa-business-time"></i>
                </p>
            </div>

            {{-- Solicitudes vistas --}}
            <div class="bg-slate-300 rounded-lg p-6 hover:cursor-pointer" wire:click="filter('seen')">
                <p class="text-center text-xl">
                    {{ $seenRequests }}
                </p>
                <p class="text-center uppercase font-bold">Solicitudes vistas</p>
                <p class="text-center text-xl mt-2 uppercase">
                    <i class="fas fa-eye"></i>
                </p>
            </div>

            {{-- Ofertas enviadas --}}
            <div class="bg-slate-300 rounded-lg p-6 hover:cursor-pointer" wire:click="filter('answered')">
                <p class="text-center text-xl">
                    {{ $answeredRequests }}
                </p>
                <p class="text-center uppercase font-bold">Ofertas válidas</p>
                <p class="text-center text-xl mt-2 uppercase">
                    <i class="fas fa-check-circle"></i>
                </p>
            </div>

            {{-- Ofertas canceladas --}}
            <div class="bg-slate-400 rounded-lg p-6 hover:cursor-pointer" wire:click="filter('cancelled')">
                <p class="text-center text-xl">
                    {{ $cancelledOffers }}
                </p>
                <p class="text-center uppercase font-bold">Ofertas canceladas</p>
                <p class="text-center text-xl mt-2 uppercase">
                    <i class="fas fa-ban"></i>
                </p>
            </div>

        </div>
    </div>

    {{-- DETALLES DE OFERTAS --}}
    <div class="px-6 py-6 bg-white rounded-lg shadow mt-6">
        <span class="font-bold">{{ $title }}</span>
        <hr class="my-1">
        <ul class="list-disc list-inside ml-4">
            @foreach ($suppliers as $supplier)
                <li class="text-sm">
                    {{$supplier->business_name}}
                </li>
            @endforeach
        </ul>
    </div>
</div>
