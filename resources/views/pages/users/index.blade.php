@extends('layouts.master')
@section('title')
    Users
@endsection
@section('css')
    <!-- gridjs css -->
    <link rel="stylesheet" href="{{ URL::asset('vendors/libs/gridjs/theme/mermaid.min.css') }}">
@endsection
@section('page-title')
    Users
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <div class="position-relative">
                        <div class="modal-button mt-2">
                            <div class="row align-items-start">
                                <div class="col-sm">
                                    <div class="mt-3 mt-md-0 mb-3">
                                        <a href="{{ route('user_create') }}" class="btn btn-success"><i
                                                class="mdi mdi-plus me-1"></i> Add
                                            User</a>
                                    </div>
                                </div>
                            </div>
                            <!-- end row -->
                        </div>
                    </div>

                    <div id="table-list-gridjs" style="min-height: 10vh"></div>

                    <div class="table-responsive d-none">
                        <table id="table-data" class="table table-nowrap table-light">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Volunteer</th>
                                    <th scope="col">Action</th>
                                </tr>

                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ $item->name }}
                                        </td>
                                        <td>
                                            {{ @$item->email }}
                                        </td>
                                        <td>
                                            {{ count(@$item->volunteers ?? []) ? 'Ya' : 'Belum' }} 
                                        </td>
                                        <td>
                                            <a href="javascript:void();" title="Reset Password"
                                                onclick="if(confirm('Are you sure want to reset password this user?')) { event.preventDefault(); document.getElementById('reset-password-item-{{ $item->id }}').submit(); }"
                                                class="btn btn-primary"><i class='bx bx-reset'></i></a>
                                            <form id="reset-password-item-{{ $item->id }}"
                                                action="{{ route('user_reset_password', $item->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                            </form>

                                            <a href="javascript:void();"
                                                onclick="if(confirm('Are you sure delete this user?')) { event.preventDefault(); document.getElementById('delete-item-{{ $item->id }}').submit(); }"
                                                class="btn btn-danger"><i class='bx bx-trash'></i></a>
                                            <form id="delete-item-{{ $item->id }}"
                                                action="{{ route('user_delete', $item->id) }}" method="POST"
                                                style="display: none;">
                                                @method('delete')
                                                @csrf
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
@endsection
@section('scripts')
    <!-- gridjs js -->
    <script src="{{ URL::asset('vendors/libs/gridjs/gridjs.umd.js') }}"></script>

    <!-- App js -->
    <script src="{{ URL::asset('vendors/js/app.js') }}"></script>

    <script>
        function getTableData(tableId) {
            const table = document.getElementById(tableId);
            const headers = Array.from(table.querySelectorAll("thead th")).map((th) => th.textContent);
            const rows = Array.from(table.querySelectorAll("tbody tr")).map((tr) =>
                Array.from(tr.querySelectorAll("td")).map((td) => td.innerHTML)
            );
            return {
                headers,
                rows
            };
        }

        const tableData = getTableData("table-data");

        new gridjs.Grid({
            columns: tableData.headers,
            data: tableData.rows.map((row) =>
                row.map((cell) => gridjs.html(cell))
            ),
            pagination: true,
            search: true
        }).render(document.getElementById("table-list-gridjs"));
    </script>
@endsection
