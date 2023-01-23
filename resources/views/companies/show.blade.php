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
                        <p><b>Logo</b>:
                            @if ($company->logo)
                            <img src="{{ $company->logo }}" alt="Logo {{ $company->name }}" width="100" height="100">
                            @else
                                No logo
                            @endif
                        </p>

                        <div class="geomap">
                            @if ($company->coordinates)
                                <p><b>Map</b>:</p>
                                @include('components.yandexmap')
                            @else
                                <p><b>Map</b>: No map</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
