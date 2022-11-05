<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Crear un nuevo proveedor</h2>
        </div>
    </x-slot>

    <x-jet-form-section class="mb-6" submit="save">

        <x-slot name="title">
            Aclaraciones
        </x-slot>

        <x-slot name="description">
            <span>
                Lea detenidamente la información solicitada y rellene los campos requeridos para registrar un nuevo proveedor en el sistema.
                <br><br>
                (*) Campos obligatorios.
                <br><br>
                Los campos que no son obligatorios pueden ser rellenados en cualquier momento.
                <br><br>
                (**) Si el estado del proveedor es inactivo, no será tenido en cuenta en el proceso automatizado de pedido de rollos. Además, no podrá asociarle una nueva compra de materia prima.
            </span>
        </x-slot>

        <x-slot name="form">

            <div class="col-span-6">
                <h2 class="font-bold">Datos comerciales</h2>
                <hr>
            </div>

            {{-- Name --}}
            <div class="col-span-3">
                <x-jet-label class="mb-2">Razón social *</x-jet-label>
                <x-jet-input wire:model.defer="createForm.business_name" type="text" class="w-full"
                    placeholder="Ingrese la razón social"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.business_name" />
            </div>
            <x-jet-input wire:model.defer="createForm.slug" type="hidden"></x-jet-input>

            {{-- CUIT --}}
            <div class="col-span-3">
                <x-jet-label class="mb-2">CUIT *</x-jet-label>
                <x-jet-input wire:model.defer="createForm.cuit" type="text" class="w-full"
                    placeholder="XX-XXXXXXXX-X"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.cuit" />
            </div>

            {{-- IVA condition --}}
            <div class="col-span-6">
                <x-jet-label class="mb-2">Condición ante IVA *</x-jet-label>
                <select class="input-control w-full" wire:model.defer="createForm.iva_condition_id">
                    <option value="" disabled selected>Seleccione condición</option>
                    @foreach ($ivaConditions as $ivaCondition)
                        <option value="{{ $ivaCondition->id }}">
                            {{ $ivaCondition->name }}

                            @if ($ivaCondition->discriminate)
                                (Discrimina IVA)
                            @else
                                (No discrimina IVA)
                            @endif

                        </option>
                    @endforeach
                </select>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.iva_condition_id" />
            </div>

            <div class="col-span-6">
                <h2 class="font-bold">Información adicional</h2>
                <hr>
            </div>

            {{-- First name --}}
            <div class="col-span-3">
                <x-jet-label class="mb-2">Nombre *</x-jet-label>
                <x-jet-input wire:model.defer="createForm.first_name" type="text" class="w-full"
                    placeholder="Ingrese el nombre del proveedor"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.first_name" />
            </div>

            {{-- Last name --}}
            <div class="col-span-3">
                <x-jet-label class="mb-2">Apellido *</x-jet-label>
                <x-jet-input wire:model.defer="createForm.last_name" type="text" class="w-full"
                    placeholder="Ingrese el apellido del proveedor"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.last_name" />
            </div>

            {{-- Adress --}}
            <div class="col-span-6">
                <x-jet-label class="mb-2">Dirección *</x-jet-label>
                <x-jet-input wire:model.defer="createForm.adress" type="text" class="w-full"
                    placeholder="Ingrese la dirección del proveedor"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.adress" />
            </div>

            <div class="col-span-6">
                <x-jet-label class="mb-2">Localidad *</x-jet-label>
                <select class="input-control w-full" wire:model.defer="createForm.locality_id">
                    <option value="" disabled selected>Seleccione localidad</option>
                    @foreach ($localities as $locality)
                        <option value="{{ $locality->id }}">
                            {{ $locality->name }}, {{ $locality->province->name }}
                            ({{ $locality->province->country->name }})
                        </option>
                    @endforeach
                </select>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.locality_id" />
            </div>

            <div class="col-span-6">
                <h2 class="font-bold">Información de contacto</h2>
                <hr>
            </div>

            {{-- Phone --}}
            <div class="col-span-6">
                <x-jet-label class="mb-2">Teléfono *</x-jet-label>
                <x-jet-input wire:model.defer="createForm.phone" type="tel" class="w-full"
                    placeholder="Ingrese el teléfono del proveedor"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.phone" />
            </div>

            {{-- Email --}}
            <div class="col-span-6">
                <x-jet-label class="mb-2">Correo electrónico *</x-jet-label>
                <x-jet-input wire:model.defer="createForm.email" type="email" class="w-full"
                    placeholder="Ingrese el correo electrónico del proveedor"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.email" />
            </div>

            <div class="col-span-6">
                <h2 class="font-bold">Estado del proveedor</h2>
                <hr>
            </div>

            {{-- Active --}}
            <div class="col-span-6">
                {{-- Radio buttons for active attribute --}}
                <div class="flex gap-4">
                    <div class="flex space-x-2">
                        <x-jet-label class="mb-2">Activo</x-jet-label>
                        <x-jet-input wire:model="createForm.active" type="radio" value="1"></x-jet-input>
                    </div>
                    <div class="flex space-x-2">
                        <x-jet-label class="mb-2">Inactivo</x-jet-label>
                        <x-jet-input wire:model="createForm.active" type="radio" value="0"></x-jet-input>
                    </div>
                </div>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.active" />
            </div>

            {{-- Observations --}}
            <div class="col-span-6">
                <x-jet-label class="mb-2">Observaciones</x-jet-label>
                <textarea wire:model.defer="createForm.observations" class="input-control w-full"
                    placeholder="Ingrese las observaciones del proveedor"></textarea>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.observations" />
            </div>

        </x-slot>

        <x-slot name="actions">
            <x-jet-button class="px-6">
                Crear proveedor
            </x-jet-button>
        </x-slot>

    </x-jet-form-section>

</div>
