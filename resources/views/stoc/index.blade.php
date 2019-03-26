@extends('layouts.stoc')

@section('content')
<stoc-index :modificari="{{ json_encode($modificari) }}" ></stoc-index>
@endsection
