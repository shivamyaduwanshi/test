@extends('backend.layouts.loggedIn')

@section('title') {{ __('Add Variation')}} @endsection

@section('content')

<div class="row">
    <div class="col-lg-12">
        <h3 class="page-header">{{__('Add Variation')}}</h3>
    </div>
</div>
<br/>
        <div class="row">
            <div class="col-md-6 col-offset-md-3">
                <div class="card">
                    <div class="card-body">
                        <form class="form" action="{{route('backend.store.variation')}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <input type="text" class="form-control" name="variation_name" placeholder="{{__('Variation name')}}" />
                                @error('title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <input type="submit" value="{{__('Add')}}" class="btn btn-primary float-right"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
@endsection