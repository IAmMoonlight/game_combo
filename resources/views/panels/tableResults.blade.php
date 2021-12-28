@extends('layouts.app')

@section('content')
    <div id="tableResults"></div>
@endsection

@push('scripts')
    <script>
        window.pageData = {
            urlCheckUsersData: '{{ route('admin.check-gamers-answers') }}',
            listGamers: {!! $listGamers !!}
        }
    </script>
@endpush
