<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Listado de usuarios del sistema</h2>
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
                            Usuario
                        </th>
                        <th scope="col"
                            class="w-1/3 px-4 py-2 text-center text-sm font-bold text-gray-500 uppercase tracking-wider">
                            Correo electrónico
                        </th>
                        <th scope="col"
                            class="w-1/3 px-4 py-2 text-center text-sm font-bold text-gray-500 uppercase tracking-wider">
                            Rol
                        </th>
                        <th scope="col"
                            class="px-4 py-2 text-center text-sm font-bold text-gray-500 uppercase tracking-wider">
                            Modificar rol
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
                                    {{ $stat['email'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center">
                                <p class="text-sm uppercase">
                                    {{ $stat['role'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3 whitespace-nowrap text-center">
                                @if ($stat['role'] != 'sudo')
                                @livewire('users.edit-user-role', ['user_id' => $stat['id']], key($stat['id']))
                                @endif
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
