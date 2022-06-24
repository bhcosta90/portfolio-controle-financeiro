@extends('layouts.app')

@section('content')
<div class='card'>
    <div class='card-header'>Meus Dados</div>
    <div class='card-body'>
        <form method="POST" action="{{ route('profile.data') }}">
            @csrf

            <label for="name" class="col-form-label">{{ __('Name') }}</label>

            <div>
                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') ?? $model->name }}" required autocomplete="name">

                @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>
            <label for="email" class="col-form-label">{{ __('Email Address') }}</label>

            <div>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') ?? $model->email }}" required autocomplete="email">

                @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <label for="company_id" class="col-form-label">{{ __('Company') }}</label>

            <div>
                <select name="company_id" required class="form-control @error('company_id') is-invalid @enderror">
                    <option value="">{{ __('Select') }}...</option>
                    @foreach($model->tenants as $tenant)
                        <option value="{{ $tenant->id }}" {{ $tenant->id == $model->tenant_id ? "selected" : "" }}>{{ $tenant->name }}</option>
                    @endforeach
                </select>

                @error('company_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <label for="password" class="col-form-label">{{ __('Password') }}</label>

            <div class='mb-3'>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>


            <button type="submit" class="btn btn-primary">
                {{ __('Update data') }}
            </button>
        </form>
    </div>
</div>
@endsection
