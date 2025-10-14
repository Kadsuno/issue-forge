@props(['rows' => 5, 'cols' => 4])

<div {{ $attributes->merge(['class' => 'card p-6']) }}>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-dark-700">
                    @for ($i = 0; $i < $cols; $i++)
                        <th class="px-4 py-3 text-left">
                            <div class="skeleton-text" style="width: {{ rand(60, 100) }}px; height: 16px;"></div>
                        </th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @for ($i = 0; $i < $rows; $i++)
                    <tr class="border-b border-dark-700/50">
                        @for ($j = 0; $j < $cols; $j++)
                            <td class="px-4 py-4">
                                <div class="skeleton-text" style="width: {{ rand(80, 150) }}px; height: 14px;"></div>
                            </td>
                        @endfor
                    </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>

