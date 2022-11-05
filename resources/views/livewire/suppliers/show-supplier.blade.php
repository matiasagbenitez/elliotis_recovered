<div>
    <x-jet-secondary-button wire:click="showSupplier">
        <i class="fas fa-eye"></i>
    </x-jet-secondary-button>

    <x-jet-dialog-modal wire:model="isOpen">
        <x-slot name="title">
            <h1 class="font-bold text-2xl">
                Detalles de proveedor
            </h1>
        </x-slot>

        <x-slot name="content">
            <h2 class="font-bold text-lg uppercase">Datos comerciales</h2>
            <hr>

            {{-- Business name --}}
            <div class="flex items-baseline space-x-2">
                <h3 class="mt-3 text-md font-bold">Razón social:</h3>
                <p class="text-md font-mono">{{ $supplier->business_name }}</p>
            </div>

            {{-- CUIT --}}
            <div class="flex items-baseline space-x-2">
                <h3 class="mt-3 text-md font-bold">CUIT:</h3>
                <p class="text-md font-mono">{{ $supplier->cuit }}</p>
            </div>

            {{-- IVA condition --}}
            <div class="flex items-baseline space-x-2">
                <h3 class="mt-3 text-md font-bold">Condición ante IVA:</h3>
                <p class="text-md font-mono">
                    {{ $supplier->iva_condition->name }}
                    {{ $supplier->iva_condition->discriminate ? '(Discrimina IVA)' : '(No discrimina IVA)' }}
                </p>
            </div>

            <div class="flex items-baseline space-x-2">
                <h3 class="mt-3 text-md font-bold">Fecha de alta sistema:</h3>
                <p class="text-md font-mono">
                    {{ $supplier->created_at->format('d/m/Y') }}
                </p>
            </div>

            <h2 class="font-bold text-lg uppercase mt-4">Información adicional</h2>
            <hr>

            {{-- First name --}}
            <div class="flex items-baseline space-x-2">
                <h3 class="mt-3 text-md font-bold">Nombre:</h3>
                <p class="text-md font-mono">{{ $supplier->first_name }}</p>
            </div>

            {{-- Last name --}}
            <div class="flex items-baseline space-x-2">
                <h3 class="mt-3 text-md font-bold">Apellido:</h3>
                <p class="text-md font-mono">{{ $supplier->last_name }}</p>
            </div>

            {{-- Adress --}}
            <div class="flex items-baseline space-x-2">
                <h3 class="mt-3 text-md font-bold">Dirección:</h3>
                <p class="text-md font-mono">
                    {{ $supplier->adress ? $supplier->adress : 'No especifica' }}
                </p>
            </div>

            {{-- Locality --}}
            <div class="flex items-baseline space-x-2">
                <h3 class="mt-3 text-md font-bold">Localidad:</h3>
                <p class="text-md font-mono">
                    {{ $supplier->locality->name }}, {{ $supplier->locality->province->name }} ({{ $supplier->locality->province->country->name }})
                </p>
            </div>

            <h2 class="font-bold text-lg uppercase mt-4">Información de contacto</h2>
            <hr>

            {{-- Phone --}}
            <div class="flex items-baseline space-x-2">
                <h3 class="mt-3 text-md font-bold">Teléfono:</h3>
                <p class="text-md font-mono">{{ $supplier->phone }}</p>
            </div>

            {{-- Email --}}
            <div class="flex items-baseline space-x-2">
                <h3 class="mt-3 text-md font-bold">Email:</h3>
                <p class="text-md font-mono">{{ $supplier->email }}</p>
            </div>

            <h2 class="font-bold text-lg uppercase mt-4">Observaciones</h2>
            <hr>

            {{-- Observations --}}
            <div class="flex items-baseline space-x-2">
                <h3 class="mt-3 text-md font-bold">Observaciones:</h3>
                <div class="overflow-hidden whitespace-nowrap text-ellipsis">
                    <span class="text-md font-mono ">
                        {{ $supplier->observations ? $supplier->observations : 'No especifica' }}
                    </span>
                </div>
            </div>

        </x-slot>

        <x-slot name="footer">
            <div class="flex flex-end gap-3">
                <x-jet-button wire:click="$set('isOpen', false)">
                    Volver
                </x-jet-button>
            </div>
        </x-slot>
    </x-jet-dialog-modal>
</div>
