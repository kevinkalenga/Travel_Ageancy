   @extends('admin.layout.master')
   @section('main_content')
     <div class="navbar-bg"></div>
    @include('admin.layout.nav')
    @include('admin.layout.sidebar')

        <div class="main-content">
            <section class="section">
                <div class="section-header justify-content-between">
                    <h1>Edit Setting</h1>
                </div>
                <div class="section-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <form action="{{route('admin_setting_update', $setting->id)}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                       
                                        <div class="row">
                                           <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Existing Logo</label>
                                                    <div><img src="{{asset('uploads/'.$setting->logo)}}" alt="logo" class="w_200"></div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Change Logo</label>
                                                    <div>
                                                        <input type="file" name="logo">
                                                    </div>
                                                </div>
                                           </div>
                                           <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Existing Favicon</label>
                                                    <div><img src="{{asset('uploads/'.$setting->fivecon)}}" alt="logo" class="w_50"></div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Change Favicon</label>
                                                    <div>
                                                        <input type="file" name="fivecon">
                                                    </div>
                                                </div>
                                           </div>
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

    
