@extends('layouts.master')

@section('css')
<link rel="stylesheet" href="../assets/css/jqtree.css"></link>
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
          <h6 class="h2 text-white d-inline-block mb-0">Categories</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item"><a href="#">Categories</a></li>
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
  <!-- Category Tree -->
  <div class="col-md-3">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-12">
            <div id="tree"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Category add/edit form -->
  <div class="col-md-9">
    <div class="card full-height">
      <div class="card-body">
        <form action="#" method="post" name="categoryForm" id="categoryForm" style="display: none;">
          @csrf
          <h6 class="heading-small text-muted mb-4">{{__('Basic Details')}}</h6>
          <div class="pl-lg-4">
            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  <label class="form-control-label" for="name">{{__('Name')}}</label>
                  <input type="text" id="name" class="form-control" name="name" placeholder="{{__('Name')}}" value="">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                  <label class="form-control-label" for="description">{{__('Description')}}</label>
                  <textarea id="description" name="description" class="form-control" placeholder="{{__('Description')}}"></textarea>
                </div>
              </div>
            </div>
          </div>
          <h6 class="heading-small text-muted mb-4">{{__('Meta Details')}}</h6>
          <div class="pl-lg-4">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="form-control-label" for="meta_title">{{__('Title')}}</label>
                  <input id="meta_title" name="meta_title" class="form-control" placeholder="{{__('Title')}}" value="" type="text">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                  <label class="form-control-label" for="meta_description">{{__('Description')}}</label>
                  <textarea type="text" id="meta_description" name="meta_description" class="form-control" placeholder="{{__('Description')}}"></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                <div class="form-group">
                  <label class="form-control-label" for="meta_keywords">{{__('Keywords')}}</label>
                  <input type="text" id="meta_keywords" name="meta_keywords" class="form-control" placeholder="{{__('Keywords')}}" value="">
                </div>
              </div>
            </div>
          </div>
          <div class="pl-lg-4">
            <input type="hidden" name="id" id="category-id" value="">
            <input type="hidden" name="parent_id" id="category-parent_id" value="">
            <button type="submit" class="btn btn-primary">{{__('Save')}}</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="../assets/js/jqtree.js"></script>
<script>
  var data = [{
    name: 'Root Category',
    id: null,
    children: []
  }];

  var $tree = $('#tree');

  $(function() {
    refresh_tree();
    $tree.tree({
      data: data,
      autoOpen: true,
      onCreateLi: function(node, $li, is_selected) {
        var action = '<a href="#node-'+ node.id +'" class="edit text-info mr-2" data-node-id="'+ node.id +'"><i class="fas fa-pen"></i></a> <a href="#node-'+ node.id +'" class="delete text-danger" data-node-id="'+ node.id +'"><i class="fas fa-trash"></i></a></span>';
        if (!node.id) {
          action = "";
        }
        $li.find('.jqtree-element').append('<span class="tree-action"> <a href="#node-'+ node.id +'" class="add text-success mr-2" data-node-id="'+ node.id +'"><i class="fas fa-plus"></i></a> ' + action); 
      }
    });
  });

  // Handle a click on the edit link
  $tree.on( 'click', '.edit', function(e) {
    // Get the id from the 'node-id' data property
    var node_id = $(e.currentTarget).data('node-id');
    $('#category-id').val("");
    $('#category-parent_id').val("");

    $.ajax({
      url: '{{url("categories")}}/' + node_id,
      type: 'get',
      success: function (data) {
        $.LoadingOverlay("hide");
        $('#category-id').val(node_id);
        $('#name').val(data.data.name);
        $('#description').val(data.data.description);
        $('#meta_title').val(data.data.meta_title);
        $('#meta_description').val(data.data.meta_description);
        $('#meta_keywords').val(data.data.meta_keywords);
        $('#category-parent_id').val(data.data.parent_id);
        $('#categoryForm').show();
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
        })
      }
    });
  })

  // Handle a click on the edit link
  $tree.on( 'click', '.add', function(e) {
    var node_id = $(e.currentTarget).data('node-id');

    $('#categoryForm')[0].reset();
    $('#categoryForm').show();
    // Get the id from the 'node-id' data property

    $('#category-id').val("");
    $('#category-parent_id').val(node_id);
  })

  // Handle a click on the edit link
  $tree.on( 'click', '.delete', function(e) {
    // Get the id from the 'node-id' data property
    var node_id = $(e.currentTarget).data('node-id');
    $('#category-id').val("");
    $('#category-parent_id').val("");

    $.ajax({
      url: '{{url("categories")}}/' + node_id,
      type: 'delete',
      headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
      cache: false,
      success: function (data) {
        $.LoadingOverlay("hide");
        $.toast({
          heading: 'Categories',
          text: 'Deleted successfully.',
          bgColor: '#FFFF',
          textColor: 'Blue',
          position: 'top-right',
        })
        refresh_tree();
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

  function refresh_tree() {
   $.ajax({
    url: '{{url("categories/list")}}',
    type: 'get',
    cache: false,
    success: function (data) {
      $.LoadingOverlay("hide");
      data = [{
        name: 'Root Category',
        id: null,
        children: data.data
      }];
      $tree.tree('loadData', data);
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

 $('#categoryForm').validate({
  debug: false,
  focusInvalid: false,
  ignore: [],
  errorElement: 'div',
  errorClass: 'invalid-feedback',
  rules: {
    "name": {
      required: true,
      minlength : 4,
    },
    "description" : {
      required: true,
      minlength : 4,
    },
    "meta_title": {
      required: true,
      minlength: 4
    },
    "meta_keywords": {
      required: true,
      minlength:4,
    },
    "meta_description": {
      required: true,
      minlength:4
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

    var url = "";
    if ($('#category-id').val()) {
      var url = "update";
    }

    var formData = new FormData(form);
    $.ajax({
      url: '{{url("categories/")}}/' + url,
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
          heading: 'Category',
          text: 'Category saved successfully.',
          bgColor: '#FFFF',
          textColor: 'Blue',
          position: 'top-right',
        })
        if (!$('#category-id').val()) {
          $('#categoryForm')[0].reset();
          $('#categoryForm').hide();
        }
        refresh_tree();
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

</script>
@endsection

