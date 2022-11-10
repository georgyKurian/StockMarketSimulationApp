<div class="m-4 p-4 bg-white overflow-hidden">
    <table>
        <thead>
            <tr>
                <td>Day</td>
                <td>Time</td>
                <td>High</td>
                <td>Low</td>
                <td>Long Enter</td>
                <td>Long Exit</td>
                <td>Short Enter</td>
                <td>Short Exit</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($intraday->candleSticks as $candleStick)
                <tr>
                    <td>{{ $intraday->day_index }}</td>
                    <td>{{ $candleStick->time }}</td>
                    <td>{{ $candleStick->high }}</td>
                    <td>{{ $candleStick->low }}</td>
                    <td>{{ $candleStick->day_index }}Long Enter</td>
                    <td>{{ $candleStick->day_index }}Long Exit</td>
                    <td>{{ $candleStick->day_index }}Short Enter</td>
                    <td>{{ $candleStick->day_index }}Short Exit</td>
                </tr>
            @endforeach
        </tbody>
        
    </table>
</div>