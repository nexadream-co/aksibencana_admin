@extends('layouts.master-without-nav')
@section('title')
    Login
@endsection
@section('page-title')
    Login
@endsection
@section('body')

    <body>
    @endsection
    @section('content')
        <div class="authentication-bg min-vh-100">
            <div class="bg-overlay bg-light"></div>
            <div class="container">
                <div class="d-flex flex-column min-vh-100 px-3 pt-4">
                    <div class="row justify-content-center my-auto">
                        <div class="col-md-8 col-lg-6 col-xl-5">

                            <div class="mb-4 pb-2">
                                <a href="index" class="d-block auth-logo">
                                    <img src="{{ URL::asset('vendors/images/logo-dark.png') }}" alt="" height="30"
                                        class="auth-logo-dark me-start">
                                    <img src="{{ URL::asset('vendors/images/logo-light.png') }}" alt=""
                                        height="30" class="auth-logo-light me-start">
                                </a>
                            </div>

                            <div class="card">
                                <div class="card-body p-4">
                                    <div class="text-center mt-2">
                                        <h5>Welcome Back !</h5>
                                        <p class="text-muted">Sign in to continue to webadmin.</p>
                                    </div>
                                    <div class="p-2 mt-4">
                                        <form action="index">

                                            <div class="mb-3">
                                                <label class="form-label" for="username">Username</label>
                                                <div class="position-relative input-custom-icon">
                                                    <input type="text" class="form-control" id="username"
                                                        placeholder="Enter username">
                                                    <span class="bx bx-user"></span>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <div class="float-end">
                                                    <a href="auth-recoverpw"
                                                        class="text-muted text-decoration-underline">Forgot password?</a>
                                                </div>
                                                <label class="form-label" for="password-input">Password</label>
                                                <div class="position-relative auth-pass-inputgroup input-custom-icon">
                                                    <span class="bx bx-lock-alt"></span>
                                                    <input type="password" class="form-control" id="password-input"
                                                        placeholder="Enter password">
                                                    <button type="button"
                                                        class="btn btn-link position-absolute h-100 end-0 top-0"
                                                        id="password-addon">
                                                        <i class="mdi mdi-eye-outline font-size-18 text-muted"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="form-check py-1">
                                                <input type="checkbox" class="form-check-input" id="auth-remember-check">
                                                <label class="form-check-label" for="auth-remember-check">Remember
                                                    me</label>
                                            </div>

                                            <div class="mt-3">
                                                <button class="btn btn-primary w-100 waves-effect waves-light"
                                                    type="submit">Log In</button>
                                            </div>

                                            <div class="mt-4 text-center">
                                                <div class="signin-other-title">
                                                    <h5 class="font-size-14 mb-3 mt-2 title"> Sign in with </h5>
                                                </div>

                                                <ul class="list-inline mt-2">
                                                    <li class="list-inline-item">
                                                        <a href="javascript:void()"
                                                            class="social-list-item bg-primary text-white border-primary">
                                                            <i class="bx bxl-facebook"></i>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <a href="javascript:void()"
                                                            class="social-list-item bg-info text-white border-info">
                                                            <i class="bx bxl-linkedin"></i>
                                                        </a>
                                                    </li>
                                                    <li class="list-inline-item">
                                                        <a href="javascript:void()"
                                                            class="social-list-item bg-danger text-white border-danger">
                                                            <i class="bx bxl-google"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>

                                            <div class="mt-4 text-center">
                                                <p class="mb-0">Don't have an account ? <a href="auth-register"
                                                        class="fw-medium text-primary"> Signup now </a> </p>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>

                        </div><!-- end col -->
                    </div><!-- end row -->

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-center p-4">
                                <p>©
                                    <script>
                                        document.write(new Date().getFullYear())
                                    </script> webadmin. Crafted with <i
                                        class="mdi mdi-heart text-danger"></i> by Themesdesign
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div><!-- end container -->
        </div>
        <!-- end authentication section -->
    @endsection
    @section('scripts')
        <script src="{{ URL::asset('vendors/js/pages/pass-addon.init.js') }}"></script>
        <!-- App js -->
        <script src="{{ URL::asset('vendors/js/app.js') }}"></script>
    @endsection
