@props(['type' => 'info'])

@php
    switch ($type) {
        case 'info':
            $clases = "bg-blue-100 border-blue-500 text-blue-700";
            break;
        case 'danger':
            $clases = "bg-red-100 border-red-500 text-red-700";
            break;
        default:
            $clases = "bg-gray-100 border-gray-500 text-gray-700";
            break;
    }
@endphp

<article {{$attributes->merge(['class' => "$clases border-l-4 p-4"])}} role="alert">

    <h1 class="font-bold">{{ $title }}</h1>
    {{ $slot }}

</article>
