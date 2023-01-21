@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h1>Employee: {{ $employee->name }}</h1>
                    </div>

                    <div class="card-body">
                        <p><b>Email</b>: {{ $employee->email }}</p>
                        <p><b>Address</b>: {{ $employee->phone }}</p>
                        <p><b>Company Id</b>: {{ $employee->company_id }}</p>
                        <p><b>Company Name</b>: {{ $employee->company->name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
