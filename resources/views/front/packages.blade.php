@extends('front.layout.master')

@section('main_content') 

        <div class="page-top" style="background-image: url({{asset('uploads/banner.jpg')}})">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Packages</h2>
                        <div class="breadcrumb-container">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                                <li class="breadcrumb-item active">Packages</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="package pt_70 pb_50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                     <form action="{{route('packages')}}" method="get">
                        
                        <div class="package-sidebar">
                            <div class="widget">
                                <h2>Search Package</h2>
                                <div class="box">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input type="text" name="name" class="form-control" placeholder="Package Name ...">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget">
                                <h2>Filter by Price</h2>
                                <div class="box">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" name="min_price" class="form-control" placeholder="Min">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="max_price" class="form-control" placeholder="Max">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget">
                                <h2>Filter by Destination</h2>
                                <div class="box">
                                    <select name="destination_id" class="form-select">
                                        @foreach($destinations as $destination)
                                           <option value="{{$destination->id}}">
                                              {{$destination->name}}
                                           </option>

                                        @endforeach
                                    </select>
                                    
                                </div>
                            </div>
                            <div class="widget">
                                <h2>Filter by Review</h2>
                                <div class="box">
                                    <div class="form-check form-check-review form-check-review-1">
                                        <input name="review" class="form-check-input" type="radio" name="reviewRadios" id="reviewRadiosAll" value="all" checked>
                                        <label class="form-check-label" for="reviewRadiosAll">
                                            All
                                        </label>
                                    </div>
                                    <div class="form-check form-check-review">
                                        <input name="review" class="form-check-input" type="radio" name="reviewRadios" id="reviewRadios1" value="5" checked>
                                        <label class="form-check-label" for="reviewRadios1">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </label>
                                    </div>
                                    <div class="form-check form-check-review">
                                        <input name="review" class="form-check-input" type="radio" name="reviewRadios" id="reviewRadios2" value="4">
                                        <label class="form-check-label" for="reviewRadios2">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </label>
                                    </div>
                                    <div class="form-check form-check-review">
                                        <input name="review" class="form-check-input" type="radio" name="reviewRadios" id="reviewRadios3" value="3">
                                        <label class="form-check-label" for="reviewRadios3">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </label>
                                    </div>
                                    <div class="form-check form-check-review">
                                        <input name="review" class="form-check-input" type="radio" name="reviewRadios" id="reviewRadios4" value="2">
                                        <label class="form-check-label" for="reviewRadios4">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </label>
                                    </div>
                                    <div class="form-check form-check-review">
                                        <input name="review" class="form-check-input" type="radio" name="reviewRadios" id="reviewRadios5" value="1">
                                        <label class="form-check-label" for="reviewRadios5">
                                            <i class="fas fa-star"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="filter-button">
                                <button class="btn btn-primary">Filter</button>
                            </div>
                        </div>
                      </form>
                    </div>
                    <div class="col-lg-8 col-md-6">
                        <div class="row">
                          @foreach($packages as $item)
                            <div class="col-lg-6 col-md-6">
                                <div class="item pb_25">
                                    <div class="photo">
                                        <a href="{{route('package', $item->slug)}}"><img src="{{asset('uploads/'.$item->featured_photo)}}" alt=""></a>
                                        <div class="wishlist">
                                            <a href=""><i class="far fa-heart"></i></a>
                                        </div>
                                    </div>
                                    <div class="text">
                                        <div class="price">
                                            ${{$item->price}} 
                                            @if($item->old_price != '')<del>${{$item->old_price}}</del>@endif
                                        </div>
                                        <h2>
                                            <a href="{{route('package', $item->slug)}}">{{$item->name}}</a>
                                        </h2>
                                        <div class="review">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            (4 Reviews)
                                        </div>
                                        <div class="element">
                                            <div class="element-left">
                                                <i class="fas fa-plane-departure"></i> {{$item->destination->name}}
                                            </div>
                                            <div class="element-right">
                                                <i class="fas fa-th-large"></i> {{$item->package_amenities->count()}} Amenities
                                            </div>
                                        </div>
                                        <div class="element">
                                            <div class="element-left">
                                                <i class="fas fa-users"></i> 25 Tours
                                            </div>
                                            <div class="element-right">
                                                <i class="fas fa-clock"></i> 7 Days
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                           @endforeach
                        </div>
                        <!-- <div class="row">
                            <div class="col-md-12">
                                <div class="pagi">
                                    <nav>
                                        <ul class="pagination">
                                            <li class="page-item">
                                                <a class="page-link" href="#" aria-label="Previous">
                                                    <span aria-hidden="true">&laquo;</span>
                                                </a>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item">
                                                <a class="page-link" href="#" aria-label="Next">
                                                    <span aria-hidden="true">&raquo;</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>



@endsection