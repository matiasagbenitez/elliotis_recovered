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
                Permisos del rol
                <span class="uppercase">
                    {{ $role->name }}
                </span>
            </h2>
            <div>

            </div>
        </div>
    </x-slot>

    <div class="px-10 py-6 bg-white rounded-lg">

        <span class="font-bold text-gray-700 text-lg">
            <i class="fas fa-info-circle text-gray-700 text-lg mr-2"></i>
            Gestión de permisos
            <hr class="my-2">
        </span>

        <p class="font-bold text-gray-700 text-lg my-3">Rol:
            <span class="font-normal">{{ $role->name }}</span>
        </p>
        <p class="font-bold text-gray-700 text-lg mb-2">Listado de permisos:</p>

        <div class="columns-3">
            @foreach ($availablePermissions as $permission)
            <div>
                <label>
                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                        {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}
                        wire:click="$emit('togglePermission', '{{ $permission->name }}')">
                    {{ $permission->name }}
                </label>
            </div>
        @endforeach
        </div>

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
