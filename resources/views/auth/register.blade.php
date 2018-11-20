@extends('layouts.app')

@section('content')
<div class="columns">
    <div class="column is-one-third is-offset-one-third m-t-100">
        <div class="card">
            <div class="card-content">
                <h1 class="title">Register</h1>
                <form action="{{ route('register') }}" method="POST" role="form">
                    {{csrf_field()}}
                <div class="field">
                    <label for="name" class="label">Name</label>
                    <p class="control">
                        <input type="text" class="input {{$errors->has('name') ? 'is-danger' : ''}}" name="name" id="name" value="{{old('name')}}" required>
                    </p>
                     @if ($errors->has('name'))
                        <p class="help is-danger">{{$errors->first('name')}}</p>
                    @endif
                </div>
                <div class="field">
                    <label for="username" class="label">Username</label>
                    <p class="control">
                        <input type="text" class="input {{$errors->has('username') ? 'is-danger' : ''}}" name="username" id="username" value="{{old('username')}}"  required>
                    </p>
                     @if ($errors->has('username'))
                        <p class="help is-danger">{{$errors->first('username')}}</p>
                    @endif
                </div>
                <div class="columns">
                <div class="column">
                <div class="field">
                    <label for="password" class="label">Password</label>
                    <p class="control">
                        <input type="password" class="input {{$errors->has('password') ? 'is-danger' : ''}}" name="password" id="password" required>
                    </p>
                    @if ($errors->has('password'))
                        <p class="help is-danger">{{$errors->first('password')}}</p>
                    @endif
                </div>
                </div>
                <div class="column">
                <div class="field">
                    <label for="password_confirmation" class="label">Confirm Password</label>
                    <p class="control">
                        <input type="password_confirmation" class="input {{$errors->has('password_confirmation') ? 'is-danger' : ''}}" name="password_confirmation" id="password_confirmation" required>
                    </p>
                    @if ($errors->has('password_confirmation'))
                        <p class="help is-danger">{{$errors->first('password_confirmation')}}</p>
                    @endif
                </div>
                </div>
                </div>
                <button class="button is-primary is-outlined is-fullwidth m-t-30">Register</button>
            </div>
            </form>
        </div>
        <h5 class="has-text-centered m-t-20"><a href="{{ route('login') }}" class="is-muted">Already have an Account?</a></h5>
    </div>
</div>
@endsection
