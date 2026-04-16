@extends('layouts.auth', ['title' => __('messages.sign_in')])

@section('content')
    <div class="d-flex flex-column h-100 p-3">
        <div class="d-flex flex-column flex-grow-1">
            <div class="row h-100">
                <div class="col-xxl-7">
                    <div class="row justify-content-center h-100">
                        <div class="col-lg-6 py-lg-5">
                            <div class="d-flex flex-column h-100 justify-content-center">
                                <div class="auth-logo mb-4">
                                    <a href="{{ url('/') }}" class="logo-dark">
                                        <img src="/images/logo-dark.png" height="24" alt="logo dark">
                                    </a>

                                    <a href="{{ url('/') }}" class="logo-light">
                                        <img src="/images/logo-light.png" height="24" alt="logo light">
                                    </a>
                                </div>

                                <h2 class="fw-bold fs-24">{{ __('messages.sign_in') }}</h2>

                                <p class="text-muted mt-1 mb-4">Administration Secure Gateway. Please enter your credentials to access the management panel.</p>

                                <div class="mb-5">
                                    <form method="POST" action="{{ route('admin.login') }}" class="authentication-form">
                                        @csrf
                                        
                                        @if ($errors->any())
                                            <div class="alert alert-danger border-0 bg-danger-subtle text-danger fs-13" role="alert">
                                                <ul class="mb-0">
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <div class="mb-3">
                                            <label class="form-label font-weight-bold" for="email">{{ __('messages.email') }}</label>
                                            <input type="email" id="email" name="email"
                                                   class="form-control" placeholder="admin@example.com"
                                                   value="{{ old('email') }}" required autofocus>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <a href="{{ route('password.request') }}"
                                               class="float-end text-muted text-unline-dashed ms-1 small">Forgot password?</a>
                                            <label class="form-label" for="password">{{ __('messages.password') }}</label>
                                            <input type="password" id="password" class="form-control"
                                                   placeholder="Enter your password" name="password" required>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="form-check">
                                                <input type="checkbox" name="remember" class="form-check-input" id="checkbox-signin">
                                                <label class="form-check-label" for="checkbox-signin">Keep me logged in</label>
                                            </div>
                                        </div>

                                        <div class="mb-0 text-center d-grid">
                                            <button class="btn btn-soft-warning fw-bold py-2 shadow-sm" type="submit">
                                                <iconify-icon icon="solar:lock-password-bold-duotone" class="align-middle fs-18 me-1"></iconify-icon>
                                                {{ __('messages.sign_in') }}
                                            </button>
                                        </div>
                                    </form>

                                    <div class="mt-4 text-center">
                                        <p class="text-muted small">© <script>document.write(new Date().getFullYear())</script> Larkon Admin Panel. Secured by Advanced Guard.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-5 d-none d-xxl-flex">
                    <div class="card h-100 mb-0 overflow-hidden border-0 shadow-lg">
                        <div class="d-flex flex-column h-100 position-relative">
                            <img src="/images/small/img-10.jpg" alt="Admin Auth" class="w-100 h-100 object-fit-cover">
                            <div class="position-absolute bottom-0 start-0 p-4 bg-dark bg-opacity-50 text-white w-100">
                                <h4 class="text-white">Professional Management</h4>
                                <p class="mb-0 opacity-75">Secure, metadata-driven architecture for modern enterprises.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
