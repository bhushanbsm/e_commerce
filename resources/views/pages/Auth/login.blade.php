@extends('layouts.auth')

@section('content')
<!-- Main content -->
<div class="main-content">
  <!-- Header -->
  <div class="header bg-gradient-primary py-7 py-lg-8 pt-lg-9">
    <div class="container">
      <div class="header-body text-center mb-7">
        <div class="row justify-content-center">
          <div class="col-xl-5 col-lg-6 col-md-8 px-5">
            <h1 class="text-white">Welcome!</h1>
            <p class="text-lead text-white">Use these awesome forms to login or create new account in your project for free.</p>
          </div>
        </div>
      </div>
    </div>
    <div class="separator separator-bottom separator-skew zindex-100">
      <svg x="0" y="0" viewBox="0 0 2560 100" preserveAspectRatio="none" version="1.1" xmlns="http://www.w3.org/2000/svg">
        <polygon class="fill-default" points="2560 0 2560 100 0 100"></polygon>
      </svg>
    </div>
  </div>
  <!-- Page content -->
  <div class="container mt--8 pb-5">
    <div class="row justify-content-center">
      <div class="col-lg-5 col-md-7">
        <div class="card bg-secondary border-0 mb-0">
          <div class="card-header bg-transparent pb-5">
            <div class="text-muted text-center mt-2 mb-3"><small>{{ __('Sign in with') }}</small></div>
            <div class="btn-wrapper text-center">
             <!--  <a href="#" class="btn btn-neutral btn-icon">
                <span class="btn-inner--icon"><img src="../assets/img/icons/common/github.svg"></span>
                <span class="btn-inner--text">Github</span>
              </a> -->
              <a href="{{ url('auth/google') }}" class="btn btn-neutral btn-icon">
                <span class="btn-inner--icon"><img src="../assets/img/icons/common/google.svg"></span>
                <span class="btn-inner--text">{{ __('Google') }}</span>
              </a>
            </div>
          </div>
          <div class="card-body px-lg-5 py-lg-5">
            <div class="text-center text-muted mb-4">
              <small>{{ __('Or sign in with credentials') }}</small>
            </div>
            <form role="form" name="loginForm" id="loginForm" action="#" method="post">
              @csrf
              <div class="form-group mb-3">
                <div class="input-group input-group-merge input-group-alternative">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                  </div>
                  <input class="form-control" id="email" placeholder="{{ __('Email') }}" name="email" type="email">
                </div>
              </div>
              <div class="form-group">
                <div class="input-group input-group-merge input-group-alternative">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                  </div>
                  <input class="form-control" id="password" placeholder="{{ __('Password') }}" name="password" type="password">
                </div>
              </div>
              <div class="text-center">
                <button type="submit" class="btn btn-primary my-4">{{ __('Sign in') }}</button>
              </div>
            </form>
          </div>
        </div>
        <div class="row mt-3">
          <div class="col-6">
            <a href="{{ url('/forgot-password') }}" class="text-light"><small>{{ __('Forgot password?') }}</small></a>
          </div>
          <div class="col-6 text-right">
            <a href="{{ url('/register') }}" class="text-light"><small>{{ __('Create new account') }}</small></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  $(function(){
    $('#loginForm').validate({
      debug: false,
      focusInvalid: false,
      ignore: [],
      errorElement: 'div',
      errorClass: 'invalid-feedback',
      rules: {
        "password": {
          required: true
        },
        "email": {
          required: true,
          email:true,
        },
      },
      highlight: function(element, errorClass) {
        $(element).closest('.form-group').addClass('has-error');
      },
      unhighlight: function(element) {
        $(element).closest('.form-group').removeClass('has-error');
      },
      errorPlacement: function(error, element) {
        if(element.parent('.input-group').length) {
          error.insertAfter(element.parent());
        } else {
          error.insertAfter(element);
        }
      },
      submitHandler: function (form) {
        $.LoadingOverlay("show");

        var formData = new FormData(form);
        $.ajax({
          url: '{{url("login")}}',
          type: 'POST',
          data: formData,
          contentType: "application/json",
          cache: false,
          contentType: false,
          processData: false,
          success: function (data) {
            $.LoadingOverlay("hide");
            $.toast({
              heading: 'Login',
              text: 'Logged in successfully.',
              bgColor: '#FFFF',
              textColor: 'white',
              position: 'top-right',
            });
            window.location.replace("{{url('dashboard')}}");
          },
          error:function(data) {
            $.LoadingOverlay("hide");
            
            if (data.status == 422) {
              $.toast({
                heading: 'Registration',
                text: data.responseJSON.msg,
                bgColor: '#FF1356',
                textColor: 'white',
                position: 'top-right',
              })
            } else {
              $.toast({
                heading: 'Registration',
                text: "Network error",
                bgColor: '#FF1356',
                textColor: 'white',
                position: 'top-right',
              })
            }
          }
        });
      }
    });
  });
</script>
@endsection
