@extends('backend.layouts.loggedIn')

@section('title') {{__('Update Variation')}} - {{$variation->title}} @endsection

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header">{{__('Update Variation')}} <button class="btn btn-danger btn-dlt">{{__('Delete')}}</button></h3>
    </div>
</div>
<br/>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <form class="form" action="{{route('backend.update.variation',$variation->id)}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            {{ method_field('PUT') }}
                            <div class="form-group">
                                <input type="text" class="form-control" name="variation_name" value="{{$variation->variation_name}}" placeholder="{{__('Variation name')}}" required/>
                                @error('variation_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <input type="submit" value="{{__('Update')}}" class="btn btn-primary float-right"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>

 <!-- Modal -->
 <div class="modal fade" id="deleteModal" role="dialog">
    <div class="modal-dialog modal-md">
        <form class="form" action="{{route('backend.delete.variation')}}" method="POST">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">{{__('Delete variation')}}</h4>
        </div>
        <div class="modal-body">
                @csrf
                {{ method_field('DELETE') }}
                 <input type="hidden" name="id" value="{{$variation->id}}">
                 <p>{{__('Are you sure want to delete variation data?')}}</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">{{__('Cancel')}}</button>
          <input type="submit" class="btn btn-danger" value="{{__('Delete')}}" />
        </div>
      </div>
    </form>
    </div>
  </div>

@include('backend.components.backBtn')
@endsection
