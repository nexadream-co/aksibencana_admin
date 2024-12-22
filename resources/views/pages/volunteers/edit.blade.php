@extends('layouts.master')
@section('title')
    Update Volunteer
@endsection
@section('page-title')
    Update Volunteer
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
                                <form method="post" action="{{ route('volunteer_update', $volunteer->id) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('put')

                                    <div class="mb-3">
                                        <label for="basicpill-firstname-input" class="form-label"><span
                                                data-key="t-choose-user">Choose User</span><span
                                                class="text-danger ms-1">*</span></label>
                                        <select class="form-control @error('user_id') is-invalid @enderror" name="user_id"
                                            name="choices-single-default" id="user-text-input" placeholder="Search Location"
                                            readonly>
                                            <option value="" data-key="t-search-user">Search User
                                            </option>
                                            @if (@$volunteer->user->id)
                                                <option value="{{ @$volunteer->user->id }}" selected>
                                                    {{ @$volunteer->user->name }}
                                                </option>
                                            @endif
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
                                            @if (@$volunteer->district->id)
                                                <option value="{{ @$volunteer->district->id }}" selected>
                                                    {{ @$volunteer->district->name }},
                                                    {{ @$volunteer->district->city->name }},
                                                    {{ @$volunteer->district->city->province->name }}
                                                </option>
                                            @endif
                                        </select>
                                        @error('district_id')
                                            <div class="text-danger">The location field is required.</div>
                                        @enderror
                                    </div>


                                    <div class="mb-3">
                                        <label class="form-label" for="ktp">KTP</label>

                                        <div class="my-2">
                                            <img src="{{ url('/') }}/storage/{{ @$volunteer->ktp }}" class="rounded"
                                                width="70" height="70" style="object-fit: cover">
                                        </div>

                                        <input type="file" class="form-control @error('ktp') is-invalid @enderror"
                                            name="ktp" placeholder="Upload KTP" id="ktp">
                                        @error('ktp')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="health_status">Health Status</label>
                                        <input type="text"
                                            class="form-control @error('health_status') is-invalid @enderror"
                                            name="health_status" placeholder="Health Status" id="health_status"
                                            value="{{ old('health_status') ?? @$volunteer->health_status }}">
                                        @error('health_status')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="whatsapp_number">Whatsapp Number</label>
                                        <input type="number"
                                            class="form-control @error('whatsapp_number') is-invalid @enderror"
                                            name="whatsapp_number" placeholder="Whatsapp number" id="whatsapp_number"
                                            value="{{ old('whatsapp_number') ?? @$volunteer->whatsapp_number }}">
                                        @error('whatsapp_number')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    {{-- <div class="mb-3">
                                        <label class="form-label" for="date_of_birth">Date of birth</label>
                                        <input type="date"
                                            class="form-control @error('date_of_birth') is-invalid @enderror"
                                            name="date_of_birth" placeholder="Date of birth" id="date_of_birth"
                                            value="{{ old('date_of_birth') ?? @$volunteer->date_of_birth }}">
                                        @error('date_of_birth')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div> --}}

                                    <div class="mb-3">
                                        <label class="form-label" for="address">Address</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" name="address" id="address" rows="3"
                                            placeholder="Addres">{{ old('address') ?? @$volunteer->address }}</textarea>
                                        @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Category</label>
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="categories[]"
                                                id="category1" value="emergency"
                                                {{ in_array('emergency', old('categories') ?? (json_decode(@$volunteer->categories) ?? [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="category1">
                                                Emergency
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="categories[]"
                                                id="category2" value="reconstruction"
                                                {{ in_array('reconstruction', old('categories') ?? (json_decode(@$volunteer->categories) ?? [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="category2">
                                                Reconstruction
                                            </label>
                                        </div>
                                        @error('categories')
                                            <div class="text-danger">The categories field is required.</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Abilities</label>
                                        @foreach ($abilities as $item)
                                            <div class="form-check mb-3">
                                                <input class="form-check-input" type="checkbox" name="abilities[]"
                                                    value="{{ $item->id }}" id="ability{{ $loop->iteration }}"
                                                    {{ in_array($item->id, old('abilities') ?? (json_decode(@$volunteer->abilities->pluck('id')) ?? [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="ability{{ $loop->iteration }}">
                                                    {{ $item->name }}
                                                </label>
                                            </div>
                                        @endforeach
                                        @error('abilities')
                                            <div class="text-danger">The abilities field is required.</div>
                                        @enderror
                                    </div>

                                    <hr>

                                    <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                        <input type="checkbox" class="form-check-input" name="availability_status"
                                            id="availability_status" @if ((old('availability_status') ?? @$volunteer->availability_status) == 'active') checked @endif>
                                        <label class="form-check-label" for="availability_status">Availability
                                            Status</label>
                                    </div>

                                    {{-- <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                        <input type="checkbox" class="form-check-input" id="status_active"
                                            name="status" @if ((old('status') ?? @$volunteer->status) == 'active') checked @endif>
                                        <label class="form-check-label" for="status_active">Active</label>
                                    </div>
                                     --}}

                                    <div class="mb-3">
                                        <label class="form-label" for="status_active">Status</label>
                                        <select name="status" id="status_active"
                                            class="form-control @error('status') is-invalid @enderror">
                                            <option value="">Choose Status</option>
                                            <option value="active" @if ((old('status') ?? @$volunteer->status) == 'active') selected @endif>Active
                                            </option>
                                            <option value="requested" @if ((old('status') ?? @$volunteer->status) == 'requested') selected @endif>
                                                Requested</option>
                                            <option value="rejected" @if ((old('status') ?? @$volunteer->status) == 'rejected') selected @endif>
                                                Rejected</option>
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
        const userElement = document.getElementById('user-text-input');
        const districtElement = document.getElementById('district-text-input');
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
