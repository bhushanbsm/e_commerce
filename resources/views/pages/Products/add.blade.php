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
          <h6 class="h2 text-white d-inline-block mb-0">Products</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Products</a></li>
              <li class="breadcrumb-item"><a href="#">Add</a></li>
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
  <div class="col-xl-12 order-xl-1">
    <div class="card">
      <div class="card-body">
        <form id="productForm" name="productForm">
          @csrf
          <h6 class="heading-small text-muted mb-4">{{__('Categorization')}}</h6>
          <div class="pl-lg-4">
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label" for="category">{{__('Category')}}</label>
                  <select class="selectpicker form-control" name="category_id" id="category" data-live-search="true" data-title="Nothing selected">
                    @foreach($categories as $category)
                    <option value="{{$category->id}}">{{__($category->name)}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label" for="sub_category">{{__('Sub-Category')}}</label>
                  <select class="selectpicker form-control" name="sub_category_id" id="sub_category" data-live-search="true" data-title="Nothing selected"> </select> 
                </div>
              </div>
            </div>
          </div>
          <hr class="my-4" />
          <!-- Address -->
          <h6 class="heading-small text-muted mb-4">{{__('Basic Details')}}</h6>
          <div class="pl-lg-4">
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="form-control-label" for="name">{{__('Name')}}</label>
                  <input id="name" class="form-control" name="name" placeholder="{{__('Name')}}" value="" type="text">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label" for="cost">{{__('Cost')}}</label>
                  <input type="text" id="cost" name="cost" class="form-control" placeholder="{{__('Cost')}}" value="">
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label" for="discount">{{__('Discount')}}</label>
                  <input type="text" id="discount" name="discount" class="form-control" placeholder="{{__('Discount')}}" value="">
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label" for="input-country">{{__('Color')}}</label>
                  <select class="selectpicker form-control" name="color_id" id="color" data-live-search="true" data-title="Nothing selected">
                    @foreach($colors as $color)
                    <option value="{{$color->id}}">{{__($color->name)}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
          <hr class="my-4" />
          <h6 class="heading-small text-muted mb-4">{{__('Images')}}</h6>
          <div class="pl-lg-4">
            <div class="row">
              <div class="col-md-12">
                <div class="input-images"></div>
              </div>
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
<script>
  $(function () {
    $('.input-images').imageUploader({
      extensions:[".jpg",".jpeg",".png",".gif",".svg"],
      mimes:["image/jpeg","image/png","image/gif","image/svg+xml"],
      maxSize: 2 * 1024 * 1024,
    });
    $('.selectpicker').selectpicker({noneSelectedText:'Nothing selected',});
  });

  $('#category').on('change', function (e, clickedIndex, isSelected, previousValue) {

    $.ajax({
      url: '{{url("categories/sub-categories")}}/' + $('#category').val(),
      type: 'get',
      cache: false,
      success: function (data) {
        $.LoadingOverlay("hide");
        var options = "";
        $.each(data.data, function( index, value ) {
          options += "<option value="+value.id+">"+value.name+"</option>";
        });
        $('#sub_category').empty().append(options);
        $('#sub_category').selectpicker('refresh');
      },
      error:function(data) {
        $.LoadingOverlay("hide");
        var resp = "Network error";
        if (data.status == 412) {
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
    $('#productForm').validate({
      debug: false,
      focusInvalid: false,
      ignore: [],
      errorElement: 'div',
      errorClass: 'invalid-feedback',
      rules: {
        "category_id": {
          required: true,
        },
        "sub_category_id" : {
          required: true,
        },
        "name": {
          required: true,
          minlength:4,
        },
        "cost": {
          required: true,
          minlength:0,
          number: true
        },
        "discount": {
          required: true,
          minlength:0,
          number: true
        },
        "color_id": {
          required: true,
        },
        "images": {
          required: true,
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
          url: '{{url("products")}}',
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
              heading: 'Product',
              text: 'Product added successfully.',
              bgColor: '#FFFF',
              textColor: 'blue',
              position: 'top-right',
            })
            window.location.replace("{{url('/dashboard')}}");
          },
          error:function(data) {
            $.LoadingOverlay("hide");
            var resp = "Network error";
            if (data.status == 412) {
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

