@extends('layouts._default', $corp) 
@section('content')
    @include('layouts._navi_bar')
    @foreach ($corps as $corp)
        <p class="do">dosomething</p>
    @endforeach

@endsection