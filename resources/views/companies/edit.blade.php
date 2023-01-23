@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Company edit') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('companies.update', $company) }}" enctype="multipart/form-data">
                            @method('PUT')
                            @csrf

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name"
                                           type="text"
                                           class="form-control @error('name') is-invalid @enderror"
                                           name="name"
                                           value="{{ $company->name }}"
                                           required
                                           autocomplete="name"
                                           autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail') }}</label>

                                <div class="col-md-6">
                                    <input id="email"
                                           type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           name="email"
                                           value="{{ $company->email }}"
                                           required
                                           autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="address" class="col-md-4 col-form-label text-md-right">{{ __('Address') }}</label>

                                <div class="col-md-6">
                                    <input id="address"
                                           type="text"
                                           class="form-control @error('address') is-invalid @enderror"
                                           name="address"
                                           value="{{ $company->address }}">

                                    @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                    <div class="geomap">
                                        @if ($company->coordinates)
                                            @include('components.yandexmap')
                                        @else
                                            No map
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="logo-company" class="col-md-4 col-form-label text-md-right">{{ __('Logo') }}</label>

                                @if ($company->logo)
                                    <img src="{{ $company->logo }}" alt="Logo {{ $company->name }}" width="100" height="100">
                                @else
                                    No logo
                                @endif

                                <div class="col-md-6">
                                    <input id="logo-company"
                                           type="file"
                                           class="form-control-file @error('logo') is-invalid @enderror"
                                           name="logo">

                                    @error('logo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-4 offset-md-4">
                                    <button type="submit" class="btn btn-success">
                                        {{ __('Update') }}
                                    </button>
                                </div>
                            </div>
                        </form>


                        <hr>
                        <h2>Employees</h2>
                        <a href="{{ route('employees.create', ['company_id' => $company->id]) }}" class="btn btn-success">{{ __('Add employee') }}</a>

                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($company->employees as $employee)
                                <tr>
                                    <th scope="row">{{ $employee->id }}</th>
                                    <td><a href="{{ route('employees.show', $employee) }}">{{ $employee->name }}</a></td>
                                    <td>{{ $employee->email }}</td>
                                    <td>{{ $employee->phone }}</td>
                                    <td>
                                        <a class="btn btn-secondary" href="{{ route('employees.edit', $employee) }}">Edit</a>

                                        <form id=destroyCompany action="{{ route('employees.destroy', $employee) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach

                            @if (!$company->employees)
                                <tr>
                                    <th scope="row" colspan="5">Employees not found!</th>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
