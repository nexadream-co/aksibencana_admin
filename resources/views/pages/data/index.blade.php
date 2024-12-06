@extends('layouts.master')
@section('title')
    Data
@endsection
@section('css')
    <!-- gridjs css -->
    <link rel="stylesheet" href="{{ URL::asset('vendors/libs/gridjs/theme/mermaid.min.css') }}">
@endsection
@section('page-title')
    Data didapatkan dari website <a href="https://data.bmkg.go.id/DataMKG/TEWS/gempaterkini.json">BMKG</a>
@endsection

@section('content')
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
                                    <th scope="col">Wilayah</th>
                                    <th scope="col">Potensi</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Time</th>
                                    <th scope="col">Coordinates</th>
                                    <th scope="col">Lintang</th>
                                    <th scope="col">Bujur</th>
                                    <th scope="col">Magnitude</th>
                                    <th scope="col">Kedalaman</th>
                                </tr>

                            </thead>
                            <tbody>
                                @foreach ($earthquakes as $item)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ @$item['Wilayah'] }}
                                        </td>
                                        <td>
                                            {{ @$item['Potensi'] }}
                                        </td>
                                        <td>
                                            {{ @$item['Tanggal'] }}
                                        </td>
                                        <td>
                                            {{ @$item['Jam'] }}
                                        </td>
                                        <td>
                                            {{ @$item['Coordinates'] }}
                                        </td>
                                        <td>
                                            {{ @$item['Lintang'] }}
                                        </td>
                                        <td>
                                            {{ @$item['Bujur'] }}
                                        </td>
                                        <td>
                                            {{ @$item['Magnitude'] }}
                                        </td>
                                        <td>
                                            {{ @$item['Kedalaman'] }}
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
