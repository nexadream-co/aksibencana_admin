@extends('layouts.master')
@section('title')
    Edit Education
@endsection
@section('page-title')
    Edit Education
@endsection

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mt-4 mt-xl-0">
                                <form method="post" action="{{ route('education_update', $education->id) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('put')

                                    <div class="mb-3">
                                        <label class="form-label" for="title">Title</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            name="title" placeholder="Title" id="title"
                                            value="{{ old('title') ?? @$education->title }}">
                                        @error('title')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="description">Description</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="description"
                                            rows="10" placeholder="Description">{{ old('description') ?? @$education->contents }}</textarea>
                                        @error('description')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="banner">Image</label>

                                        @if (@$education->banner)
                                            <div class="my-2">
                                                <img src="{{ url('storage') }}/{{ $education->banner }}" class="rounded"
                                                    width="70" height="70" style="object-fit: cover">
                                            </div>
                                        @endif

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
