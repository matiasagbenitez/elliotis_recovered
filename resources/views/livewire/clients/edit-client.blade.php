<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar un cliente</h2>
        </div>
    </x-slot>

    <x-jet-form-section class="mb-6" submit="update">

        <x-slot name="title">
            Aclaraciones
        </x-slot>

        <x-slot name="description">
            <span>
                Lea detenidamente la información solicitada y rellene los campos requeridos para editar la información de un cliente en el sistema.
                <br><br>
                (*) Campos obligatorios.
                <br><br>
                Los campos que no son obligatorios pueden ser rellenados en cualquier momento.
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
                <x-jet-input wire:model.defer="editForm.business_name" type="text" class="w-full"
                    placeholder="Ingrese la razón social"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.business_name" />
            </div>
            <x-jet-input wire:model.defer="editForm.slug" type="hidden"></x-jet-input>

            {{-- CUIT --}}
            <div class="col-span-3">
                <x-jet-label class="mb-2">CUIT *</x-jet-label>
                <x-jet-input wire:model.defer="editForm.cuit" type="text" class="w-full"
                    placeholder="XX-XXXXXXXX-X"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.cuit" />
            </div>

            {{-- IVA condition --}}
            <div class="col-span-6">
                <x-jet-label class="mb-2">Condición ante IVA *</x-jet-label>
                <select class="input-control w-full" wire:model.defer="editForm.iva_condition_id">
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
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.iva_condition_id" />
            </div>

            <div class="col-span-6">
                <h2 class="font-bold">Información adicional</h2>
                <hr>
            </div>

            {{-- First name --}}
            <div class="col-span-3">
                <x-jet-label class="mb-2">Nombre *</x-jet-label>
                <x-jet-input wire:model.defer="editForm.first_name" type="text" class="w-full"
                    placeholder="Ingrese el nombre del cliente"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.first_name" />
            </div>

            {{-- Last name --}}
            <div class="col-span-3">
                <x-jet-label class="mb-2">Apellido *</x-jet-label>
                <x-jet-input wire:model.defer="editForm.last_name" type="text" class="w-full"
                    placeholder="Ingrese el apellido del cliente"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.last_name" />
            </div>

            {{-- Adress --}}
            <div class="col-span-6">
                <x-jet-label class="mb-2">Dirección *</x-jet-label>
                <x-jet-input wire:model.defer="editForm.adress" type="text" class="w-full"
                    placeholder="Ingrese la dirección del cliente"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.adress" />
            </div>

            <div class="col-span-6">
                <x-jet-label class="mb-2">Localidad *</x-jet-label>
                <select class="input-control w-full" wire:model.defer="editForm.locality_id">
                    <option value="" disabled selected>Seleccione localidad</option>
                    @foreach ($localities as $locality)
                        <option value="{{ $locality->id }}">
                            {{ $locality->name }}, {{ $locality->province->name }}
                            ({{ $locality->province->country->name }})
                        </option>
                    @endforeach
                </select>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.locality_id" />
            </div>

            <div class="col-span-6">
                <h2 class="font-bold">Información de contacto</h2>
                <hr>
            </div>

            {{-- Phone --}}
            <div class="col-span-6">
                <x-jet-label class="mb-2">Teléfono *</x-jet-label>
                <x-jet-input wire:model.defer="editForm.phone" type="tel" class="w-full"
                    placeholder="Ingrese el teléfono del cliente"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.phone" />
            </div>

            {{-- Email --}}
            <div class="col-span-6">
                <x-jet-label class="mb-2">Correo electrónico *</x-jet-label>
                <x-jet-input wire:model.defer="editForm.email" type="email" class="w-full"
                    placeholder="Ingrese el correo electrónico del cliente"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.email" />
            </div>

            <div class="col-span-6">
                <h2 class="font-bold">Estado del cliente</h2>
                <hr>
            </div>

            {{-- Active --}}
            <div class="col-span-6">
                {{-- Radio buttons for active attribute --}}
                <div class="flex gap-4">
                    <div class="flex space-x-2">
                        <x-jet-label class="mb-2">Activo</x-jet-label>
                        <x-jet-input wire:model="editForm.active" type="radio" value="1"></x-jet-input>
                    </div>
                    <div class="flex space-x-2">
                        <x-jet-label class="mb-2">Inactivo</x-jet-label>
                        <x-jet-input wire:model="editForm.active" type="radio" value="0"></x-jet-input>
                    </div>
                </div>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.active" />
            </div>

            {{-- Observations --}}
            <div class="col-span-6">
                <x-jet-label class="mb-2">Observaciones</x-jet-label>
                <textarea class="input-control w-full" wire:model.defer="editForm.observations"
                    placeholder="Ingrese observaciones sobre el cliente"></textarea>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.observations" />
            </div>

        </x-slot>

        <x-slot name="actions">
            <x-jet-button class="px-6">
                Guardar cambios
            </x-jet-button>
        </x-slot>

    </x-jet-form-section>

</div>
