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
    <!-- Table -->
    <div class="row justify-content-center">
      <div class="col-lg-6 col-md-8">
        <div class="card bg-secondary border-0">
          <div class="card-header bg-transparent pb-5">
            <div class="text-muted text-center mt-2 mb-4"><small>{{ __('Sign up with') }}</small></div>
            <div class="text-center">
              <!-- <a href="#" class="btn btn-neutral btn-icon mr-4">
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
              <small>{{ __('Or sign up with credentials') }}</small>
            </div>
            <form role="form" name="registerForm" method="post" id="registerForm">
              @csrf
              <div class="form-group">
                <div class="input-group input-group-merge input-group-alternative mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="ni ni-hat-3"></i></span>
                  </div>
                  <input class="form-control" name="name" placeholder="{{ __('Name') }}" type="text">
                </div>
              </div>
              <div class="form-group">
                <div class="input-group input-group-merge input-group-alternative mb-3">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="ni ni-email-83"></i></span>
                  </div>
                  <input class="form-control" name="email" placeholder="{{ __('E-Mail') }}" type="email">
                </div>
              </div>
              <div class="form-group">
                <div class="input-group input-group-merge input-group-alternative">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                  </div>
                  <input class="form-control" name="password" placeholder="{{ __('Password') }}" type="password" id="password">
                </div>
              </div>
              <div class="form-group">
                <div class="input-group input-group-merge input-group-alternative">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="ni ni-lock-circle-open"></i></span>
                  </div>
                  <input class="form-control" name="password_confirmation" placeholder="{{ __('Confirm Password') }}" type="password">
                </div>
              </div>
              <div class="row my-4">
                <div class="col-12">
                  <div class="custom-control custom-control-alternative custom-checkbox">
                    <input class="custom-control-input" name="agreeTC" id="customCheckRegister" type="checkbox">
                    <label class="custom-control-label" for="customCheckRegister">
                      <span class="text-muted">{{ __('I agree with the') }} <a href="#!">{{ __('Privacy Policy') }}</a></span>
                    </label>
                  </div>
                </div>
              </div>
              <div class="text-center">
                <button type="submit" class="btn btn-primary mt-4">{{ __('Create account') }}</button>
              </div>
            </form>
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
    $.validator.addMethod("pwcheck", function(value) {
      return /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,15}$/.test( value );
    },
    "A strong password consists of 8 - 20 characters with combination of at least one uppercase letter, one lowercase letter, one numeric digit, and one special character."
    );

    $('#registerForm').validate({
      debug: false,
      focusInvalid: false,
      ignore: [],
      errorElement: 'div',
      errorClass: 'invalid-feedback',
      rules: {
        "password": {
          required: true,
          minlength : 8,
          pwcheck: true
        },
        "password_confirmation" : {
          required: true,
          minlength : 8,
          equalTo : "#password"
        },
        "email": {
          required: true,
          email:true,
        },
        "name": {
          required: true,
          minlength:4,
        },
        "agreeTC": {
          required: true,
        },
      },
      messages: {
        "password_confirmation": {
          equalTo: "Password and Confirm Password does not match."
        }
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
      submitHandler: function (form,event) {
        event.preventDefault();
        $.LoadingOverlay("show");

        var formData = new FormData(form);
        $.ajax({
          url: '{{url("register")}}',
          type: 'POST',
          data: formData,
          contentType: "application/json",
          cache: false,
          contentType: false,
          processData: false,
          success: function (data) {
            $.LoadingOverlay("hide");
            $.toast({
              heading: 'Registration',
              text: 'Registered successfully.',
              bgColor: '#FFFF',
              textColor: 'white',
              position: 'top-right',
            })
            window.location.replace("{{url('/')}}");
          },
          error:function(data) {
            $.LoadingOverlay("hide");

            if (data.status == 412) {
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
