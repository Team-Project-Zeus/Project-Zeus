

@extends('layouts.app')

@section('content')
    @if (session('error'))
        <div class="alert alert-success" role="alert">
            {{ session('error') }}
        </div>
    @endif
    <p>tebasdasst</p>

@endsection