@extends('layouts.app')

@section('content')

<div class="columns">
    <div class="column is-one-third is-offset-one-third m-t-100">
        <div class="card">
            <div class="card-content">
                <h1 class="title">Admin Login</h1>
                <form action="{{ route('admin.login.submit') }}" method="POST" role="form">
                    {{csrf_field()}}
                <div class="field">
                    <label for="username" class="label">Username</label>
                    <p class="control">
                        <input type="text" class="input {{$errors->has('username') ? 'is-danger' : ''}}" name="username" id="username" value="{{old('username')}}" required>
                    </p>
                     @if ($errors->has('username'))
                        <p class="help is-danger">{{$errors->first('username')}}</p>
                    @endif
                </div>
                <div class="field">
                    <label for="password" class="label">Password</label>
                    <p class="control">
                        <input type="password" class="input {{$errors->has('password') ? 'is-danger' : ''}}" name="password" id="password" required>
                    </p>
                    @if ($errors->has('password'))
                        <p class="help is-danger">{{$errors->first('password')}}</p>
                    @endif
                </div>


                <button class="button is-primary is-outlined is-fullwidth m-t-30">Log In</button>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection
