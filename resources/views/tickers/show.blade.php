<x-layout.default>
    <main class="mx-auto w-full flex justify-center">
        <div class="max-w-6xl mt-8 dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
            <h1 class="text-center dark:text-white py-4 text-xl">Simulations</h1>
            <div class="w-full max-w-6xl dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div class="overflow-x-auto relative shadow-md sm:rounded-lg m-4">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="py-3 px-6">id</th>
                                <th scope="col" class="py-3 px-6">Threshold</th>
                                <th scope="col" class="py-3 px-6"></th>
                                <th scope="col" class="py-3 px-6 text-right">Profit</th>
                                <th scope="col" class="py-3 px-6 text-right">Net Profit</th>
                                <th scope="col" class="py-3 px-6 text-right">Entered Days</th>

                                <th scope="col" class="py-3 px-6 text-right">Total Profit</th>
                                <th scope="col" class="py-3 px-6 text-right">Total Net Profit</th>
                                <th scope="col" class="py-3 px-6 text-right">Profit Percentage</th>

                                <th scope="col" class="py-3 px-6 text-center">Date Range</th>
                                <th scope="col" class="py-3 px-6 text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($simulations as $simulation)
                                <tr
                                    class="{{ $loop->odd ? 'bg-white border-b dark:bg-gray-900 dark:border-gray-700' : 'bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700' }}">
                                    <td class="py-1 px-6" rowspan="2">
                                        <a
                                            href="{{ route('simulations.show', $simulation['id']) }}">{{ $simulation['id'] }}</a>
                                    </td>
                                    <td class="py-1 px-6 text-right" rowspan="2">
                                        {{ $simulation['threshold'] }}</a>
                                    </td>
                                    <td class="py-1 px-6 text-right">Long</td>
                                    <td class="py-1 px-6 text-right">{{ $simulation['long_profit'] }}</td>
                                    <td class="py-1 px-6 text-right">{{ $simulation['long_net_profit'] }}</td>
                                    <td class="py-1 px-6 text-right">{{ $simulation['long_entered_days'] }}</td>

                                    <td class="py-1 px-6 text-right" rowspan="2">{{ $simulation['total_profit'] }}
                                    <td class="py-1 px-6 text-right" rowspan="2">
                                        {{ $simulation['total_net_profit'] }}</td>
                                    <td class="py-1 px-6 text-right" rowspan="2">
                                        {{ $simulation['profit_percentage'] }}
                                    </td>
                                    <td class="py-1 px-6 text-center" rowspan="2">{{ $simulation['date_range'] }}
                                    </td>
                                    <td class="py-1 px-6 text-center" rowspan="2">
                                        <a href="{{ route('simulations.show', $simulation['id']) }}"
                                            class="text-white px-4 py-2 bg-gray-700 rounded">Details</a>
                                    </td>
                                </tr>
                                <tr
                                    class="{{ $loop->odd ? 'bg-white border-b dark:bg-gray-900 dark:border-gray-700' : 'bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700' }}">
                                    <td class="py-1 px-6 text-right">Short</td>
                                    <td class="py-1 px-6 text-right">{{ $simulation['short_profit'] }}</td>
                                    <td class="py-1 px-6 text-right">{{ $simulation['short_net_profit'] }}</td>
                                    <td class="py-1 px-6 text-right">{{ $simulation['short_entered_days'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="flex flex-wrap justify-center w-full my-20">{!! $links !!}</div>
        </div>

        <div class="ml-4">
            <div class="w-full max-w-sm mx-auto mt-8 dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div class="overflow-x-auto relative p-4 w-full overflow-hidden">
                    <div class="text-md text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <div class="grid grid-cols-2">
                            <div class="px-2 py-2">Ticker</div>
                            <div class="px-2 py-2 text-right">{{ $ticker['symbol'] }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-layout.default>
