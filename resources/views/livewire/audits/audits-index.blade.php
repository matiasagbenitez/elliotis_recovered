<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">

            {{-- GO BACK BUTTON --}}
            <a href="{{ route('admin.tenderings.index') }}">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Auditoría</h2>
            </a>

            {{-- PDF BUTTON --}}
            <a href="#">
                <x-jet-danger-button>
                    <i class="fas fa-file-pdf mr-2"></i>
                    Descargar PDF
                </x-jet-danger-button>
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto bg-white px-10 py-5 my-6 rounded-lg">

        {{-- Título --}}
        <p class="font-bold text-gray-700 text-lg ">
            <i class="fas fa-info-circle text-gray-700 text-lg mr-2"></i>
            Detalle de auditoría
        </p>
        <hr class="my-3">

        {{-- Select --}}
        <x-jet-label for="select" class="mb-2" value="Seleccione el modelo que desea auditar" />
        <div class="flex items-center justify-between mb-6">
            <select wire:model="selected_model" class="input-control w-full">
                <option value="">Todos los modelos</option>
                @foreach ($models as $model)
                    <option value="{{ $model['model'] }}">{{ $model['name'] }}</option>
                @endforeach
            </select>
        </div>

        <x-responsive-table>

            @if ($audits->isNotEmpty())
                <table class="text-gray-600 min-w-full divide-y divide-gray-200 table-fixed">
                    <thead class="border-b border-gray-300 bg-gray-200">
                        <tr class="text-center text-sm text-gray-500 uppercase">
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
                                Modelo
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
                                    <p class="text-sm">
                                        {{ $audit['model'] }}
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
