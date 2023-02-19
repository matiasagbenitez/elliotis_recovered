<div>
    <x-jet-button wire:click="editTypeOfTask">
        <i class="fas fa-edit"></i>
    </x-jet-button>

    <x-jet-dialog-modal wire:model="isOpen">
        <x-slot name="title">
            Editar tipo de tarea
        </x-slot>

        <x-slot name="content">
            {{-- Name --}}
            <div class="mb-4">
                <x-jet-label class="mb-2">Tipo (opcional)</x-jet-label>
                <x-jet-input wire:model.defer="editForm.type" type="text" class="w-full"
                    placeholder="Ingrese el tipo de tarea"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.type" />
            </div>

            {{-- Prefix --}}
            <div class="mb-4">
                <x-jet-label class="mb-2">Nombre</x-jet-label>
                <x-jet-input wire:model.defer="editForm.name" type="text" class="w-full"
                    placeholder="Ingrese el nombre de la tarea"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.name" />
            </div>

            <div class="mb-4">
                <x-jet-label class="mb-2">Descripción</x-jet-label>
                <x-jet-input wire:model.defer="editForm.description" type="text" class="w-full"
                    placeholder="Ingrese la descripción de la tarea"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.description" />
            </div>

            {{-- Tarea inicial --}}
            <div class="mb-4">
                <x-jet-label class="mb-2">¿Es una tarea inicial?</x-jet-label>
                <select wire:model.defer="editForm.initial_task" class="input-control w-full">
                    <option value="0">No</option>
                    <option value="1">Si</option>
                </select>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.initial_task" />
            </div>

            {{-- Areas --}}
            <div class="mb-4 grid grid-cols-2 gap-3">
                {{-- Origen --}}
                <div>
                    <x-jet-label class="mb-2">Área origen</x-jet-label>
                    <select wire:model.defer="editForm.origin_area_id" class="input-control w-full">
                        <option value="">Seleccione una opción</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}">{{ $area->name }}</option>
                        @endforeach
                    </select>
                    <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.origin_area_id" />
                </div>
                {{-- Destino --}}
                <div>
                    <x-jet-label class="mb-2">Área destino</x-jet-label>
                    <select wire:model.defer="editForm.destination_area_id" class="input-control w-full">
                        <option value="">Seleccione una opción</option>
                        @foreach ($areas as $area)
                            <option value="{{ $area->id }}">{{ $area->name }}</option>
                        @endforeach
                    </select>
                    <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.destination_area_id" />
                </div>
            </div>

            {{-- Fases --}}
            <div class="mb-4 grid grid-cols-2 gap-3">
                {{-- Inicial --}}
                <div>
                    <x-jet-label class="mb-2">Etapa inicial</x-jet-label>
                    <select wire:model.defer="editForm.initial_phase_id" class="input-control w-full">
                        <option value="">Seleccione una opción</option>
                        @foreach ($phases as $phase)
                            <option value="{{ $phase->id }}">{{ $phase->name }}</option>
                        @endforeach
                    </select>
                    <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.initial_phase_id" />
                </div>
                {{-- Final --}}
                <div>
                    <x-jet-label class="mb-2">Etapa final</x-jet-label>
                    <select wire:model.defer="editForm.final_phase_id" class="input-control w-full">
                        <option value="">Seleccione una opción</option>
                        @foreach ($phases as $phase)
                            <option value="{{ $phase->id }}">{{ $phase->name }}</option>
                        @endforeach
                    </select>
                    <x-jet-input-error class="mt-2 text-xs font-semibold" for="editForm.final_phase_id" />
                </div>
            </div>



            {{-- Prefix --}}
            <div class="mb-4 grid grid-cols-3 gap-3">
                <div>
                    <x-jet-label class="mb-2">Ícono actual</x-jet-label>
                    <img src="{{ asset('storage/img' . $editForm['icon']) }}" alt="Logo actual" class="w-32 h-32">
                </div>
                <div>
                    <x-jet-label class="mb-2">Actualizar ícono</x-jet-label>
                    <x-jet-input wire:model="newIcon" type="file" accept="image/*" class="w-full">
                    </x-jet-input>
                    <x-jet-input-error class="mt-2 text-xs font-semibold" for="newIcon" />
                </div>
                @if ($newIcon)
                    <div>
                        <x-jet-label class="mb-2">Ícono</x-jet-label>
                        <img src="{{ $newIcon->temporaryUrl() }}" alt="Logo nuevo" class="w-32 h-32">
                    </div>
                @endif
            </div>

        </x-slot>

        <x-slot name="footer">
            <div class="flex flex-end gap-3">
                <x-jet-danger-button wire:click="$set('isOpen', false)">
                    Cancelar
                </x-jet-danger-button>

                <x-jet-button wire:click="update">
                    Guardar cambios
                </x-jet-button>
            </div>
        </x-slot>
    </x-jet-dialog-modal>
</div>

@push('script')
    <script>
        Livewire.on('success', message => {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
            });

            Toast.fire({
                icon: 'success',
                title: message
            });
        });

        Livewire.on('error', message => {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: message,
                showConfirmButton: true,
                confirmButtonColor: '#1f2937',
            });
        });
    </script>
@endpush
