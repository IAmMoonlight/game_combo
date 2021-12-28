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
            listGamers: {!! $listGamers !!},
            gameTypes: {
                "{{ \App\Models\GameSettings::TYPE_SOLO }}": 'соло',
                "{{ \App\Models\GameSettings::TYPE_CHOOSE }}": "4 варианта",
                "{{ \App\Models\GameSettings::TYPE_CHOOSE_FROM_TWO }}": "2 варианта"
            },
        }
    </script>
@endpush
