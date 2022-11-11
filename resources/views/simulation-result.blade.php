<x-layout.default>
    <main class="mx-auto w-full flex justify-center">
        {{-- <div class="max-w-6xl mt-8 dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
            <x-intraday.simulation-table :candle-sticks="$candleSticks"/>
        </div> --}}
        <div class="ml-4">
            <div class="w-full max-w-sm mx-auto mt-8 dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <x-simulation.result :summary="$summary"/>
            </div>
        </div>
    </main>
    <div class="flex flex-wrap justify-center w-full my-20">{!! $links !!}</div>
    
</x-layout.default>