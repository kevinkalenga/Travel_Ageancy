   @extends('admin.layout.master')
   @section('main_content')
     <div class="navbar-bg"></div>
    @include('admin.layout.nav')
    @include('admin.layout.sidebar')

        <div class="main-content">
            <section class="section">
                <div class="section-header justify-content-between">
                    <h1>Edit Destination</h1>
                    <div>
                        <a href="{{route('admin_destination_index')}}" class="btn btn-primary">
                            <i class="fas fa-plus"></i>
                            View All
                        </a>
                    </div>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{route('admin_destination_edit_submit', $destination->id)}}" method="post"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                             <label class="form-label">Existing Featured Photo</label>
                                             <div>
                                              <img class="w_100" src="{{asset('uploads/'.$destination->featured_photo)}}" alt="">
                                             </div>
                                        </div>
                                        <div class="mb-3">
                                             <label class="form-label">Change Featured Photo </label>
                                             <div>
                                                <input type="file" name="featured_photo">
                                             </div>
                                        </div>
                                                     <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                      <label class="form-label">Name *</label>
                                                      <input type="text" class="form-control" name="name" value="{{$destination->name}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Slug *</label>
                                                    <input type="text" class="form-control" name="slug" value="{{$destination->slug}}">
                                                </div>
                                            </div>
                                        </div>
                                       
                                      
                                
                                        <div class="mb-3">
                                            <label class="form-label">Description *</label>
                                             <textarea name="description" class="form-control editor" cols="30" rows="10">{{ $destination->description }}</textarea>

                                        </div>
                                        <di class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                   <label class="form-label">Country </label>
                                                   <input type="text" class="form-control" name="country" value="{{$destination->country}}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                   <label class="form-label">Language </label>
                                                   <input type="text" class="form-control" name="language" value="{{$destination->language}}">
                                                </div>
                                            </div>
                                        </di>
                                         <div class="row">
                                             <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Currency </label>
                                                    <input type="text" class="form-control" name="currency" value="{{$destination->currency}}">
                                                 </div>
                                             </div>
                                             <div class="col-md-4">
                                                <div class="mb-3">
                                                 <label class="form-label">Area </label>
                                                  <input type="text" class="form-control" name="area" value="{{$destination->area}}">
                                                </div>
                                             </div>
                                             <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">TimeZone </label>
                                                    <input type="text" class="form-control" name="timezone" value="{{$destination->timezone}}">
                                                 </div>
                                             </div>
                                         </div>
                                        
                                        
                                        
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Visa Requirement </label>
                                             <textarea name="visa_requirement" class="form-control editor" cols="30" rows="10">{{ $destination->visa_requirement }}</textarea>

                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Best Time  to Visit</label>
                                             <textarea name="best_time" class="form-control editor" cols="30" rows="10">{{ $destination->best_time }}</textarea>

                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Health and Safety</label>
                                             <textarea name="health_safety" class="form-control editor" cols="30" rows="10">{{ $destination->health_safety }}</textarea>

                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Map (iframe code)</label>
                                             <textarea name="map" class="form-control h_100" cols="30" rows="10">{{ $destination->map }}</textarea>

                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label"></label>
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
       

    @endsection

    
