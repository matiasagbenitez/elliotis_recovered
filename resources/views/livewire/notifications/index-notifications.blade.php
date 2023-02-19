<div class="container py-6">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Notificaciones') }}
        </h2>
    </x-slot>

    <div class="max-w-8xl mx-auto bg-white rounded-lg px-10 py-8">
        <span class="font-bold text-gray-500">
            <i class="fas fa-info-circle mr-1"></i>
            Listado de notificaciones
        </span>
        <hr class="my-3">

        @forelse ($text as $item)
            <div
                class="px-6 py-3 border-b cursor-pointer justify-between border-gray-100 {{ $item['read_at'] == null ? 'bg-gray-100' : 'bg-white hover:bg-gray-50' }}">

                <div>
                    <span class="font-bold text-gray-500">
                        {{ $item['title'] }}
                    </span>
                    <br>
                    <span class="text-gray-500">
                        {{ $item['message'] }}
                    </span>
                </div>

                <div class="flex justify-between mt-5">
                    <span class="text-gray-500 text-xs font-semibold">
                        {{ $item['created_at'] }}
                    </span>
                    <div class="flex gap-3 items-center">
                        @if ($item['read_at'] == null)
                            <span
                                class="text-gray-500 text-xs font-semibold hover:cursor-pointer hover:font-bold hover:text-gray-700"
                                wire:click="$emit('markAsRead', '{{ $item['id'] }}')">
                                <i class="fas fa-check-circle"></i>
                                Marcar como leída
                            </span>
                        @endif
                        @if ($item['licitation'])
                            <span
                                class="text-gray-500 text-xs font-semibold  hover:cursor-pointer hover:font-bold hover:text-gray-700"
                                wire:click="$emit('createTendering', '{{ $item['id'] }}')">
                                <i class="fas fa-shopping-cart"></i>
                                Generar licitación
                            </span>
                        @endif
                    </div>
                </div>

            </div>
        @empty
            <p class="text-lg font-bold text-gray-600 text-center">No hay notificaciones nuevas.</p>
        @endforelse
    </div>
</div>
