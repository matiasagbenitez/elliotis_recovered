<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Permisos del rol
                <span class="uppercase">
                    {{ $role->name }}
                </span>
            </h2>
        </div>
    </x-slot>

    <div class="px-10 py-6 bg-white rounded-lg">

        <span class="font-bold text-gray-700 text-lg">
            <i class="fas fa-info-circle text-gray-700 text-lg mr-2"></i>
            Gestión de permisos
            <hr class="my-2">
        </span>

        <p class="font-bold text-gray-700 text-lg">Rol:
            <span class="font-normal">{{ $role->name }}</span>
        </p>
        <p class="font-bold text-gray-700 text-lg">Listado de permisos:</p>

        @foreach ($availablePermissions as $permission)
            <div>
                <label>
                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                        wire:model.defer="selectedPermissions.{{ $permission->name }}">
                    {{ $permission->name }}
                </label>
            </div>
        @endforeach

        <div class="flex mt-4 justify-end">
            <x-jet-button wire:click='updateRole'>
                <i class="fas fa-save mr-2"></i>
                Guardar cambios
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
