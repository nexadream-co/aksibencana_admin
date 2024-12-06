@extends('layouts.master')
@section('title')
    Add Logistic
@endsection
@section('page-title')
    Add Logistic
@endsection

@section('css')
    <!-- choices css -->
    <link href="{{ url('vendors/libs/choices.js/public/assets/styles/choices.min.css') }}" rel="stylesheet" type="text/css" />

    <link href="{{ url('vendors/css/choices/app-choices.css') }}" id="app-style" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mt-4 mt-xl-0">
                                <form method="post" action="{{ route('delivery_logistic_store', [$delivery->id]) }}"
                                    enctype="multipart/form-data">
                                    @csrf

                                    <h4>Logistic Form</h4>

                                    <div class="mb-3">
                                        <label for="basicpill-firstname-input" class="form-label"><span
                                                data-key="t-choose-logistic">Choose logistic</span><span
                                                class="text-danger ms-1">*</span></label>
                                        <select class="form-control @error('logistic_id') is-invalid @enderror"
                                            name="logistic_id" name="choices-single-default" id="logistic-text-input"
                                            placeholder="Search Logistic">
                                            <option value="" data-key="t-search-logistic">Search logistic
                                            </option>
                                        </select>
                                        @error('logistic_id')
                                            <div class="text-danger">The logistic field is required.</div>
                                        @enderror
                                    </div>

                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-primary w-md">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Form Layout -->
@endsection
@section('scripts')
    <!-- choices js -->
    <script src="{{ url('vendors/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>

    <!-- App js -->
    <script src="{{ url('/') }}/vendors/js/app.js"></script>

    <script>
        const logisticElement = document.getElementById('logistic-text-input');
        initChoices(logisticElement, '/logistic/search');

        function initChoices(element, url) {
            const choices = new Choices(element, {
                searchEnabled: true,
                searchChoices: false,
                placeholder: true,
                placeholderValue: 'Type to search',
                noResultsText: 'No results found',
            });

            let searchTimeout;

            element.addEventListener('search', function(event) {
                const searchTerm = event.detail.value;

                if (searchTimeout) clearTimeout(searchTimeout);

                searchTimeout = setTimeout(async () => {
                    try {
                        const response = await fetch(
                            `${url}?q=${searchTerm}`
                        );
                        const data = await response.json();

                        const items = data.map(item => ({
                            value: item.id,
                            label: item.value,
                        }));

                        choices.clearChoices();
                        choices.setChoices(items, 'value', 'label', true);
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    }
                }, 300);
            });
        }
    </script>
@endsection
