@extends('front.layout.master')

@section('main_content') 

        <div class="page-top page-top-package" style="background-image: url({{asset('uploads/'.$package->banner)}})">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2>{{$package->name}}</h2>
                        <h3><i class="fas fa-plane-departure"></i> {{$package->destination->name}}</h3>
                       @if($package->total_score || $package->total_rating)
                        <div class="review">
                            <div class="set">
                                    @php 
                                      $package_rating = $package->total_score/$package->total_rating;
                                      
                                    @endphp
                                    
                                    @for($i=1; $i<=5; $i++)
                                        @if($i <= $package_rating)
                                           <i class="fas fa-star"></i>
                                        @elseif($i-0.5 <= $package_rating)
                                           <i class="fas fa-star-half-alt"></i>
                                        @else 
                                            <i class="far fa-star"></i>
                                        @endif
                                    @endfor
                                
                               
                            </div>
                            <span>({{ $package_rating }} out of 5)</span>
                        </div>
                        @else 
                        <div class="review">
                          <div class="set">
                                @for($i=1; $i<=5; $i++)
                                    <i class="far fa-star"></i>
                                 @endfor
                          
                          </div>
                          <span>(No Rating Found)</span>
                        </div>
                       @endif
                        <div class="price">
                            ${{$package->price}} @if($package->old_price != '')<del>${{$package->old_price}}</del>@endif
                        </div>
                        <div class="person">
                            per person
                        </div>
                    </div>
                </div>
            </div>
        </div> 

                <div class="package-detail pt_50 pb_50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12">


                        <div class="main-item mb_50">

                            <ul class="nav nav-tabs d-flex justify-content-center" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="tab-1" data-bs-toggle="tab" data-bs-target="#tab-1-pane" type="button" role="tab" aria-controls="tab-1-pane" aria-selected="true">Detail</button>
                                </li>
                                @if($package_itineraries->count() > 0)
                                  <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab-2" data-bs-toggle="tab" data-bs-target="#tab-2-pane" type="button" role="tab" aria-controls="tab-2-pane" aria-selected="false">Itinerary</button>
                                  </li>
                                @endif
                                @if($package->map != '')
                                 <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab-3" data-bs-toggle="tab" data-bs-target="#tab-3-pane" type="button" role="tab" aria-controls="tab-3-pane" aria-selected="false">Location</button>
                                 </li>
                                @endif
                               @if($package_photos->count() > 0 || $package_videos->count() > 0)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab-4" data-bs-toggle="tab" data-bs-target="#tab-4-pane" type="button" role="tab" aria-controls="tab-4-pane" aria-selected="false">Gallery</button>
                                </li>
                                @endif
                                @if($package_faqs->count() > 0)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab-5" data-bs-toggle="tab" data-bs-target="#tab-5-pane" type="button" role="tab" aria-controls="tab-5-pane" aria-selected="false">FAQ</button>
                                </li>
                                @endif
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab-6" data-bs-toggle="tab" data-bs-target="#tab-6-pane" type="button" role="tab" aria-controls="tab-6-pane" aria-selected="false">Review</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab-7" data-bs-toggle="tab" data-bs-target="#tab-7-pane" type="button" role="tab" aria-controls="tab-7-pane" aria-selected="false">Enquery</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="tab-8" data-bs-toggle="tab" data-bs-target="#tab-8-pane" type="button" role="tab" aria-controls="tab-8-pane" aria-selected="false">Booking</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                
                                <div class="tab-pane fade show active" id="tab-1-pane" role="tabpanel" aria-labelledby="tab-1" tabindex="0">
                                    <!-- Detail -->
                                    <h2 class="mt_30">Detail</h2>
                                    <p>
                                        {!! $package->description !!}
                                    </p>
                                    
                                    @if($package_amenities_include->count() > 0)
                                    <h2 class="mt_30">Includes</h2>
                                    <div class="amenity">
                                        <div class="row">

                                          @foreach($package_amenities_include as $item)
                                            <div class="col-lg-3 mb_15">
                                                <i class="fas fa-check"></i> {{$item->amenity->name}}
                                            </div>
                                          @endforeach
                                           
                                        </div>
                                    </div>
                                    @endif

                                    @if($package_amenities_exclude->count() > 0)
                                    <h2 class="mt_30">Excludes</h2>
                                    <div class="amenity">
                                        <div class="row">
                                           @foreach($package_amenities_exclude as $item)
                                            <div class="col-lg-3 mb_15">
                                                <i class="fas fa-times"></i> {{$item->amenity->name}}
                                            </div>
                                           @endforeach
                                        </div>
                                    </div>
                                    @endif
                                    <!-- // Detail -->

                                    
                                </div>

                                <div class="tab-pane fade" id="tab-2-pane" role="tabpanel" aria-labelledby="tab-2" tabindex="0">
                                    <!-- Tour Plan -->
                                    <h2 class="mt_30">Itinerary</h2>
                                    <div class="tour-plan">
                                        
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                              @foreach($package_itineraries as $item)
                                                <tr>
                                                    <td><b>{{$item->name}}</b></td>
                                                    <td>
                                                        {!! $item->description !!}
                                                    </td>
                                                </tr>
                                              @endforeach
                                            </table>
                                        </div>
                                    </div>

                                    <!-- // Tour Plan -->
                                </div>

                                <div class="tab-pane fade" id="tab-3-pane" role="tabpanel" aria-labelledby="tab-3" tabindex="0">
                                    <!-- Location -->
                                    <h2 class="mt_30">Location Map</h2>
                                    <div class="location-map">
                                       {!! $package->map !!}
                                    </div>
                                    <!-- // Location -->
                                </div>

                                <div class="tab-pane fade" id="tab-4-pane" role="tabpanel" aria-labelledby="tab-4" tabindex="0">
                                    <!-- Gallery -->
                                  @if($package_photos->count() > 0 )
                                    <h2 class="mt_30">
                                        Photos
                                    </h2>
                                    <div class="photo-all">
                                        <div class="row">
                                          @foreach($package_photos as $item)
                                            <div class="col-md-6 col-lg-3">
                                                <div class="item">
                                                    <a href="{{asset('uploads/'.$item->photo)}}" class="magnific">
                                                        <img src="{{asset('uploads/'.$item->photo)}}" alt="">
                                                    </a>
                                                </div>
                                            </div>
                                          @endforeach
                                     
                                        </div>
                                    </div>
                                   @endif

                                 @if($package_videos->count() > 0 )
                                    <h2 class="mt_30">
                                        <!-- kLuqCtnKr_8 -->
                                        Videos
                                    </h2>
                                    <div class="video-all">
                                        <div class="row">
                                           @foreach($package_videos as $item)
                                            <div class="col-md-6 col-lg-6">
                                                <div class="item">
                                                    <a class="video-button" href="http://www.youtube.com/watch?v={{$item->video}}">
                                                        <img src="http://img.youtube.com/vi/kLuqCtnKr_8/0.jpg" alt="">
                                                        <div class="icon">
                                                            <i class="far fa-play-circle"></i>
                                                        </div>
                                                        <div class="bg"></div>
                                                    </a>
                                                </div>
                                            </div>
                                          @endforeach
                                           
                                        </div>
                                    </div>
                                  @endif
                                    <!-- // Gallery -->
                                </div>


                                <div class="tab-pane fade" id="tab-5-pane" role="tabpanel" aria-labelledby="tab-5" tabindex="0">
                                    <!-- FAQ -->
                                    <h2 class="mt_30">Frequently Asked Questions</h2>
                                    <div class="faq-package">
                                        <div class="accordion" id="accordionExample">
                                         @foreach($package_faqs as $item)
                                            <div class="accordion-item mb_30">
                                                <h2 class="accordion-header" id="heading_{{$loop->iteration}}">
                                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{$loop->iteration}}" aria-expanded="false" aria-controls="collapse_{{$loop->iteration}}">
                                                       {{$item->question}}
                                                    </button>
                                                </h2>
                                                <div id="collapse_{{$loop->iteration}}" class="accordion-collapse collapse" aria-labelledby="heading_1" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        {!! $item->answer !!}
                                                    </div>
                                                </div>
                                            </div>
                                          @endforeach
                                           
                                        </div>
                                    </div>
                                    <!-- // FAQ -->
                                </div>


                                <div class="tab-pane fade" id="tab-6-pane" role="tabpanel" aria-labelledby="tab-6" tabindex="0">
                                    <!-- Review -->
                                    <div class="review-package">

                                        <h2>Reviews ({{$reviews->count()}})</h2>
                                      @forelse($reviews as $item)
                                        <div class="review-package-section">
                                            <div class="review-package-box d-flex justify-content-start">
                                                <div class="left">
                                                    @if($item->user->photo == '')
                                                      <img src="{{asset('uploads/default.png')}}" alt="">
                                                    @else
                                                      <img src="{{asset('uploads/'.$item->user->photo)}}" alt="">
                                                    
                                                    @endif
                                                </div>
                                                <div class="right">
                                                    <div class="name">{{$item->user->name}}</div>
                                                    <div class="date">{{$item->created_at->format('Y-m-d')}}</div>
                                                    <div class="review mb-2">
                                                        <div class="set">
                                                            @for($i=1; $i<=5; $i++)
                                                              @if($i <= $item->rating)
                                                                <i class="fas fa-star"></i>
                                                              @else 
                                                                <i class="far fa-star"></i>
                                                               @endif
                                                            @endfor
                                                        </div>
                                                       
                                                    </div>
                                                    <div class="text">
                                                        {!! $item->comment !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @empty 
                                        <div class="alert alert-danger">
                                            No Review Found.
                                        </div>
                                      @endforelse
                                     
        
        
                                        <div class="mt_40"></div>
        
                                        <h2>Leave Your Review</h2>
                                       
                                       
                                         @if($errors->any())
                                          <div class="alert alert-danger">
                                              <ul>
                                                  @foreach($errors->all() as $error)
                                                  <li>{{ $error }}</li>
                                                  @endforeach
                                              </ul>
                                          </div>
                                        @endif
                                       
                                       
                                       
                                      @if(Auth::guard('web')->check())
                                       @php 
                                        $review_possible = App\Models\Booking::where('package_id', $package->id)->where('user_id', Auth::guard('web')->user()->id)->
                                        where('paid_status', 'COMPLETED')->count();
                                       @endphp
                                       @if($review_possible > 0)
                                        
                                         @php 
                                               App\Models\Review::where('package_id', $package->id)->where
                                               ('user_id', Auth::guard('web')->user()->id)->count() > 0 ? 
                                               $reviewed = true : $reviewed = false;
                                         @endphp
                                        @if($reviewed == false)
                                        <form action="{{route('review_submit')}}" method="post">
                                            @csrf
                                            <input type="hidden" name="package_id" value="{{$package->id}}">
                                        <div class="mb-3">
                                            <div class="give-review-auto-select star-rating">
                                                
                                                <div class="give-review-auto-select star-rating">

                                                    <label  data-value="5" for="star5" title="5 stars"><i class="fas fa-star"></i></label>
                                                    <input type="radio" id="star5" name="rating" value="5" />

                                                    <label  data-value="4" for="star4" title="4 stars"><i class="fas fa-star"></i></label>
                                                    <input type="radio" id="star4" name="rating" value="4" />

                                                    <label  data-value="3" for="star3" title="3 stars"><i class="fas fa-star"></i></label>
                                                    <input type="radio" id="star3" name="rating" value="3" />

                                                    <label  data-value="2" for="star2" title="2 stars"><i class="fas fa-star"></i></label>
                                                    <input type="radio" id="star2" name="rating" value="2" />

                                                    <label  data-value="1" for="star1" title="1 star"><i class="fas fa-star"></i></label>
                                                    <input type="radio" id="star1" name="rating" value="1" />

                                                </div>

                                            </div>
                                            <script>
                                                document.addEventListener('DOMContentLoaded', () => {
    const labels = document.querySelectorAll('.star-rating label');

    labels.forEach(label => {
        label.addEventListener('click', function () {
            let value = this.dataset.value;

            // Sélectionne l’input correct
            document.querySelector(`input[name="rating"][value="${value}"]`).checked = true;

            // Reset couleurs
            labels.forEach(l => l.style.color = '#ccc');

            // Colore toutes les étoiles >= à celle cliquée
            labels.forEach(l => {
                if (l.dataset.value <= value) {
                    l.style.color = '#f5b301';
                }
            });
        });
    });
});


                                            </script>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <textarea class="form-control" rows="3" placeholder="Comment" name="comment" ></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                       </form>
                                       @else
                                          <div class="alert alert-danger">
                                             You have already given the review.
                                          </div>
                                        @endif
                                      @else
                                        <div class="alert alert-danger">
                                            You have to book this package to review
                                        </div>
                                      @endif
                                     @else 
                                      <a href="{{ route('login') }}" class="text-danger text-decoration-underline">Login to Review</a>
                                     @endif
                                    </div>
                                    <!-- // Review -->
                                </div>



                                <div class="tab-pane fade" id="tab-7-pane" role="tabpanel" aria-labelledby="tab-7" tabindex="0">
                                    <!-- Enquery -->
                                    <h2 class="mt_30">Ask Your Question</h2>
                                    <div class="enquery-form">
                                        <form action="{{route('enquery_form_submit', $package->id)}}" method="post">
                                            @csrf
                                            <div class="mb-3">
                                                <input type="text" class="form-control" placeholder="Full Name" name="name">
                                            </div>
                                            <div class="mb-3">
                                                <input type="email" class="form-control" placeholder="Email Address" name="email">
                                            </div>
                                            <div class="mb-3">
                                                <input type="text" class="form-control" placeholder="Phone Number" name="phone">
                                            </div>
                                            <div class="mb-3">
                                                <textarea class="form-control h-150" rows="3" placeholder="Message" name="message"></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <button type="submit" class="btn btn-primary">
                                                    Send Message
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- // Enquery -->
                                </div>


                                <div class="tab-pane fade" id="tab-8-pane" role="tabpanel" aria-labelledby="tab-8" tabindex="0">
                                    <!-- Booking -->
                                       @if($tours->count() > 0)
                                         <form action="{{ route('payment') }}" method="POST">
                                               @csrf
                                               <input type="hidden" name="package_id" value="{{ $package->id }}">
                                               <input type="hidden" id="ticketPrice" name="ticket_price" value="{{ $package->price }}">

                                               <div class="row">
                                                   <div class="col-md-8">
                                                        @foreach($tours as $item)
                                                         @php 
                                                             $total_booked_seats = App\Models\Booking::where('tour_id',$item->id)
                                                                                 ->where('package_id',$package->id)
                                                                                 ->sum('total_person');

                                                             if($item->total_seat == -1) {
                                                                 $remaining_seats = 'Unlimited';
                                                             } else {
                                                                 $remaining_seats = $item->total_seat - $total_booked_seats;
                                                             }
                                                         @endphp

    <div class="p-3 mb-3 rounded 
                @if($item->booking_end_date < date('Y-m-d') || ($remaining_seats !== 'Unlimited' && $remaining_seats == 0)) 
                    bg-light text-muted expired-tour 
                @else border 
                @endif">
        
        <h2 class="mt-2">
            @if($item->booking_end_date < date('Y-m-d') || ($remaining_seats !== 'Unlimited' && $remaining_seats == 0))
                <del>Tour {{ $loop->iteration }}</del>
            @else
                <input type="radio" 
                       name="tour_id" 
                       value="{{ $item->id }}" 
                       @if($loop->first) checked @endif>
                Tour {{ $loop->iteration }}
            @endif
        </h2>

        <div class="summary">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <td><b>Tour Start Date</b></td>
                        <td>{{ $item->tour_start_date }}</td>
                    </tr>
                    <tr>
                        <td><b>Tour End Date</b></td>
                        <td>{{ $item->tour_end_date }}</td>
                    </tr>
                    <tr>
                        <td><b>Booking End Date</b></td>
                        <td class="text-danger">{{ $item->booking_end_date }}</td>
                    </tr>
                    <tr>
                        <td><b>Total Seat</b></td>
                        <td>
                            @if($item->total_seat == -1)
                            Unlimited
                            @else
                            {{ $item->total_seat }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><b>Booked Seat</b></td>
                        <td>{{ $total_booked_seats }}</td>
                    </tr>
                    <tr>
                        <td><b>Remaining Seat</b></td>
                        <td>{{ $remaining_seats }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @if($item->booking_end_date < date('Y-m-d'))
            <p class="text-danger fw-bold">❌ Booking closed</p>
        @elseif($remaining_seats !== 'Unlimited' && $remaining_seats == 0)
            <p class="text-danger fw-bold">❌ Fully booked</p>
        @endif
    </div>
@endforeach

                                                       
                                                   </div>

                                                   <div class="col-md-4">
                                                       <h2 class="mt-3">Payment</h2>

                                                       <label><b>Number of Persons</b></label>
                                                       <input type="number" id="numPersons" name="total_person" value="1" min="1" class="form-control mb-2" oninput="calculateTotal()">

                                                       <label><b>Total</b></label>
                                                       <input type="text" id="totalAmount" class="form-control mb-2" value="${{ $package->price }}" disabled>

                                                       <label><b>Select Payment Method</b></label>
                                                       <select name="payment_method" class="form-select mb-3">
                                                           <option value="Paypal">PayPal</option>
                                                           <option value="Stripe">Stripe</option>
                                                           <option value="Cash">Cash</option>
                                                       </select>

                                                       @if(Auth::check())
                                                           <button type="submit" class="btn btn-primary w-100">Pay Now</button>
                                                       @else
                                                           <a href="{{ route('login') }}" class="text-danger text-decoration-underline">Login to Book</a>
                                                       @endif
                                                   </div>
                                               </div>
                                           </form>
                                             @else 
                                     <div class="alert alert-danger">
                                        No Tours is Available
                                     </div>
                                     @endif
                                        


<script>
    function calculateTotal() {
        const ticketPrice = parseFloat(document.getElementById('ticketPrice').value);
        const numPersons = parseInt(document.getElementById('numPersons').value);
        const totalAmount = ticketPrice * (numPersons > 0 ? numPersons : 1);
        document.getElementById('totalAmount').value = `$${totalAmount}`;
    }
</script>
                                
                                    <!-- // Booking -->
                                   
                                </div>

                            </div>
                            
                        </div>
                            

                    </div>
                </div>
            </div>
        </div>


@endsection