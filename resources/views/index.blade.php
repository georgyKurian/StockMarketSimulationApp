<x-layout.default>
    @foreach ($days as $day)
        <x-intraday :intraday="$day"/>
    @endforeach
</x-layout.default>