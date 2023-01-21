@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h1>{{ __('Employees') }}</h1>
                        <a href="{{ route('employees.create') }}" class="btn btn-success">{{ __('New employee') }}</a>
                    </div>

                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Company</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($employees as $employee)
                                <tr>
                                    <th scope="row">{{ $employee->id }}</th>
                                    <td><a href="{{ route('employees.show', $employee) }}">{{ $employee->name }}</a></td>
                                    <td>{{ $employee->email }}</td>
                                    <td>{{ $employee->phone }}</td>
                                    <td>{{ $employee->company->name }}</td>
                                    <td>
                                        <a class="btn btn-secondary" href="{{ route('employees.edit', [$employee, 'company_id' => $employee->company->id]) }}">Edit</a>

                                        <form id=destroyCompany action="{{ route('employees.destroy', $employee) }}" method="POST">
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
