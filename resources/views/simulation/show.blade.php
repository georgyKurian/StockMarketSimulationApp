<x-layout.default>
    <main class="mx-auto w-full flex justify-center">
        <div class="max-w-6xl mt-8 dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
            <h1 class="text-center dark:text-white py-4 text-xl">Days</h1>
            <div class="w-full max-w-6xl dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div class="overflow-x-auto relative shadow-md sm:rounded-lg m-4">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="py-3 px-6">id</th>
                                <th scope="col" class="py-3 px-6">Date</th>
                                <th scope="col" class="py-3 px-6">Long Profit</th>
                                <th scope="col" class="py-3 px-6">Short Profit</th>
                                <th scope="col" class="py-3 px-6">Total Profit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($days as $day)
                                <tr
                                    class="{{ $loop->odd ? 'bg-white border-b dark:bg-gray-900 dark:border-gray-700' : 'bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700' }}">
                                    <td class="py-1 px-6">
                                        <a href="{{ route('days.show', $day['id']) }}">{{ $day['id'] }}</a>
                                    </td>
                                    <td class="py-1 px-6">
                                        <a href="{{ route('days.show', $day['id']) }}">{{ $day['date'] }}</a>
                                    </td>
                                    <td class="py-1 px-6 text-right">{{ $day['long_profit'] }}</td>
                                    <td class="py-1 px-6 text-right">{{ $day['short_profit'] }}</td>
                                    <td class="py-1 px-6 text-right">{{ $day['total_profit'] }}</td>
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
                            <div class="px-2 py-2 text-right">
                                <a
                                    href="{{ route('tickers.show', $simulation['ticker']['id']) }}">{{ $simulation['ticker']['symbol'] }}</a>
                            </div>
                            <div class="px-2 py-2">Total Long P/L</div>
                            <div class="px-2 py-2 text-right">{{ $simulation['long_profit'] }}</div>
                            <div class="px-2 py-2">Total Short P/L</div>
                            <div class="px-2 py-2 text-right">{{ $simulation['short_profit'] }}</div>
                            <div class="px-2 py-2">Sub-Total P/L</div>
                            <div class="px-2 py-2 text-right">{{ $simulation['total_profit'] }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-6xl mt-8 dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <h1 class="text-center dark:text-white py-4 text-xl">Months</h1>
                <div class="w-full max-w-6xl dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                    <div class="overflow-x-auto relative shadow-md sm:rounded-lg m-4">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="py-3 px-6">Month</th>
                                    <th scope="col" class="py-3 px-6">Long Profit</th>
                                    <th scope="col" class="py-3 px-6">Short Profit</th>
                                    <th scope="col" class="py-3 px-6">Total Profit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($months['data'] as $month)
                                    <tr
                                        class="{{ $loop->odd ? 'bg-white border-b dark:bg-gray-900 dark:border-gray-700' : 'bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700' }}">
                                        <td class="py-1 px-6">
                                            {{ "{$month['year']}, {$month['month']}" }}
                                        </td>
                                        <td class="py-1 px-6 text-right">{{ $month['long_profit'] }}</td>
                                        <td class="py-1 px-6 text-right">{{ $month['short_profit'] }}</td>
                                        <td class="py-1 px-6 text-right">{{ $month['total_profit'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="flex flex-wrap justify-center w-full my-20">{!! $months['links'] !!}</div>
            </div>
        </div>
    </main>
</x-layout.default>
