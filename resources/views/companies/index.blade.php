@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h1>{{ __('Companies') }}</h1>
                        <a href="{{ route('companies.create') }}" class="btn btn-success">{{ __('New company') }}</a>
                    </div>

                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Address</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($companies as $company)
                                <tr>
                                    <th scope="row">{{ $company->id }}</th>
                                    <td><a href="{{ route('companies.show', $company) }}">{{ $company->name }}</a></td>
                                    <td>{{ $company->email }}</td>
                                    <td>{{ $company->address }}</td>
                                    <td>
                                        <a class="btn btn-secondary" href="{{ route('companies.edit', $company) }}">Edit</a>

                                        <form id=destroyCompany action="{{ route('companies.destroy', $company) }}" method="POST">
                                            @csrf
                                            @method('DELETE')

                                            <button onclick="return confirm('Are you sure?')" type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
