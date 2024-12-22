@extends('layouts.master')
@section('title')
    Edit Branch Office
@endsection
@section('page-title')
    Edit Branch Office
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
                                <form method="post" action="{{ route('branch_office_update', $branch_office->id) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('put')

                                    <h4>Branch Office Form</h4>

                                    <div class="mb-3">
                                        <label for="basicpill-firstname-input" class="form-label"><span
                                                data-key="t-location">Location</span><span
                                                class="text-danger ms-1">*</span></label>
                                        <select class="form-control @error('district_id') is-invalid @enderror"
                                            name="district_id" name="choices-single-default" id="district-text-input"
                                            placeholder="Search Location">
                                            <option value="" data-key="t-search-location">Search Location
                                            </option>
                                            @if (@$branch_office->district->id)
                                                <option value="{{ @$branch_office->district->id }}" selected>
                                                    {{ @$branch_office->district->name }},
                                                    {{ @$branch_office->district->city->name }},
                                                    {{ @$branch_office->district->city->province->name }}
                                                </option>
                                            @endif
                                        </select>
                                        @error('district_id')
                                            <div class="text-danger">The location field is required.</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="name">Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" placeholder="Name" id="name"
                                            value="{{ old('name') ?? @$branch_office->name }}">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="latitude">Latitude</label>
                                        <input type="text" class="form-control @error('latitude') is-invalid @enderror"
                                            name="latitude" placeholder="Latitude" id="latitude"
                                            value="{{ old('latitude') ?? @$branch_office->latitude }}">
                                        @error('latitude')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="longitude">Longitude</label>
                                        <input type="text" class="form-control @error('longitude') is-invalid @enderror"
                                            name="longitude" placeholder="Longitude" id="longitude"
                                            value="{{ old('longitude') ?? @$branch_office->longitude }}">
                                        @error('longitude')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="address">Address</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" name="address" id="address" rows="3"
                                            placeholder="Addres">{{ old('address') ?? @$branch_office->address }}</textarea>
                                        @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                        <input type="checkbox" class="form-check-input" id="status_active" name="status"
                                            @if ((old('status') ?? @$branch_office->status) == 'active') checked @endif>
                                        <label class="form-check-label" for="status_active">Active</label>
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
        const districtElement = document.getElementById('district-text-input');
        initChoices(districtElement, '/location/district/search');

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
