@extends('layouts.master')
@section('title')
    Create Education
@endsection
@section('page-title')
    Create Education
@endsection

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mt-4 mt-xl-0">
                                <form method="post" action="{{ route('education_store') }}" enctype="multipart/form-data">
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label" for="title">Title</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            name="title" placeholder="Title" id="title" value="{{ old('title') }}">
                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="description">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description"
                                            rows="10" placeholder="Description">{{ old('description') }}</textarea>
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="banner">Image</label>
                                        <input type="file" class="form-control @error('banner') is-invalid @enderror"
                                            name="banner" placeholder="Upload banner" id="banner">
                                        @error('banner')
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
