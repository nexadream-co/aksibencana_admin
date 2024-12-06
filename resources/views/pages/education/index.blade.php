@extends('layouts.master')
@section('title')
    Education
@endsection
@section('css')
    <!-- gridjs css -->
    <link rel="stylesheet" href="{{ URL::asset('vendors/libs/gridjs/theme/mermaid.min.css') }}">
@endsection
@section('page-title')
    Education
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
                                        <a href="{{ route('education_create') }}" class="btn btn-success"><i
                                                class="mdi mdi-plus me-1"></i> Add
                                            Education</a>
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
                                    <th scope="col">Banner</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col">Updated At</th>
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
                                            @if (@$item->banner)
                                                <img src="{{ url('storage') }}/{{ $item->banner }}"
                                                    style="width: 50px; height: 50px; object-fit:cover; border-radius: 5px">
                                            @endif
                                        </td>
                                        <td>
                                            {{ @$item->title }}
                                        </td>
                                        <td>
                                            {{ $item->created_at->format('Y-m-d h:i:s') }}
                                        </td>
                                        <td>
                                            {{ $item->updated_at->format('Y-m-d h:i:s') }}
                                        </td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('education_edit', $item->id) }}"
                                                    class="btn btn-primary me-1"><i class="bx bx-pencil"></i></a>
                                                <a href="javascript:void();"
                                                    onclick="if(confirm('Are you sure delete this item?')) { event.preventDefault(); document.getElementById('delete-item-{{ $item->id }}').submit(); }"
                                                    class="btn btn-danger"><i class='bx bx-trash'></i></a>
                                                <form id="delete-item-{{ $item->id }}"
                                                    action="{{ route('education_delete', $item->id) }}" method="POST"
                                                    style="display: none;">
                                                    @method('delete')
                                                    @csrf
                                                </form>
                                            </div>
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
