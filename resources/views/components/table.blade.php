@props([
    'headers' => [],
    'striped' => true,
    'hoverable' => true,
])

<div class="overflow-x-auto">
    <table {{ $attributes->merge(['class' => 'table' . ($striped ? ' table-zebra' : '') . ($hoverable ? ' hover' : '')]) }}>
        @if(count($headers))
            <thead>
                <tr>
                    @foreach($headers as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
        @endif

        <tbody>
            {{ $slot }}
        </tbody>

        @isset($footer)
            <tfoot>
                {{ $footer }}
            </tfoot>
        @endisset
    </table>
</div>
