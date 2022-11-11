<div class="overflow-x-auto relative p-4 w-full overflow-hidden">
    <div class="text-md text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
        <div class="grid grid-cols-2">
            <div class="px-2 py-2">Long P/L</div>
            <div class="px-2 py-2 text-right">{{ $summary['long_profit'] }}</div>
            <div class="px-2 py-2">Short P/L</div>
            <div class="px-2 py-2 text-right">{{ $summary['short_profit'] }}</div>
            <div class="px-2 py-2">Total P/L</div>
            <div class="px-2 py-2 text-right">{{ $summary['total_profit'] }}</div>
        </div>
    </div>
</div>