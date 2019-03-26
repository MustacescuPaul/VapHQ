@extends('layouts.stoc')

@section('content')
<stoc-index :modificari="{{ json_encode($modificari) }}" ></comanda-index>
@endsection
