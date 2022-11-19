<x-layout.default>
    <main class="mx-auto w-full flex justify-center">
        <div class="max-w-6xl mt-8 dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
            <div class="w-full max-w-6xl dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div class="overflow-x-auto relative shadow-md sm:rounded-lg m-4">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="py-3 px-6">id</th>
                                <th scope="col" class="py-3 px-6">Ticker</th>
                                <th scope="col" class="py-3 px-6">Long Profit</th>
                                <th scope="col" class="py-3 px-6">Short Profit</th>
                                <th scope="col" class="py-3 px-6">Total Profit</th>
                                <th scope="col" class="py-3 px-6">Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($simulations as $simulation)
                                <tr
                                    class="{{ $loop->odd ? 'bg-white border-b dark:bg-gray-900 dark:border-gray-700' : 'bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700' }}">
                                    <td class="py-1 px-6">
                                        <a
                                            href="{{ route('simulations.show', $simulation['id']) }}">{{ $simulation['id'] }}</a>
                                    </td>
                                    <td class="py-1 px-6">
                                        {{ $simulation['ticker_symbol'] }}</a>
                                    </td>
                                    <td class="py-1 px-6 text-right">{{ $simulation['long_profit'] }}</td>
                                    <td class="py-1 px-6 text-right">{{ $simulation['short_profit'] }}</td>
                                    <td class="py-1 px-6 text-right">{{ $simulation['total_profit'] }}</td>
                                    <td class="py-1 px-6 text-right">{{ $simulation['created_at'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="flex flex-wrap justify-center w-full my-20">{!! $links !!}</div>
        </div>

    </main>
</x-layout.default>
