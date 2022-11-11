<div class="overflow-x-auto relative shadow-md sm:rounded-lg m-4">
    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="py-3 px-6">Day</th>
                <th scope="col" class="py-3 px-6">Time</th>
                <th scope="col" class="py-3 px-6">Open</th>
                <th scope="col" class="py-3 px-6">Close</th>
                <th scope="col" class="py-3 px-6">High</th>
                <th scope="col" class="py-3 px-6">Low</th>
                <th scope="col" class="py-3 px-6">Long Enter</th>
                <th scope="col" class="py-3 px-6">Long Exit</th>
                <th scope="col" class="py-3 px-6">Short Enter</th>
                <th scope="col" class="py-3 px-6">Short Exit</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($candleSticks as $candleStick)
                <tr class="{{ $loop->odd ? 'bg-white border-b dark:bg-gray-900 dark:border-gray-700' : 'bg-gray-50 border-b dark:bg-gray-800 dark:border-gray-700'}}">
                    <td class="py-1 px-6">{{ $candleStick['id'] }}</td>
                    <td class="py-1 px-6">{{ $candleStick['time'] }}</td>
                    <td class="py-1 px-6">{{ $candleStick['open'] }}</td>
                    <td class="py-1 px-6">{{ $candleStick['close'] }}</td>
                    <td class="py-1 px-6">{{ $candleStick['high'] }}</td>
                    <td class="py-1 px-6">{{ $candleStick['low'] }}</td>
                    <td class="py-1 px-6">{{ $candleStick['long_enter_at'] }}</td>
                    <td class="py-1 px-6">{{ $candleStick['long_exit_at'] }}</td>
                    <td class="py-1 px-6">{{ $candleStick['short_enter_at'] }}</td>
                    <td class="py-1 px-6">{{ $candleStick['short_exit_at'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

           
        