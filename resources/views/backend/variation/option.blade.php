@extends('backend.layouts.loggedIn')

@section('title') {{__('Variation Option')}} - {{$variation->variation_name}} @endsection

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header">{{$variation->variation_name}} {{__('Variation Option')}}</h3>
    </div>
</div>

        <div class="row">
            <div class="col-md-6">
                <div class="variation-option-list"></div>
                <hr>
                <div class="card">
                    <div class="card-body">
                        <div class="variation-option">
                             <input name="variation_option" class="form-control" value="" placeholder="Variation Option" autocomplete="off">
                                <button class="btn btn-primary btn-add-variation-option">Add</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

   <!-- Modal -->
 <div class="modal fade" id="deletevariation-optionModal" role="dialog">
    <div class="modal-dialog modal-md">
        <form class="form" action="{{route('backend.ajaxDeleteVariationOption')}}" id="variation-optionFormDelete" method="POST">
         @csrf
         @method_field('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">{{__('Delete Variation Option')}}</h4>
        </div>
        <div class="modal-body">
                @csrf
                {{ method_field('DELETE') }}
                 <input type="hidden" name="id" value="">
                 <p>{{__('Are you sure want to delete this variation option data?')}}</p>
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
    .variation-option{
        display:flex;
    }
    .variation-option .btn-edit,.variation-option .btn-dlt{
        margin: 0px 5px;
    }
    .variation-option-list .card{
        margin: 5px auto;
    }
    .btn-add-variation-option{
        margin-left: 5px;
    }
    .variation-option .btn{
        margin : 0 5px;
    }
</style>
@endpush
@push('js')
 <script>

     $('document').ready(function(e){

         var variationId = "{{$variation->id}}";

         var getSubcategories = function(){
				$.ajax(
				{
					"headers":{
					'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
				},
					'type':'get',
					'url' : "{{route('backend.variationOptions')}}" + '/' + variationId,
				beforeSend: function() {

				},
				'success' : function(response){
                    if(response.status == 'success'){
                        $('.variation-option-list').html('');
                        if(response.data.length > 0){
                            response.data.map(function(item,index){
                                    let html = '<div class="card">';
                                    html += '<div class="card-body">';
                                    html += '    <div class="variation-option">';
                                    html +=           `<input name="variation_option" class="form-control" value="${item.variation_option}" placeholder="Variation option" readonly>`;
                                    html += `            <button class="btn btn-primary btn-edit-variation-option"  data-id="${item.id}">Edit</button>`;
                                    html += `            <button class="btn btn-danger btn-delete-variation-option" data-id="${item.id}">Delete</button>`;
                                    html += '        </div>';
                                    html += '    </div>';
                                    html += '</div>';
                                    $('.variation-option-list').append(html);
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

          $('body').on('click','.btn-add-variation-option',function(e){
               let variationOption = $(this).parents('.variation-option').find('input[name="variation_option"]').val();
                                     $(this).parents('.variation-option').find('input[name="variation_option"]').val('');
                  console.log(variationOption);
               if(variationOption){
                    let data = {variation_option:variationOption,variation_id:variationId};
                    $.ajax(
                    {
                        "headers":{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                        'type':'POST',
                        'data':data,
                        'url' : "{{route('backend.ajaxStoreVariationOption')}}",
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

          $('body').on('click','.btn-delete-variation-option',function(e){
              e.preventDefault();
              let modal = $('#deletevariation-optionModal');
                  modal.find('input[name="id"]').val($(this).attr('data-id'));
                  modal.modal('show');
          });

            //Image Preview
            $('input[name="variation_image"]').on('change',function(e){
                tmppath = URL.createObjectURL(event.target.files[0]);
                $('.image-preview').attr('src',tmppath);
            });
            $('.btn-dlt').on('click',function(e){
                 e.preventDefault();
                 $('#deleteModal').modal('show');
            });
            $('#variation-optionFormDelete').on('submit',function(e){
                e.preventDefault();
                let form         = $(this);
                    data         = form.serialize();
                    $('#deletevariation-optionModal').modal('hide');
                    $.ajax(
                    {
                        "headers":{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                        'type':'DELETE',
                        'data':data,
                        'url' : "{{route('backend.ajaxDeleteVariationOption')}}",
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

            $('body').on('click','.btn-edit-variation-option',function(e){
                $('.btn-edit-variation-option').attr('disabled','disabled');
                $('.btn-delete-variation-option').attr('disabled','disabled');
                $(this).removeClass('btn-edit-variation-option');
                $(this).addClass('btn-update-variation-option');
                $(this).text('Update');
                $(this).removeAttr('disabled');
                $(this).parents('.variation-option').find('.btn-delete-variation-option').hide();
                $(this).parents('.variation-option').append('<button class="btn btn-clear-variation-option">Clear</button>')
                $(this).parents('.variation-option').find('input[name="variation_option"]').removeAttr('readonly');
            });

            $('body').on('click','.btn-clear-variation-option',function(e){
                getSubcategories();
            });

            $('body').on('click','.btn-update-variation-option',function(e){
                let id    = $(this).attr('data-id');
                let title = $(this).parents('.variation-option').find('input[name="variation_option"]').val();
                if(id && title){
                    let data = {id:id,variation_option:title};
                    $.ajax(
                    {
                        "headers":{
                        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    },
                        'type':'PUT',
                        'data':data,
                        'url' : "{{route('backend.ajaxUpdateVariationOption')}}",
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