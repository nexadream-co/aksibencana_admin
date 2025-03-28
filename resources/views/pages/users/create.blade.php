@extends('layouts.master')
@section('title')
    Create User
@endsection
@section('page-title')
    Create User
@endsection

@section('content')
    <div class="row d-flex justify-content-center">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="mt-4 mt-xl-0">
                                <form method="post" action="{{ route('user_store') }}" enctype="multipart/form-data">
                                    @csrf

                                    <h4>Create User Form</h4>

                                    <div class="mb-3">
                                        <label class="form-label" for="role_id">Role</label>
                                        <select name="role_id" id="role_id"
                                            class="form-control @error('role_id') is-invalid @enderror">
                                            <option value="">Choose Role</option>
                                            @foreach ($roles as $item)
                                                <option value="{{ $item->id }}"
                                                    @if (old('role_id') == $item->id) selected @endif>{{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('role_id')
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

                                    <div class="mb-3">
                                        <label class="form-label" for="email">Email</label>
                                        <input type="text" class="form-control @error('email') is-invalid @enderror"
                                            name="email" placeholder="Email" id="email" value="{{ old('email') }}">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="name">Name</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            name="name" placeholder="Name" id="name" value="{{ old('name') }}">
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="password">Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            name="password" placeholder="Password" id="password" value="">
                                        @error('password')
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
