@props(['rows' => 5, 'cols' => 4])

@php
    // Predictable width variations for columns without randomness
    $headerWidths = ['w-16', 'w-20', 'w-24', 'w-20', 'w-16', 'w-24', 'w-20', 'w-16', 'w-20', 'w-24'];
    $cellWidths = ['w-24', 'w-28', 'w-32', 'w-28', 'w-24', 'w-32', 'w-28', 'w-24', 'w-28', 'w-32'];
@endphp

<div {{ $attributes->merge(['class' => 'card p-6']) }}>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-dark-700">
                    @for ($i = 0; $i < $cols; $i++)
                        <th class="px-4 py-3 text-left">
                            <div class="skeleton-text {{ $headerWidths[$i % count($headerWidths)] }} h-4"></div>
                        </th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < $rows; $i++)
                    <tr class="border-b border-dark-700/50">
                        @for ($j = 0; $j < $cols; $j++)
                            <td class="px-4 py-4">
                                <div class="skeleton-text {{ $cellWidths[$j % count($cellWidths)] }} h-3.5"></div>
                            </td>
                        @endfor
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>

