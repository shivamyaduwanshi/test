@extends('backend.layouts.loggedIn')

@section('title') {{__('Update Category')}} - {{$category->title}} @endsection

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header">{{__('Update Category')}} <button class="btn btn-danger btn-dlt">{{__('Delete')}}</button></h3>
    </div>
</div>
<br/>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <form class="form" action="{{route('backend.update.category',$category->id)}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            {{ method_field('PUT') }}
                            <div class="form-group">
                                <input type="text" class="form-control" name="title" value="{{$category->title}}" placeholder="{{__('Title')}}" required/>
                                @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="img-preview">
                               <img class="image-preview" src="{{$category->image}}" width="100%" height="200px"/>
                            </div>
                            <br/>
                            <div class="form-group">
                                <input type="file" class="form-control" name="category_image" accept="image/*" placeholder="{{__('Category Image')}}"/>
                                @error('category_image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>
                                    <input type="checkbox" name="is_active" value="1" @if($category->is_active) checked @endif/> {{__('Active')}}
                                </label>
                            </div>
                            <input type="submit" value="{{__('Update')}}" class="btn btn-primary float-right"/>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="sub-category-list">

                </div>
                <hr>
                <div class="card">
                    <div class="card-body">
                        <div class="sub-category">
                             <input name="title" class="form-control" value="" placeholder="Sub category title" autocomplete="off">
                                <button class="btn btn-primary btn-add-subcategory">Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

 <!-- Modal -->
 <div class="modal fade" id="deleteModal" role="dialog">
    <div class="modal-dialog modal-md">
        <form class="form" action="{{route('backend.delete.category')}}" method="POST">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">{{__('Delete Category')}}</h4>
        </div>
        <div class="modal-body">
                @csrf
                {{ method_field('DELETE') }}
                 <input type="hidden" name="id" value="{{$category->id}}">
                 <p>{{__('Are you sure want to delete category?')}}</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Cancel')}}</button>
          <input type="submit" class="btn btn-danger" value="{{__('Delete')}}" />
        </div>
      </div>
    </form>
    </div>
  </div>

   <!-- Modal -->
 <div class="modal fade" id="deleteSubCategoryModal" role="dialog">
    <div class="modal-dialog modal-md">
        <form class="form" action="{{route('backend.ajaxdeleteSubCategory')}}" id="subCategoryFormDelete" method="POST">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">{{__('Delete Sub Category')}}</h4>
        </div>
        <div class="modal-body">
                @csrf
                {{ method_field('DELETE') }}
                 <input type="hidden" name="id" value="">
                 <p>{{__('Are you sure want to delete this sub-category?')}}</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Cancel')}}</button>
          <input type="submit" class="btn btn-danger" value="{{__('Delete')}}" />
        </div>
      </div>
    </form>
    </div>
  </div>

 </div>

@include('backend.components.backBtn')
@endsection
@push('css')
<style>
    .sub-category{
        display:flex;
    }
    .sub-category .btn-edit,.sub-category .btn-dlt{
        margin: 0px 5px;
    }
    .sub-category-list .card{
        margin: 5px auto;
    }
    .btn-add-subcategory{
        margin-left: 5px;
    }
</style>
@endpush
@push('js')
 <script>

     $('document').ready(function(e){

         var categoryId = "{{$category->id}}";

         var getSubcategories = function(){
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
                        $('.sub-category-list').html('');
                        if(response.data.length > 0){
                            response.data.map(function(item,index){
                                    let html = '<div class="card">';
                                    html += '<div class="card-body">';
                                    html += '    <div class="sub-category">';
                                    html +=           `<input name="title" class="form-control" value="${item.title}" placeholder="Sub catgory titlt" readonly>`;
                                    html += `            <button class="btn btn-primary btn-edit-subcategory"  data-id="${item.id}">Edit</button>`;
                                    html += `            <button class="btn btn-danger btn-delete-subcategory" data-id="${item.id}">Delete</button>`;
                                    html += '        </div>';
                                    html += '    </div>';
                                    html += '</div>';
                                    $('.sub-category-list').append(html);
                            });
                        }
                    }
				},
				'error' : function(error){
				},
				complete: function() {
				},
				});
  	     }

          getSubcategories();

          $('body').on('click','.btn-add-subcategory',function(e){
               let title = $(this).parents('.sub-category').find('input[name="title"]').val();
                           $(this).parents('.sub-category').find('input[name="title"]').val('');
               if(title){
                    let data = {title:title,category_id:categoryId};
                    $.ajax(
                    {
                        "headers":{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                        'type':'POST',
                        'data':data,
                        'url' : "{{route('backend.ajaxStoreSubCategory')}}",
                    beforeSend: function() {

                    },
                    'success' : function(response){
                        if(response.status == 'success'){
                            toastr.success(response.message);
                            getSubcategories();
                        }
                        if(response.status == 'failed'){
                            toastr.success(response.message);
                        }
                    },
                    'error' : function(error){
                    },
                    complete: function() {
                    },
                    });
               }
               
          });

          $('body').on('click','.btn-delete-subcategory',function(e){
              e.preventDefault();
              let modal = $('#deleteSubCategoryModal');
                  modal.find('input[name="id"]').val($(this).attr('data-id'));
                  modal.modal('show');
          });

            //Image Preview
            $('input[name="category_image"]').on('change',function(e){
                tmppath = URL.createObjectURL(event.target.files[0]);
                $('.image-preview').attr('src',tmppath);
            });
            $('.btn-dlt').on('click',function(e){
                 e.preventDefault();
                 $('#deleteModal').modal('show');
            });
            $('#subCategoryFormDelete').on('submit',function(e){
                e.preventDefault();
                let form         = $(this);
                    data         = form.serialize();
                    $('#deleteSubCategoryModal').modal('hide');
                    $.ajax(
                    {
                        "headers":{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                        'type':'DELETE',
                        'data':data,
                        'url' : "{{route('backend.ajaxdeleteSubCategory')}}",
                    beforeSend: function() {

                    },
                    'success' : function(response){
                        if(response.status == 'success'){
                            toastr.success(response.message);
                            getSubcategories();
                        }
                        if(response.status == 'failed'){
                            toastr.success(response.message);
                        }
                    },
                    'error' : function(error){
                    },
                    complete: function() {
                    },
                    });
            });

            $('body').on('click','.btn-edit-subcategory',function(e){
                $('.btn-edit-subcategory').attr('disabled','disabled');
                $('.btn-delete-subcategory').attr('disabled','disabled');
                $(this).removeClass('btn-edit-subcategory');
                $(this).addClass('btn-update-subcategory');
                $(this).text('Update');
                $(this).removeAttr('disabled');
                $(this).parents('.sub-category').find('.btn-delete-subcategory').hide();
                $(this).parents('.sub-category').append('<button class="btn btn-clear-subcategory">Clear</button>')
                $(this).parents('.sub-category').find('input[name="title"]').removeAttr('readonly');
            });

            $('body').on('click','.btn-clear-subcategory',function(e){
                getSubcategories();
            });

            $('body').on('click','.btn-update-subcategory',function(e){
                let id    = $(this).attr('data-id');
                let title = $(this).parents('.sub-category').find('input[name="title"]').val();
                if(id && title){
                    let data = {title:title,id:id};
                    $.ajax(
                    {
                        "headers":{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                        'type':'POST',
                        'data':data,
                        'url' : "{{route('backend.ajaxUpdateSubCategory')}}",
                    beforeSend: function() {

                    },
                    'success' : function(response){
                        if(response.status == 'success'){
                            toastr.success(response.message);
                            getSubcategories();
                        }
                        if(response.status == 'failed'){
                            toastr.success(response.message);
                        }
                    },
                    'error' : function(error){
                    },
                    complete: function() {
                    },
                    });
                }
            });
     });
 </script>
@endpush