@extends('layouts.master')

@section('css')
<style>
  .bootstrap-select > .btn-light {
    color: #8898aa;
    background-color: inherit;
  }
  .bootstrap-select > .btn-light:not(:disabled):not(.disabled):active, .btn-light:not(:disabled):not(.disabled).active, .show > .btn-light.dropdown-toggle {
    color: #8898aa;
    border-color: #adb5bd;
    background-color: inherit;
  }
</style>
@endsection

@section('title')
Dashboard
@endsection

@section('sub-header')
<!-- Header -->
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 text-white d-inline-block mb-0">Resume</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Resume</a></li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('content')
<div class="row">
  <div class="col-xl-8 order-xl-1">
    <div class="card">
      <div class="card-body">
       <form id="resumeForm" name="resumeForm">
        @csrf
        <h6 class="heading-small text-muted mb-4">User information</h6>
        <div class="pl-lg-4">
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label class="form-control-label" for="username">Username</label>
                <input type="text" id="username" name="username" class="form-control" placeholder="Username" value="">
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="form-control-label" for="email">Email address</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label class="form-control-label" for="first-name">First name</label>
                <input type="text" id="first-name" name="first_name" class="form-control" placeholder="First name" value="">
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="form-control-label" for="last-name">Last name</label>
                <input type="text" id="last-name" name="last_name" class="form-control" placeholder="Last name" value="">
              </div>
            </div>
          </div>
        </div>
        <hr class="my-4" />
        <!-- Address -->
        <h6 class="heading-small text-muted mb-4">Contact information</h6>
        <div class="pl-lg-4">
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label class="form-control-label" for="address">Address</label>
                <input id="address" class="form-control" name="address_line" placeholder="" value="" type="text">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label class="form-control-label" for="country">Country</label>
                <select class="selectpicker form-control" name="country_id" id="country" data-live-search="true" data-title="Nothing selected">
                  @foreach($countries as $country)
                  <option value="{{$country->id}}">{{__($country->name)}}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="form-control-label" for="state">State</label>
                <select class="selectpicker form-control" name="state_id" id="state" data-live-search="true" data-title="Nothing selected"></select>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="form-control-label" for="state">City</label>
                <select class="selectpicker form-control" name="city_id" id="city" data-live-search="true" data-title="Nothing selected"></select>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="form-control-label" for="zipcode">Postal code</label>
                <input type="number" id="zipcode" name="zipcode" class="form-control" placeholder="Postal code">
              </div>
            </div>
          </div>
        </div>
        <hr class="my-4" />
        <!-- Description -->
        <h6 class="heading-small text-muted mb-4">About me</h6>
        <div class="pl-lg-4">
          <div class="form-group">
            <label class="form-control-label">About Me</label>
            <textarea rows="4" class="form-control" name="about" placeholder=""></textarea>
          </div>
        </div>
        <hr class="my-4" />
        <!-- Description -->
        <h6 class="heading-small text-muted mb-4">Resume</h6>
        <div class="pl-lg-4">
          <div class="custom-file">
            <input type="file" class="custom-file-input" id="document" name="document" lang="en">
            <label class="custom-file-label" for="customFileLang">Select file</label>
          </div>
        </div>
        <div class="pl-lg-4 mt-3">
          <div class="row">
            <div class="col-md-12">
              <button type="submit" class="btn btn-primary">{{__('Save')}}</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js"></script>
<script>
  $(function () {
    $('.selectpicker').selectpicker({noneSelectedText:'Nothing selected',});
  });

  $('#country').on('change', function (e, clickedIndex, isSelected, previousValue) {
    $.ajax({
      url: '{{url("states")}}/' + $('#country').val(),
      type: 'get',
      cache: false,
      success: function (data) {
        $.LoadingOverlay("hide");
        var options = "";
        $.each(data.data, function( index, value ) {
          options += "<option value="+value.id+">"+value.name+"</option>";
        });
        $('#state').empty().append(options);
        $('#state').selectpicker('refresh');
      },
      error:function(data) {
        $.LoadingOverlay("hide");
        var resp = "Network error";
        if (data.status == 422) {
          resp = data.responseJSON.msg;
        }
        $.toast({
          heading: 'Categories',
          text: resp,
          bgColor: '#FF1356',
          textColor: 'white',
          position: 'top-right',
        });
      }
    });
  });

  $('#state').on('change', function (e, clickedIndex, isSelected, previousValue) {
    $.ajax({
      url: '{{url("cities")}}/' + $('#state').val(),
      type: 'get',
      cache: false,
      success: function (data) {
        $.LoadingOverlay("hide");
        var options = "";
        $.each(data.data, function( index, value ) {
          options += "<option value="+value.id+">"+value.name+"</option>";
        });
        $('#city').empty().append(options);
        $('#city').selectpicker('refresh');
      },
      error:function(data) {
        $.LoadingOverlay("hide");
        var resp = "Network error";
        if (data.status == 422) {
          resp = data.responseJSON.msg;
        }
        $.toast({
          heading: 'Categories',
          text: resp,
          bgColor: '#FF1356',
          textColor: 'white',
          position: 'top-right',
        });
      }
    });
  });

  $(function(){
    $('#resumeForm').validate({
      debug: false,
      focusInvalid: false,
      ignore: [],
      errorElement: 'div',
      errorClass: 'invalid-feedback',
      rules: {
        "country_id": {
          required: true,
        },
        "state_id" : {
          required: true,
        },
        "city_id": {
          required: true,
        },
        "username": {
          required: true,
          minlength:4,
        },
        "first_name": {
          required: true,
          minlength:4,
        },
        "last_name": {
          required: true,
          minlength:4,
        },
        "email": {
          required: true,
          email: true,
        },
        "zipcode": {
          required: true,
        },
        "about": {
          required: true,
          minlength: 20
        },
        "document": {
          required: true,
          accept: 'image/x-eps,application/pdf',
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
      submitHandler: function (form,event) {
        event.preventDefault();
        $.LoadingOverlay("show");

        var formData = new FormData(form);
        $.ajax({
          url: '{{url("resume")}}',
          headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"}, 
          type: "post",
          data: formData,
          contentType: "application/json",
          cache: false,
          contentType: false,
          processData: false,
          success: function (data) {
            $.LoadingOverlay("hide");
            $.toast({
              heading: 'Resume',
              text: 'Resume uploaded successfully.',
              bgColor: '#FFFF',
              textColor: 'blue',
              position: 'top-right',
            })
            window.location.replace("{{url('/dashboard')}}");
          },
          error:function(data) {
            $.LoadingOverlay("hide");
            var resp = "Network error";
            if (data.status == 422) {
              resp = data.responseJSON.msg;
            }
            $.toast({
              heading: 'Categories',
              text: resp,
              bgColor: '#FF1356',
              textColor: 'white',
              position: 'top-right',
            });
          }
        });
      }
    });
  });
</script>
@endsection

