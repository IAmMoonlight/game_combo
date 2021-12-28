@extends('layouts.app')

@section('content')
    <div id="adminPanel"></div>
@endsection

@push('scripts')
    <script>
        window.pageData = {
            urlCheckUsersData: '{{ route('admin.check-gamers-answers') }}',
            urlReset: '{{ route('admin.reset-answers') }}',
            urlPlayPause: '{{ route('admin.play-pause') }}',
            urlChangeTypeAnswer: '{{ route('admin.change-type-answer') }}',
            urlUpdateScore: '{{ route('admin.change-score') }}',
            listGamers: {!! $listGamers !!}
        }
    </script>
@endpush
