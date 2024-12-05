@extends('layouts.master')
@section('title')
    Create Assignment Volunteer
@endsection
@section('page-title')
    Create Assignment Volunteer
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
                                <form method="post" action="{{ route('volunteer_assignment_store', [$volunteer->id]) }}" enctype="multipart/form-data">
                                    @csrf

                                    <h4>Assignment Volunteer Form</h4>
                                    <div class="mb-3">
                                        <label for="basicpill-firstname-input" class="form-label"><span
                                                data-key="t-choose-disaster">Choose disaster</span><span
                                                class="text-danger ms-1">*</span></label>
                                        <select class="form-control @error('disaster_id') is-invalid @enderror" name="disaster_id"
                                            name="choices-single-default" id="disaster-text-input"
                                            placeholder="Search Disaster">
                                            <option value="" data-key="t-search-disaster">Search disaster
                                            </option>
                                        </select>
                                        @error('disaster_id')
                                            <div class="text-danger">The disaster field is required.</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="basicpill-firstname-input" class="form-label"><span
                                                data-key="t-choose-disaster">Choose disaster Station</span><span
                                                class="text-danger ms-1">*</span></label>
                                        <select class="form-control @error('disaster_station_id') is-invalid @enderror" name="disaster_station_id"
                                            name="choices-single-default" id="disaster-station-text-input"
                                            placeholder="Search Disaster">
                                            <option value="" data-key="t-search-disaster">Search disaster station
                                            </option>
                                        </select>
                                        @error('disaster_station_id')
                                            <div class="text-danger">The disaster station field is required.</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="basicpill-firstname-input" class="form-label"><span
                                                data-key="t-choose-user">Choose User</span><span
                                                class="text-danger ms-1">*</span></label>
                                        <select class="form-control @error('user_id') is-invalid @enderror" name="user_id"
                                            name="choices-single-default" id="user-text-input"
                                            placeholder="Search Location">
                                            <option value="" data-key="t-search-user">Search User
                                            </option>
                                        </select>
                                        @error('user_id')
                                            <div class="text-danger">The user field is required.</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="title">Title</label>
                                        <input type="text"
                                            class="form-control @error('title') is-invalid @enderror"
                                            name="title" placeholder="Title" id="title"
                                            value="{{ old('title') }}">
                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="start_date">Start Date</label>
                                        <input type="date"
                                            class="form-control @error('start_date') is-invalid @enderror"
                                            name="start_date" placeholder="Start Date" id="start_date"
                                            value="{{ old('start_date') }}">
                                        @error('start_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label" for="end_date">End Date</label>
                                        <input type="date"
                                            class="form-control @error('end_date') is-invalid @enderror"
                                            name="end_date" placeholder="End Date" id="end_date"
                                            value="{{ old('end_date') }}">
                                        @error('end_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label" for="status_active">Status</label>
                                        <select name="status" id="status_active" class="form-control @error('status') is-invalid @enderror">
                                            <option value="">Choose Status</option>
                                            <option value="active" @if(old('status') == 'active') selected @endif>Active</option>
                                            <option value="finished" @if(old('status') == 'finished') selected @endif>Finished</option>
                                            <option value="canceled" @if(old('status') == 'canceled') selected @endif>Canceled</option>
                                        </select>
                                        @error('status')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
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
        const disasterElement = document.getElementById('disaster-text-input');
        const disasterStationElement = document.getElementById('disaster-station-text-input');
        const userElement = document.getElementById('user-text-input');
        initChoices(disasterElement, '/disaster/search');
        initChoices(disasterStationElement, '/disaster/search/station');
        initChoices(userElement, '/user/search');

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
