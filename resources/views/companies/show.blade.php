@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h1>Company: {{ $company->name }}</h1>
                    </div>

                    <div class="card-body">
                        <p><b>Email</b>: {{ $company->email }}</p>
                        <p><b>Address</b>: {{ $company->address }}</p>
                        <p><b>Logo</b>: {{ $company->logo }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
