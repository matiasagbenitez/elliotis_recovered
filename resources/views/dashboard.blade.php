<x-app-layout>

    <div class="py-6">
        <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                @can('admin.dashboard')
                    @livewire('dashboard.dashboard')
                @endcan

            </div>
        </div>
    </div>

</x-app-layout>
