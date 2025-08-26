@props([
    'title' => null,
    'headers' => [],
    'data' => null,
    'showViewAll' => false,
    'viewAllUrl' => null,
    'viewAllText' => 'View All',
    'emptyMessage' => null,
])

@php
    $dataCount = null;
    if ($data instanceof \Illuminate\Support\Collection) {
        $dataCount = $data->count();
    } elseif (is_array($data)) {
        $dataCount = count($data);
    }
@endphp

<div class="overflow-x-auto bg-white rounded-lg shadow">
    @if($title)
        <div class="d-flex justify-content-between align-items-center p-3 border-bottom">
            <h5 class="m-0">{{ $title }}</h5>
            @if($showViewAll && $viewAllUrl)
                <a href="{{ $viewAllUrl }}" class="btn btn-sm btn-outline-secondary">{{ $viewAllText }}</a>
            @endif
        </div>
    @endif

    <table {{ $attributes->merge(['class' => 'min-w-full table table-striped align-middle']) }}>
        @if(!empty($headers))
            <thead>
                <tr>
                    @foreach($headers as $th)
                        <th scope="col">{{ $th }}</th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody>
            @if(($dataCount === 0) && $emptyMessage)
                <tr>
                    <td colspan="{{ max(1, count($headers)) }}" class="text-center text-muted py-4">{{ $emptyMessage }}</td>
                </tr>
            @else
                {{ $slot }}
            @endif
        </tbody>
    </table>
</div>
