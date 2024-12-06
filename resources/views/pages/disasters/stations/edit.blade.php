@extends('layouts.master')
@section('title')
    Edit Disaster Station
@endsection
@section('page-title')
    Edit Disaster Station
@endsection

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mt-4 mt-xl-0">
                                <form method="post" action="{{ route('disaster_station_update', ['id' => $disaster->id, 'station_id' => $station->id]) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method("put")

                                    <div class="mb-3">
                                        <label class="form-label" for="disaster">Disaster</label>
                                        <input type="text"
                                            class="form-control @error('disaster') is-invalid @enderror"
                                            name="disaster" placeholder="Disaster" id="disaster" value="{{@$disaster->title}}"
                                            value="{{ old('disaster') }}" readonly>
                                        @error('disaster')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="name">Name</label>
                                        <input type="text"
                                            class="form-control @error('name') is-invalid @enderror"
                                            name="name" placeholder="Name" id="name"
                                            value="{{ old('name') ?? @$station->name }}">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label" for="latitude">Latitude</label>
                                        <input type="text"
                                            class="form-control @error('latitude') is-invalid @enderror"
                                            name="latitude" placeholder="Latitude" id="latitude"
                                            value="{{ old('latitude') ?? @$station->latitude }}">
                                        @error('latitude')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label" for="longitude">Longitude</label>
                                        <input type="text"
                                            class="form-control @error('longitude') is-invalid @enderror"
                                            name="longitude" placeholder="Longitude" id="longitude"
                                            value="{{ old('longitude') ?? @$station->longitude }}">
                                        @error('longitude')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label" for="reconstruction">Category</label>
                                        <select name="category" id="reconstruction" class="form-control @error('category') is-invalid @enderror">
                                            <option value="">Choose Category</option>
                                            <option value="reconstruction" @if((old('category') ?? @$station->category) == 'reconstruction') selected @endif>Reconstruction</option>
                                            <option value="emergency" @if((old('category') ?? @$station->category) == 'emergency') selected @endif>Emergency</option>
                                        </select>
                                        @error('category')
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
