 @php
   $images = \App\Models\MediaGallery::whereNull('deleted_at')->orderBy('id','desc')->get();
 @endphp
        <!-- Modal -->
        <div id="image-gallery-modal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Image Gallery</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                      @foreach($images as $key => $image)
                        <div class="col-md-2" style="margin:5px auto;">
                           <img src="{{$image->imageurl}}" style="width:100%;height:100px;" />
                        </div>
                      @endforeach
                    </div>
                </div>
                </div>
            </div>
        </div>