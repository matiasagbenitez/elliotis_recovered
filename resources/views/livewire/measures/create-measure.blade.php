<div>
    <x-jet-secondary-button wire:click="createMeasure">
        Crear nueva medida de producción
    </x-jet-secondary-button>

    <x-jet-dialog-modal wire:model="isOpen">
        <x-slot name="title">
            Crear nueva medida de producción
        </x-slot>

        <x-slot name="content">

            {{-- Radio buttons to select if is trunk or not --}}
            <div class="my-4">
                <x-jet-label for="is_trunk" value="{{ __('¿Es rollo?') }}" />
                <div class="mt-2 flex space-x-2">
                    <x-jet-label for="is_trunk" value="{{ __('No') }}" />
                    <x-jet-input id="is_trunk" type="radio" class="mt-1 block" wire:model="showDiv" value="1" />
                    <x-jet-label for="is_trunk" value="{{ __('Si') }}" />
                    <x-jet-input id="is_trunk" type="radio" class="mt-1 block" wire:model="showDiv" checked value="0" />
                </div>
                <x-jet-input-error for="is_trunk" class="mt-2" />
            </div>

            @if ($showDiv)
                {{-- Height --}}
                <div class="mb-4">
                    <x-jet-label class="mb-2">Alto (pulgadas)</x-jet-label>
                    <select class="input-control w-full" wire:model="createForm.height">
                        <option value="" disabled selected>Seleccione el alto en pulgadas</option>
                        @foreach ($inches as $inch)
                            <option value="{{ $inch->id }}">{{ $inch->name }}"</option>
                        @endforeach
                    </select>
                    <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.height" />
                </div>

                {{-- Width --}}
                <div class="mb-4">
                    <x-jet-label class="mb-2">Ancho (pulgadas)</x-jet-label>
                    <select class="input-control w-full" wire:model="createForm.width">
                        <option value="" disabled selected>Seleccione el ancho en pulgadas</option>
                        @foreach ($inches as $inch)
                            <option value="{{ $inch->id }}">{{ $inch->name }}"</option>
                        @endforeach
                    </select>
                    <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.width" />
                </div>
            @endif

            {{-- Length --}}
            <div class="mb-4">
                <x-jet-label class="mb-2">Largo (pies)</x-jet-label>
                <select class="input-control w-full" wire:model="createForm.length">
                    <option value="" disabled selected>Seleccione el largo en pies</option>
                    @foreach ($feets as $feet)
                        <option value="{{ $feet->id }}">{{ $feet->name }}'</option>
                    @endforeach
                </select>
                <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.length" />
            </div>

            <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.name" />

            {{-- Favorite --}}
            {{-- Radio buttons for favorite attribute --}}
            <div class="my-4">
                <x-jet-label for="is_favorite" value="{{ __('¿Marcar como favorito?') }}" />
                <div class="mt-2 flex space-x-2">
                    <x-jet-label for="is_favorite" value="{{ __('No') }}" />
                    <x-jet-input id="is_favorite" type="radio" class="mt-1 block" wire:model="isFav" checked value="0" />
                    <x-jet-label for="is_favorite" value="{{ __('Si') }}" />
                    <x-jet-input id="is_favorite" type="radio" class="mt-1 block" wire:model="isFav" value="1" />
                </div>
                <x-jet-input-error for="is_favorite" class="mt-2" />
            </div>

        </x-slot>

        <x-slot name="footer">
            <div class="flex flex-end gap-3">
                <x-jet-danger-button wire:click="$set('isOpen', false)">
                    Cancelar
                </x-jet-danger-button>

                <x-jet-button wire:click="save">
                    Crear medida
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
