@extends('layouts.master')

@section('css')
<!-- Datatable -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.bootstrap4.min.css">
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
            </ol>
          </nav>
        </div>
        <div class="col-lg-6 col-5 text-right">
          <a href="{{url('products/add')}}" class="btn btn-md btn-neutral">New</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('content')
<div class="row">
  <div class="col">
    <div class="card">
      <!-- Card header -->
      <div class="card-header border-0">
        <h3 class="mb-0">Products</h3>
      </div>
      <!-- Light table -->
      <div class="table-responsive">
        <table class="table align-items-center table-flush" id="products-table">
          <thead class="thead-light">
            <tr>
              <th scope="col">Images</th>
              <th scope="col" class="sort" data-sort="name">Name</th>
              <th scope="col" class="sort" data-sort="category">Category</th>
              <th scope="col" class="sort" data-sort="sub_category">Sub-Category</th>
              <th scope="col" class="sort" data-sort="cost">cost</th>
              <th scope="col" class="sort" data-sort="discount">discount</th>
              <th scope="col" class="sort" data-sort="color">colors</th>
              <th scope="col"></th>
            </tr>
          </thead>
          <tbody class="list">
            @foreach($products as $product)
            <tr>
              <th scope="row">
                <div class="media align-items-center">
                  <a href="#" class="avatar rounded-circle mr-3">
                    @if(!empty($product->product_image))
                    <img alt="Image placeholder" src="{{url('files') . '/' . $product->product_image->path}}">
                    @endif
                  </a>
                </div>
              </th>
              <th scope="row">
                <div class="media align-items-center">
                  <div class="media-body">
                    <span class="name mb-0 text-sm">{{__($product->name)}}</span>
                  </div>
                </div>
              </th>
              <td class="budget">
                {{__($product->category->name)}}
              </td>
              <td>
                {{__($product->sub_category->name)}}
              </td>
              <td>
                {{__($product->cost)}}
              </td>
              <td>
                {{__($product->discount)}}
              </td>
              <td>
                {{__($product->product_color->name)}}
              </td>
              <td class="text-right notexport" data-orderable="false">
                <div class="dropdown">
                  <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    <a class="dropdown-item" href="{{url('products/'.$product->id)}}">Edit</a>
                    <a class="dropdown-item" onclick="deleteProduct({{$product->id}})" href="#">Delete</a>
                  </div>
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
  </div>
</div>
</div>
@endsection

@section('scripts')
<!-- Datatable -->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.0/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.bootstrap4.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.print.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.0/js/buttons.colVis.min.js"></script>

<script>
  $(document).ready( function () {
    $('#products-table').DataTable({
      dom: 'Bfrtip',
      buttons: [
      'csv', 'excel', 'pdf', 'print'
      ],
      "language": {
    "paginate": {
      "previous": '<i class="fas fa-angle-left"></i>',
      "next": '<i class="fas fa-angle-right"></i>',
    }
  }
    });
  });

  function deleteProduct(id) {
    $.ajax({
      url: '{{url("products")}}/' + id,
      type: 'delete',
      headers: {'X-CSRF-TOKEN': "{{ csrf_token() }}"},
      cache: false,
      success: function (data) {
        $.LoadingOverlay("hide");
        $.toast({
          heading: 'Product',
          text: 'Deleted successfully.',
          bgColor: '#FFFF',
          textColor: 'Blue',
          position: 'top-right',
        })
        window.location.reload()
      },
      error:function(data) {
        $.LoadingOverlay("hide");
        var resp = "Network error";
        if (data.status == 412) {
          resp = data.responseJSON.msg;
        }
        $.toast({
          heading: 'Product',
          text: resp,
          bgColor: '#FF1356',
          textColor: 'white',
          position: 'top-right',
        });
      }
    });
  }
</script>
@endsection

