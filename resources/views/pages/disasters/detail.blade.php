@extends('layouts.master')
@section('title')
    Detail Disaster
@endsection
@section('css')
    <!-- gridjs css -->
    <link rel="stylesheet" href="{{ URL::asset('vendors/libs/gridjs/theme/mermaid.min.css') }}">
@endsection
@section('page-title')
    Detail Disaster
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <h5 class="font-size-16">Title</h5>
                            <p class="mb-0">{{ @$disaster->title }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <h5 class="font-size-16">Address</h5>
                            <p class="mb-0">{{ @$disaster->address }}, {{ @$disaster->district->name }},
                                {{ @$disaster->district->city->name }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <h5 class="font-size-16">Status</h5>
                            <p class="mb-0">{{ @$disaster->status }}</p>
                        </div>
                    </div>

                    <div class="">
                        <a href="{{ route('disaster_edit', $disaster->id) }}"
                            class="btn btn-sm mt-4 btn-outline-success">Edit</a>
                        <a href="{{ route('disaster_stations', [$disaster->id]) }}"
                            class="btn btn-sm mt-4 btn-outline-success">See
                            Stations</a>
                    </div>
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h4>Volunteer Assignment</h4>
                    <div class="position-relative">
                        <div class="modal-button mt-2">
                            <div class="row align-items-start">
                                <div class="col-sm">
                                    <div class="mt-3 mt-md-0 mb-3">
                                        <a href="{{ route('volunteers') }}" class="btn btn-success">Assign
                                            Volunteer</a>
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
                                    <th scope="col">Disaster</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Start Date</th>
                                    <th scope="col">End Date</th>
                                </tr>

                            </thead>
                            <tbody>
                                @foreach ($assignments as $item)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ @$item->disaster->title }}
                                        </td>
                                        <td>
                                            {{ @$item->title }}
                                        </td>
                                        <td>
                                            {{ $item->status }}
                                        </td>
                                        <td>
                                            {{ $item->start_date }}
                                        </td>
                                        <td>
                                            {{ $item->end_date }}
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
                Array.from(tr.querySelectorAll("td")).map((td) => td.innerHTML) // Ambil HTML, bukan teks
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
