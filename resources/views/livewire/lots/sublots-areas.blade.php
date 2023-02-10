<div class="container py-6">

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Sublotes por área</h2>

        </div>
    </x-slot>

    <x-responsive-table>
        <div class="px-6 py-4 bg-gray-200">
            <div class="grid grid-cols-6 gap-4">
                <div class="col-span-5">
                    <x-jet-label class="mb-2 text-lg font-semibold">Área</x-jet-label>
                    <select wire:model="area" class="input-control w-full">
                        <option value="">Todas las áreas</option>
                        @foreach ($areas as $a)
                            <option value="{{ $a->id }}">{{ $a->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-end justify-center">
                    <a href="{{ route('admin.sublots-areas.pdf', ['area' => $area]) }}">
                        <x-jet-danger-button>
                            <i class="fas fa-file-pdf mr-2"></i>
                            <p class="py-1 px-1">Descargar PDF</p>
                        </x-jet-danger-button>
                    </a>
                </div>
            </div>
        </div>

        @if ($sublotStats)
            <table class="text-gray-600 min-w-full divide-y divide-gray-200">
                <thead class="border-b border-gray-300 bg-gray-200">
                    <tr class="text-center text-sm text-gray-500 uppercase">
                        <th scope="col" class="px-4 py-2">
                            Código
                        </th>
                        <th scope="col" class="px-4 py-2">
                            Producto
                        </th>
                        <th scope="col" class="px-4 py-2">
                            Área
                        </th>
                        <th scope="col" class="px-4 py-2">
                            Unidades stock
                        </th>
                        <th scope="col" class="px-4 py-2">
                            M2
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($sublotStats as $stat)
                        <tr class="bg-gray-50">
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm uppercase">
                                    {{ $stat['code'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3">
                                <p class="text-sm uppercase">
                                    {{ $stat['product'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3">
                                <p class="text-sm uppercase">
                                    {{ $stat['area'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <p class="text-sm uppercase">
                                    {{ $stat['actual_quantity'] }}
                                </p>
                            </td>
                            <td class="px-6 py-3 text-center font-bold">
                                <p class="text-sm">
                                    {{ $stat['m2'] }}
                                </p>
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

</div>
