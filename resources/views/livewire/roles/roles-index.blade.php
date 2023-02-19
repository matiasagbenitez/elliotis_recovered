<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Roles y permisos del sistema</h2>
            <div>
                <a href="{{ route('admin.roles.create') }}">
                    <x-jet-secondary-button>
                        Crear un nuevo rol
                    </x-jet-secondary-button>
                </a>
            </div>
        </div>
    </x-slot>

    <x-responsive-table>

        @if ($stats)
            <table class="text-gray-600 min-w-full divide-y divide-gray-200">
                <thead class="border-b border-gray-300 bg-gray-200 whitespace-nowrap">
                    <tr>
                        <th scope="col"
                            class="px-4 py-2 text-center text-sm font-bold text-gray-500 uppercase tracking-wider">
                            ID
                        </th>
                        <th scope="col"
                            class="w-1/3 px-4 py-2 text-center text-sm font-bold text-gray-500 uppercase tracking-wider">
                            Nombre del rol
                        </th>
                        <th scope="col"
                            class="w-1/3 px-4 py-2 text-center text-sm font-bold text-gray-500 uppercase tracking-wider">
                            Usuarios sincronizados
                        </th>
                        <th scope="col"
                            class="w-1/3 px-4 py-2 text-center text-sm font-bold text-gray-500 uppercase tracking-wider">
                            Permisos asociados
                        </th>
                        <th scope="col"
                            class="px-4 py-2 text-center text-sm font-bold text-gray-500 uppercase tracking-wider">
                            Editar rol
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($stats as $stat)
                        <tr class="bg-gray-50">
                            <td class="px-6 py-3">
                                <p class="text-sm uppercase">
                                    {{ $stat['id'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center">
                                <p class="text-sm uppercase">
                                    {{ $stat['name'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center">
                                <p class="text-sm uppercase">
                                    {{ $stat['users'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center">
                                <p class="text-sm uppercase">
                                    {{ $stat['permissions'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center">
                                <a href="{{ route('admin.roles.edit', $stat['id']) }}"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="px-6 py-4">
                <p class="text-center font-semibold">No se encontraron registros coincidentes.</p>
            </div>
        @endif

    </x-responsive-table>

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
