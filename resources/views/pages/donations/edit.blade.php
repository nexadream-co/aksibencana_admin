@extends('layouts.master')
@section('title')
    Edit Donation
@endsection
@section('page-title')
    Edit Donation
@endsection

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mt-4 mt-xl-0">
                                <form method="post" action="{{ route('donation_update', $donation->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('put')

                                    <div class="mb-3">
                                        <label class="form-label" for="title">Title</label>
                                        <input type="text"
                                            class="form-control @error('title') is-invalid @enderror"
                                            name="title" placeholder="Title" id="title"
                                            value="{{ old('title') ?? @$donation->title }}">
                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="image">Image</label>

                                        @if (count(@json_decode($donation->images) ?? []))
                                            <div class="my-2">
                                                <img src="{{ url('/') }}/storage/{{ @json_decode($donation->images)[0] }}" class="rounded"
                                                    width="70" height="70" style="object-fit: cover">
                                            </div>
                                        @endif

                                        <input type="file" class="form-control @error('image') is-invalid @enderror"
                                            name="image" placeholder="Upload image" id="image">
                                        @error('image')
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
                                                <option value="{{$item->id}}" @if((old('category_id')  ?? @$donation->donation_category_id) == $item->id) selected @endif>{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="description">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description" rows="3"
                                            placeholder="Addres">{{ old('description') ?? @$donation->description }}</textarea>
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-check form-switch form-switch-md mb-3" dir="ltr">
                                        <input type="checkbox" class="form-check-input" id="status_active" value="active"
                                            name="status" @if ((old('status')  ?? @$donation->status) == 'active') checked @endif>
                                        <label class="form-check-label" for="status_active">Active</label>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="start_date">Start Date</label>
                                        <input type="date"
                                            class="form-control @error('start_date') is-invalid @enderror"
                                            name="start_date" placeholder="Start Date" id="start_date"
                                            value="{{ old('start_date') ?? @$donation->start_date }}">
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
                                            name="end_date" placeholder="Start Date" id="end_date"
                                            value="{{ old('end_date') ?? @$donation->end_date }}">
                                        @error('end_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    {{-- <div class="mb-3">
                                        <label class="form-label" for="total">Total</label>
                                        <input type="number"
                                            class="form-control @error('total') is-invalid @enderror"
                                            name="total" placeholder="Total" id="total"
                                            value="{{ old('total') ?? @$donation->total }}">
                                        @error('total')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div> --}}

                                    <div class="mb-3">
                                        <label class="form-label" for="target">Target</label>
                                        <input type="number"
                                            class="form-control @error('target') is-invalid @enderror"
                                            name="target" placeholder="Target" id="target"
                                            value="{{ old('target') ?? @$donation->target }}">
                                        @error('target')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <hr>

                                    <div class="mb-3">
                                        <label class="form-label" for="fundraiser_name">Fundraiser Name</label>
                                        <input type="text"
                                            class="form-control @error('fundraiser_name') is-invalid @enderror"
                                            name="fundraiser_name" placeholder="Fundraiser Name" id="fundraiser_name"
                                            value="{{ old('fundraiser_name') ?? @$donation->fundraiser->name }}">
                                        @error('fundraiser_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>


                                    <div class="mb-3">
                                        <label class="form-label" for="fundraiser_photo">Photo</label>

                                        @if (@$donation->fundraiser->photo)
                                            <div class="my-2">
                                                <img src="{{ url('/') }}/storage/{{ @$donation->fundraiser->photo }}" class="rounded"
                                                    width="70" height="70" style="object-fit: cover">
                                            </div>
                                        @endif

                                        <input type="file" class="form-control @error('fundraiser_photo') is-invalid @enderror"
                                            name="fundraiser_photo" placeholder="Fundraiser photo" id="fundraiser_photo">
                                        @error('fundraiser_photo')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="fundraiser_description">Fundraiser Description</label>
                                        <textarea class="form-control @error('fundraiser_description') is-invalid @enderror" name="fundraiser_description" id="fundraiser_description" rows="3"
                                            placeholder="Fundraiser Description">{{ old('fundraiser_description') ?? @$donation->fundraiser->description }}</textarea>
                                        @error('fundraiser_description')
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
    <!-- App js -->
    <script src="{{ url('/') }}/vendors/js/app.js"></script>
@endsection
