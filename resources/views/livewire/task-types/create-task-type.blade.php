<div>
    <x-jet-secondary-button wire:click="createTaskType">
        Crear nuevo tipo de tarea
    </x-jet-secondary-button>

    <x-jet-dialog-modal wire:model="isOpen">
        <x-slot name="title">
            Crear nuevo tipo de tarea
        </x-slot>

        <x-slot name="content">
            {{-- Name --}}
            <div class="mb-4">
                <x-jet-label class="mb-2">Nombre</x-jet-label>
                <x-jet-input wire:model.defer="createForm.name" type="text" class="w-full"
                    placeholder="Ingrese el nombre del tipo de tarea"></x-jet-input>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.name" />
            </div>

            {{-- AREA --}}
            <div class="mb-4">
                <x-jet-label class="mb-2">Área</x-jet-label>
                <select wire:model.defer="createForm.area_id" class="input-control w-full">
                    <option value="" disabled>Seleccione una área</option>
                    @foreach ($areas as $area)
                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                    @endforeach
                </select>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.area_id" />
            </div>

            {{-- INITIAL PHASE --}}
            <div class="mb-4">
                <x-jet-label class="mb-2">Etapa inicial</x-jet-label>
                <select wire:model="createForm.initial_phase_id" class="input-control w-full">
                    <option value="" disabled>Seleccione una etapa</option>
                    @foreach ($initial_phases as $phase)
                        <option value="{{ $phase->id }}">{{ $phase->name }}</option>
                    @endforeach
                </select>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.initial_phase_id" />
            </div>

            {{-- FINAL PHASE --}}
            <div class="mb-4">
                <x-jet-label class="mb-2">Etapa final</x-jet-label>
                <select wire:model="createForm.final_phase_id" class="input-control w-full">
                    <option value="" disabled>Seleccione una etapa</option>
                    @foreach ($final_phases as $phase)
                        <option value="{{ $phase->id }}">{{ $phase->name }}</option>
                    @endforeach
                </select>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.final_phase_id" />
            </div>

            <div class="mb-4">
                <x-jet-label class="mb-2">Tipo de tarea</x-jet-label>

                {{-- Input type radio --}}
               <div class="flex flex-col gap-1">
                <div class="flex items-center ">
                    <x-jet-input name="val" type="radio" class="block" value="1" wire:model.defer="createForm.initial_task"/>
                    <x-jet-label class="ml-2">Tarea inicial</x-jet-label>
                </div>

                <div class="flex items-center ">
                    <x-jet-input name="val" type="radio" class="block" wire:model.defer="intermediate" checked />
                    <x-jet-label class="ml-2">Tarea intermedia (por defecto)</x-jet-label>
                </div>

                <div class="flex items-center ">
                    <x-jet-input name="val" type="radio" class="block" value="1" wire:model.defer="createForm.final_task" />
                    <x-jet-label class="ml-2">Tarea final</x-jet-label>
                </div>
               </div>
            </div>

        </x-slot>

        <x-slot name="footer">
            <div class="flex flex-end gap-3">
                <x-jet-danger-button wire:click="$set('isOpen', false)">
                    Cancelar
                </x-jet-danger-button>

                <x-jet-button wire:click="save">
                    Crear tipo de tarea
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
    </script>
@endpush
