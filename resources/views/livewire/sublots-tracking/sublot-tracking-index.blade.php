<div class="container py-6">
    <div class="bg-white rounded-lg px-10 py-5">

        <p class="font-bold text-gray-700 text-lg">
            <i class="fas fa-info-circle text-gray-700 text-lg mr-2"></i>
            Sublotes de prueba
        </p>
        <hr class="mt-2 mb-4">

        <div class="flex gap-3">
            <div class="w-3/4">
                <select wire:model='selectedSublot' class="input-control w-full">
                    <option value="">Seleccione una opción</option>
                    @foreach ($sublotes as $sublot)
                        <option value="{{ $sublot['id'] }}">
                            Sublote {{ $sublot['code'] }} - {{ $sublot['product'] }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="w-1/4">
                <button wire:click="$emit('showSublot')" class="h-full w-full rounded-lg bg-gray-300">
                    <i class="fas fa-search mr-2"></i>
                    Buscar
                </button>
            </div>
        </div>

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
