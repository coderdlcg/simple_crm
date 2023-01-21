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
                        <table id="companies" class="table data-table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Address</th>
                                <th width="200px">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>

                        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
                        <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
                        <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function destroyItem(id) {
            let sureRemove = confirm('Вы точно хотите удалить запись?');

            if (!sureRemove) {
                return false;
            }

            let el = document.getElementById('destroy-item-' + id);
            let url = el.getAttribute('data-url')
            let token = el.getAttribute('data-token')

            $.ajax({
                method: 'POST',
                url: url,
                data: { _token: token, _method: 'DELETE'},
                success: function(data)
                {
                    location.href = window.location.pathname
                },
                error: function (data) {
                    location.href = window.location.pathname
                }
            });

            return false;
        }

        $(document).ready(function() {
            $.noConflict();

            $('#companies').DataTable({
                ajax: 'companies',
                serverSide: true,
                processing: true,
                columns: [
                    {data: 'id', name: 'id'},
                    {data: 'name', name: 'name'},
                    {data: 'email', name: 'email'},
                    {data: 'address', name: 'address'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ]
            });
        })
    </script>
@endsection
