@extends('layouts.master')
@section('title')
    Create Disaster
@endsection
@section('page-title')
    Create Disaster
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
                                <form method="post" action="{{ route('disaster_update', $disaster->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('put')

                                    <div class="mb-3">
                                        <label class="form-label" for="title">Title</label>
                                        <input type="text"
                                            class="form-control @error('title') is-invalid @enderror"
                                            name="title" placeholder="Title" id="title"
                                            value="{{ old('title') ?? @$disaster->title }}">
                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="description">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" rows="3"
                                            placeholder="Description">{{ old('description') ?? @$disaster->description }}</textarea>
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
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
                                            @if (@$disaster->district->id)
                                                <option value="{{ @$disaster->district->id }}" selected>
                                                    {{ @$disaster->district->name }},
                                                    {{ @$disaster->district->city->name }},
                                                    {{ @$disaster->district->city->province->name }}
                                                </option>
                                            @endif
                                        </select>
                                        @error('district_id')
                                            <div class="text-danger">The location field is required.</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="address">Address</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror" name="address" id="address" rows="3"
                                            placeholder="Addres">{{ old('address') ?? @$disaster->address }}</textarea>
                                        @error('address')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>


                                    <div class="mb-3">
                                        <label class="form-label" for="images">Image</label>

                                        @if (count(@json_decode($disaster->images) ?? []))
                                            <div class="my-2">
                                                <img src="{{ url('/') }}/storage/{{ @json_decode($disaster->images)[0] }}" class="rounded"
                                                    width="70" height="70" style="object-fit: cover">
                                            </div>
                                        @endif

                                        <input type="file" class="form-control @error('images') is-invalid @enderror"
                                            name="images" placeholder="Upload images" id="images">
                                        @error('images')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="date">Date</label>
                                        <input type="date"
                                            class="form-control @error('date') is-invalid @enderror"
                                            name="date" placeholder="Date" id="date"
                                            value="{{ old('date') ?? @$disaster->date }}">
                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="category_id">Category</label>
                                        <select name="category_id" id="category_id" class="form-control">
                                            <option value="">Choose Category</option>
                                            @foreach ($categories as $item)
                                                <option value="{{$item->id}}" @if((old('category_id')  ?? @$disaster->disaster_category_id) == $item->id) selected @endif>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    {{-- <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                        <input type="checkbox" class="form-check-input" id="status_active" value="active"
                                            name="status" @if ((old('status')  ?? @$disaster->status) == 'active') checked @endif>
                                        <label class="form-check-label" for="status_active">Active</label>
                                    </div> --}}
                                    
                                    <div class="mb-3">
                                        <label class="form-label" for="status_active">Status</label>
                                        <select name="status" id="status_active" class="form-control @error('status') is-invalid @enderror">
                                            <option value="">Choose Status</option>
                                            <option value="active" @if((old('status') ?? @$disaster->status) == 'active') selected @endif>Active</option>
                                            <option value="requested" @if((old('status') ?? @$disaster->status) == 'requested') selected @endif>Requested</option>
                                            <option value="rejected" @if((old('status') ?? @$disaster->status) == 'rejected') selected @endif>Rejected</option>
                                            <option value="inactive" @if((old('status') ?? @$disaster->status) == 'inactive') selected @endif>Inactive</option>
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
