@extends('layouts.master')
@section('title')
    Donation Histories
@endsection
@section('css')
    <!-- gridjs css -->
    <link rel="stylesheet" href="{{ URL::asset('vendors/libs/gridjs/theme/mermaid.min.css') }}">
@endsection
@section('page-title')
    Donation Histories
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="d-flex mb-3">
                                <div class="flex-grow-1">
                                    <h5 class="font-size-16">Title</h5>
                                    <p class="mb-0">{{ @$donation->title }}</p>
                                </div>
                            </div>

                            <div class="d-flex mb-3">
                                <div class="flex-grow-1">
                                    <h5 class="font-size-16">Status</h5>
                                    <p class="mb-0">{{ @$donation->status }}</p>
                                </div>
                            </div>

                            <div class="d-flex mb-3">
                                <div class="flex-grow-1">
                                    <h5 class="font-size-16">Start Date</h5>
                                    <p class="mb-0">{{ @$donation->start_date }}</p>
                                </div>
                            </div>

                            <div class="d-flex mb-3">
                                <div class="flex-grow-1">
                                    <h5 class="font-size-16">End Date</h5>
                                    <p class="mb-0">{{ @$donation->end_date }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="d-flex mb-3">
                                <div class="flex-grow-1">
                                    <h5 class="font-size-16">Total</h5>
                                    <p class="mb-0">{{ number_format(@$donation->totalDonation ?? 0) }}</p>
                                </div>
                            </div>

                            <div class="d-flex mb-3">
                                <div class="flex-grow-1">
                                    <h5 class="font-size-16">Target</h5>
                                    <p class="mb-0">{{ number_format(@$donation->target ?? 0) }}</p>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="flex-grow-1">
                                    <h5 class="font-size-16">Fundraiser</h5>
                                    <p class="mb-0">{{ @$donation->fundraiser->name }}</p>
                                </div>
                            </div>
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

                    <div id="table-list-gridjs" style="min-height: 10vh"></div>

                    <div class="table-responsive d-none">
                        <table id="table-data" class="table table-nowrap table-light">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">User</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Nominal</th>
                                    <th scope="col">Date</th>
                                    {{-- <th scope="col">Action</th> --}}
                                </tr>

                            </thead>
                            <tbody>
                                @foreach ($histories as $item)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ @$item->user->name }}
                                        </td>
                                        <td>
                                            {{ @$item->status }}
                                        </td>
                                        <td>
                                            {{ number_format($item->nominal) }}
                                        </td>
                                        <td>
                                            {{ $item->date }}
                                        </td>
                                        {{-- <td>
                                            <a href="{{ route('volunteer_assignment_edit', ['id' => $donation->id, 'assignment_id' => $item->id]) }}"
                                                class="btn btn-primary"><i class="bx bx-pencil"></i></a>
                                            <a href="javascript:void();"
                                                onclick="if(confirm('Are you sure delete this item?')) { event.preventDefault(); document.getElementById('delete-item-{{ $item->id }}').submit(); }"
                                                class="btn btn-danger"><i class='bx bx-trash'></i></a>

                                            <form id="delete-item-{{ $item->id }}"
                                                action="{{ route('volunteer_assignment_delete', ['id' => $donation->id, 'assignment_id' => $item->id]) }}"
                                                method="POST" style="display: none;">
                                                @method('delete')
                                                @csrf
                                            </form>
                                        </td> --}}
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
