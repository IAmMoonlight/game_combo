@extends('layouts.app')
@push('head')

    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/liquidGradient.css') }}">
@endpush

@section('content')
    <div id="table-snow-header"></div>
    <div id="tableResults"></div>
    <canvas class="floating-gradient" width="100%" height="100%" style="pointer-events: auto; opacity: 1;"></canvas>
@endsection

@push('scripts')
    <script>
        window.pageData = {
            urlCheckUsersData: '{{ route('admin.check-gamers-answers') }}',
            listGamers: {!! $listGamers !!},
        }
    </script>
@endpush
