<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">

            {{-- GO BACK BUTTON --}}
            <a href="{{ route('admin.tenderings.index') }}">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Auditoría</h2>
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto bg-gray-50 my-4 rounded-lg">

        {{-- Título --}}
        <div class="px-6 py-3 bg-white rounded-t-lg">
            <p class="font-bold text-gray-800 text-lg ">
                <i class="fas fa-info-circle text-gray-700 text-lg mr-2"></i>
                Detalle de auditoría sobre la tabla ORDENES DE COMPRA
            </p>
        </div>

        <x-responsive-table>

            @if ($audits->isNotEmpty())
                <table class="text-gray-600 min-w-full divide-y divide-gray-200 table-fixed">
                    <thead class="border-b border-gray-300 bg-gray-200">
                        <tr class="text-center text-sm text-gray-500 uppercase whitespace-nowrap">
                            <th scope="col" class="px-4 py-2">
                                ID
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Responsable
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Fecha
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Acción
                            </th>
                            <th scope="col" class="px-4 py-2">
                                ID registro
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Estado Anterior
                            </th>
                            <th scope="col" class="px-4 py-2">
                                Estado actual
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($stats as $audit)
                            <tr>
                                <td class="px-6 py-2 text-center">
                                    <p class="text-sm uppercase">
                                        {{ $audit['id'] }}
                                    </p>
                                </td>
                                <td class="px-6 py-2 text-center">
                                    <p class="text-sm uppercase">
                                        {{ $audit['user'] }}
                                    </p>
                                </td>
                                <td class="px-6 py-2 text-center">
                                    <p class="text-sm uppercase">
                                        {{ $audit['created_at'] }}
                                    </p>
                                </td>
                                <td class="px-6 py-2 text-center">
                                    <p class="text-sm uppercase">
                                        {{ $audit['event'] }}
                                    </p>
                                </td>
                                <td class="px-6 py-2 text-center">
                                    <p class="text-sm uppercase">
                                        {{ $audit['auditable_id'] }}
                                    </p>
                                </td>
                                <td class="px-6 py-2 text-center">
                                    <span class="text-sm">
                                        {{ $audit['old_values'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-2 text-center">
                                    <p class="text-sm">
                                        {{ $audit['new_values'] }}
                                    </p>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="px-6 py-4">
                    <p class="text-center font-semibold">No se encontraron registros.</p>
                </div>
            @endif

        </x-responsive-table>

    </div>
</div>
