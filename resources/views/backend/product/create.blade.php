@extends('backend.layouts.loggedIn')

@section('title') {{ __('Create Banner') }} @endsection

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header">{{ __('Create Product') }}</h3>
    </div>
</div>
<br/>
        <div class="row">
            <div class="col-md-6 col-offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <form class="form" action="{{route('backend.store.banner')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label>Title</label>
                                <input type="text" class="form-control" name="title" placeholder="{{__('Title')}}" required/>
                                @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" id="description" class="form-control" value="description"></textarea>
                                @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="row">
                              <div class="col-md-6">
                                <label>Category</label>
                                <select name="category" class="form-control">
                                     @foreach($categories as $key => $category)
                                       @if($key == '0')
                                          <option value="">Select Category</option>
                                        @endif
                                       <option value="{{$category->id}}">{{$category->title}}</option>
                                     @endforeach
                                </select>
                                @error('category')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                              </div>
                              <div class="col-md-6">
                                <label>Sub Category</label>
                                <select name="sub_category" class="form-control">
                                     <option value="">Select Sub Category</option>
                                </select>
                                @error('sub_category')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                              </div>
                            </div>
                            <br>
                            <div class="row">
                              <div class="col-md-6">
                                   <div class="form-group">
                                        <label>Price</label>
                                        <input type="number" class="form-control" name="price" placeholder="{{__('Price')}}" required/>
                                        @error('price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                              </div>
                                <div class="col-md-6">
                                   <div class="form-group">
                                        <label>Selling Price</label>
                                        <input type="number" class="form-control" name="selling_price" placeholder="{{__('Setting Price')}}" required/>
                                        @error('selling_price')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-12">
                                <div class="image-wrapper">
                                  <button class="image-gallery-modal-open-btn">Upload Image</button>
                                </div>
                              </div>
                            </div>
                            <br>
                            <input type="submit" value="{{__('Create')}}" class="btn btn-primary float-right"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @include('backend.components.imageGallery')

@endsection
@push('css')
 <style>
    .modal-dialog {
        width: 98%;
        height: 98%;
        margin: 10px 10px;
        padding: 0;
    }
    .modal-content {
        height: auto;
        min-height: 98%;
        border-radius: 0;
    }
 </style>
@endpush
@push('js')
 {{-- <script src="https://cdn.ckeditor.com/4.16.0/basic/ckeditor.js"></script> --}}
 <script>

    // CKEDITOR.replace('description', {
    //  height: 200
    // });
     
     $('.image-gallery-modal-open-btn').on('click',function(e){
         e.preventDefault();
         $('#image-gallery-modal').modal('show');
     });
     

     $('select[name="category"]').on('change',function(e){
         let categoryId =  $(this).val();
           if(categoryId == '' || categoryId == undefined || categoryId == null){
                 return false;
           }
            $.ajax(
            {
                "headers":{
                'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            },
                'type':'get',
                'url' : "{{route('backend.ajaxGetSubCategories')}}" + '/' + categoryId,
            beforeSend: function() {

            },
            'success' : function(response){
                if(response.status == 'success'){
                    $('select[name="sub_category"]').html('');
                    if(response.data.length > 0){
                        $('select[name="sub_category"]').append('<option value="">Select Sub Category</option>');
                        response.data.map(function(item,index){
                                $('select[name="sub_category"]').append(`<option value="${item.id}">${item.title}</option>`);
                        });
                    }
                }
            },
            'error' : function(error){
            },
            complete: function() {
            },
            });
     });

 </script>

@endpush