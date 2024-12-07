@extends('layouts.master')
@section('title')
    Create Logistics
@endsection
@section('page-title')
    Create Logistics
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
                                <form method="post" action="{{ route('logistic_store') }}" enctype="multipart/form-data">
                                    @csrf

                                    <h4>Logistics Form</h4>
                                    <div class="mb-3">
                                        <label for="basicpill-firstname-input" class="form-label"><span
                                                data-key="t-choose-disaster">Choose disaster</span><span
                                                class="text-danger ms-1">*</span></label>
                                        <select class="form-control @error('disaster_id') is-invalid @enderror"
                                            name="disaster_id" name="choices-single-default" id="disaster-text-input"
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
                                        <label for="basicpill-firstname-input" class="form-label"><span
                                                data-key="t-location">Location</span><span
                                                class="text-danger ms-1">*</span></label>
                                        <select class="form-control @error('district_id') is-invalid @enderror"
                                            name="district_id" name="choices-single-default" id="district-text-input"
                                            placeholder="Search Location">
                                            <option value="" data-key="t-search-location">Search Location
                                            </option>
                                        </select>
                                        @error('district_id')
                                            <div class="text-danger">The location field is required.</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="goods_name">Goods Name</label>
                                        <input type="text" class="form-control @error('goods_name') is-invalid @enderror"
                                            name="goods_name" placeholder="Goods Name" id="goods_name"
                                            value="{{ old('goods_name') }}">
                                        @error('goods_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="goods_type">Goods Type</label>
                                        <input type="text" class="form-control @error('goods_type') is-invalid @enderror"
                                            name="goods_type" placeholder="Goods Type" id="goods_type"
                                            value="{{ old('goods_type') }}">
                                        @error('goods_type')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="date">Date</label>
                                        <input type="date" class="form-control @error('date') is-invalid @enderror"
                                            name="date" placeholder="Date" id="date" value="{{ old('date') }}">
                                        @error('date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="branch_office_id">Branch Office</label>
                                        <select name="branch_office_id" id="branch_office_id"
                                            class="form-control @error('branch_office_id') is-invalid @enderror">
                                            <option value="">Choose Branch Office</option>
                                            @foreach ($branch_offices as $item)
                                                <option value="{{ $item->id }}"
                                                    @if (old('branch_office_id') == $item->id) selected @endif>{{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('branch_office_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <hr>

                                    <h4>Expeditions</h4>

                                    <div class="mb-3">
                                        <label class="form-label" for="expedition_name">Expedition Name</label>
                                        <select name="expedition_name" id="expedition_name"
                                            class="form-control @error('expedition_name') is-invalid @enderror">
                                            <option value="">Choose Expedition</option>
                                            <option value="jnt" @if (old('expedition_name') == 'jnt') selected @endif>JNT
                                            </option>
                                            <option value="jne" @if (old('expedition_name') == 'jne') selected @endif>JNE
                                            </option>
                                        </select>
                                        @error('expedition_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="telp">Telp Number</label>
                                        <input type="number" class="form-control @error('telp') is-invalid @enderror"
                                            name="telp" placeholder="Telp Number" id="telp"
                                            value="{{ old('telp') }}">
                                        @error('telp')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="weight">Weight</label>
                                        <input type="number" class="form-control @error('weight') is-invalid @enderror"
                                            name="weight" placeholder="Weight" id="weight"
                                            value="{{ old('weight') }}">
                                        @error('weight')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="address">Address</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" name="address" id="address" rows="3"
                                            placeholder="Addres">{{ old('address') }}</textarea>
                                        @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="receipt_code">Receipt Code</label>
                                        <input type="text"
                                            class="form-control @error('receipt_code') is-invalid @enderror"
                                            name="receipt_code" placeholder="Receipt Code" id="receipt_code"
                                            value="{{ old('receipt_code') }}">
                                        @error('receipt_code')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <hr>

                                    <h4>Status</h4>

                                    <div class="mb-3">
                                        <label class="form-label" for="status_active">Status</label>
                                        <select name="status" id="status_active"
                                            class="form-control @error('status') is-invalid @enderror">
                                            <option value="">Choose Status</option>
                                            <option value="active" @if (old('status') == 'active') selected @endif>
                                                Active</option>
                                            <option value="requested" @if (old('status') == 'requested') selected @endif>
                                                Requested</option>
                                            <option value="rejected" @if (old('status') == 'rejected') selected @endif>
                                                Rejected</option>
                                            <option value="inactive" @if (old('status') == 'inactive') selected @endif>
                                                Inactive</option>
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
        const userElement = document.getElementById('user-text-input');
        const districtElement = document.getElementById('district-text-input');
        initChoices(disasterElement, '/disaster/search');
        initChoices(userElement, '/user/search');
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
