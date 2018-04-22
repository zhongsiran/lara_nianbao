@extends('layouts._default')

@section('content')
    <form action="{{route('corp.update', $corp->RegNum)}}" method="POST">
        {{csrf_field()}}
        {{method_field('PATCH')}}
        <input type="text" name="phone_status">
        <input type="submit">
    </form>
@endsection