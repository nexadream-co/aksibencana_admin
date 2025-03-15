@extends('layouts.master')
@section('title')
    Volunteer Assignments
@endsection
@section('css')
    <!-- gridjs css -->
    <link rel="stylesheet" href="{{ URL::asset('vendors/libs/gridjs/theme/mermaid.min.css') }}">
@endsection
@section('page-title')
    Volunteer Assignments
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <h5 class="font-size-16">Name</h5>
                            <p class="mb-0">{{@$volunteer->user->name}}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <h5 class="font-size-16">Categories</h5>
                            <p class="mb-0">{{ implode(', ', @json_decode($volunteer->categories) ?? []) }}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <h5 class="font-size-16">Availability Status</h5>
                            <p class="mb-0">{{@$volunteer->availability_status}}</p>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <h5 class="font-size-16">Status</h5>
                            <p class="mb-0">{{@$volunteer->status}}</p>
                        </div>
                    </div>
                </div><!-- end card-body -->
            </div><!-- end card -->
        </div><!-- end col -->
    </div><!-- end row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">

                    <div class="position-relative">
                        <div class="modal-button mt-2">
                            <div class="row align-items-start">
                                <div class="col-sm">
                                    <div class="mt-3 mt-md-0 mb-3">
                                        <a href="{{ route('volunteer_assignment_create', [$volunteer->id]) }}" class="btn btn-success"><i
                                                class="mdi mdi-plus me-1"></i> Add
                                            Assignment</a>
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
                                    <th scope="col">Action</th>
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
                                        <td>
                                            <a href="{{ route('volunteer_assignment_edit', ['id' => $volunteer->id, 'assignment_id' => $item->id]) }}" class="btn btn-primary"><i
                                                    class="bx bx-pencil"></i></a>
                                            <a href="{{ route('volunteer_assignment_generate_certificate', ['id' => $volunteer->id, 'assignment_id' => $item->id]) }}" class="btn btn-outline-primary" title="Send Certificate"><i
                                                    class="bx bx-envelope"></i></a>
                                            <a href="javascript:void();"
                                                onclick="if(confirm('Are you sure delete this item?')) { event.preventDefault(); document.getElementById('delete-item-{{ $item->id }}').submit(); }"
                                                class="btn btn-danger"><i class='bx bx-trash'></i></a>

                                            <form id="delete-item-{{ $item->id }}"
                                                action="{{ route('volunteer_assignment_delete', ['id' => $volunteer->id, 'assignment_id' => $item->id]) }}" method="POST"
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
