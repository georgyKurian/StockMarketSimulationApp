<x-layout.default>
    <main class="mx-auto w-full flex justify-center">
        <div class="max-w-6xl mt-8 dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
            <div class="w-full max-w-6xl dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div class="overflow-x-auto relative shadow-md sm:rounded-lg m-4">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="py-3 px-6">Id</th>
                                <th scope="col" class="py-3 px-6">Symbol</th>
                                <th scope="col" class="py-3 px-6">Highest Profit</th>
                                <th scope="col" class="py-3 px-6">Number Of Simulations</th>
                                <th scope="col" class="py-3 px-6">Date Range</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tickers as $ticker)
                                <tr
                                    class="{{ $loop->odd ? 'bg-white border-b dark:bg-gray-900 dark:border-gray-700' : 'bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700' }}">
                                    <td class="py-1 px-6">
                                        <a href="{{ route('tickers.show', $ticker['id']) }}">{{ $ticker['id'] }}</a>
                                    </td>
                                    <td class="py-1 px-6">
                                        <a href="{{ route('tickers.show', $ticker['id']) }}">{{ $ticker['symbol'] }}</a>
                                    </td>
                                    <td class="py-1 px-6 text-right">
                                        {{ $ticker['highest_profit'] }}</a>
                                    </td>
                                    <td class="py-1 px-6 text-right">{{ $ticker['number_of_simulations'] }}</td>
                                    <td class="py-1 px-6 text-right">{{ $ticker['date_range'] }}</td>
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
