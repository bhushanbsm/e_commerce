@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
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
              <li class="breadcrumb-item"><a href="#">Edit</a></li>
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
                  <select class="selectpicker form-control" name="category_id" id="category" data-live-search="true" data-title="Nothing selected" value="{{$product->category_id}}">
                    @foreach($categories as $category)
                    <option value="{{$category->id}}">{{__($category->name)}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label" for="sub_category">{{__('Sub-Category')}}</label>
                  <select class="selectpicker form-control" name="sub_category_id" id="sub_category" data-live-search="true" data-title="Nothing selected" value="{{$product->sub_category_id}}">
                    @foreach($sub_categories as $sub_category)
                    <option value="{{$sub_category->id}}">{{__($sub_category->name)}}</option>
                    @endforeach
                  </select> 
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
                  <input id="name" class="form-control" name="name" placeholder="{{__('Name')}}" value="{{$product->name}}" type="text">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label" for="cost">{{__('Cost')}}</label>
                  <input type="text" id="cost" name="cost" class="form-control" placeholder="{{__('Cost')}}" value="{{$product->cost}}">
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group">
                  <label class="form-control-label" for="discount">{{__('Discount')}}</label>
                  <input type="text" id="discount" name="discount" class="form-control" placeholder="{{__('Discount')}}" value="{{$product->discount}}">
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
                <div class="row">
                  @foreach($product->product_images as $product_image)
                  <div class="col-md-3">
                    <a data-fancybox="gallery" href="{{url('files') . '/' .$product_image->path}}"><img class="img-thumbnail" src="{{url('files') . '/' .$product_image->path}}"></a>
                  </div>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
          <div class="pl-lg-4 mt-3">
            <div class="row">
              <div class="col-md-12">
                <input type="hidden" name="id" value="{{$product->id}}" id="product_id">
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
    $('[data-fancybox="gallery"]').fancybox({
      thumbs : {
        autoStart : true
      }
    });
    $('.input-images').imageUploader({
      mimes:['image/jpeg', 'image/png'],
      maxSize: 2 * 1024 * 1024,
    });
    $('.selectpicker').selectpicker({noneSelectedText:'Nothing selected'});
    $('#category').selectpicker('val',{{$product->category_id}});
    $('#sub_category').selectpicker('val',{{$product->sub_category_id}});
    $('#color').selectpicker('val',{{$product->color_id}});
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
          accept: 'image/*',
          filesize: 2,
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
          url: '{{url("products/update")}}',
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
              text: 'Product updated successfully.',
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
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
@endsection

