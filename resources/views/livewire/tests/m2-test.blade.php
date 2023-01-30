<div class="container">
    <div class="bg-white my-12 px-10 py-5 rounded-xl">

        <select class="input-control w-full" wire:model='selectedLot'>
            <option value="">Seleccione un producto</option>
            @foreach ($lots as $lot)
                <option value="{{ $lot->id }}">
                    Lote: {{ $lot->code }}
                    &ensp;
                    Tarea: {{ $lot->task->typeOfTask->name }}
                </option>
            @endforeach
        </select>

    </div>
</div>
