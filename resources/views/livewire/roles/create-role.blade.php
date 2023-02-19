<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.roles.index') }}">
                <x-jet-button>
                    <i class="fas fa-arrow-left mr-2"></i>
                    Volver
                </x-jet-button>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear un nuevo rol
            </h2>
            <div>

            </div>
        </div>
    </x-slot>

    <div class="px-10 py-6 bg-white rounded-lg">

        <span class="font-bold text-gray-700 text-lg">
            <i class="fas fa-info-circle text-gray-700 text-lg mr-2"></i>
            Crear un nuevo rol
            <hr class="my-2">
        </span>

        {{-- Nombre --}}
        <div>
            <x-jet-label class="mb-2">Nombre del rol</x-jet-label>
            <x-jet-input wire:model="createForm.name" type="text" class="w-full" placeholder="Ingrese el nombre del rol"></x-jet-input>
            <x-jet-input-error class="mt-2 text-xs font-semibold" for="createForm.name" />
        </div>

        <p class="font-bold text-gray-700 text-lg my-2">Listado de permisos:</p>

        <div class="columns-3">
            @foreach ($availablePermissions as $permission)
                <div>
                    <label>
                        <input type="checkbox" name="permissions[]"
                            value="{{ $permission->name }}"
                            wire:click="$emit('togglePermission', '{{ $permission->name }}')"
                            >
                        {{ $permission->name }}
                    </label>
                </div>
            @endforeach
        </div>

        <div class="flex mt-4 justify-end">
            <x-jet-button wire:click='createRole'>
                Crear nuevo rol
            </x-jet-button>
        </div>
    </div>


    @push('script')
        <script>
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

</div>
