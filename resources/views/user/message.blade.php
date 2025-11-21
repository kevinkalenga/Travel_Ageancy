    
@extends('front.layout.master')

@section('main_content')

        <div class="page-top" style="background-image: url({{asset('uploads/banner.jpg')}})">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Messages</h2>
                        <div class="breadcrumb-container">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                                <li class="breadcrumb-item active">Messages</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>


             <div class="page-content user-panel pt_70 pb_70">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-3 col-md-12">
                        <div class="card">
                            @include('user.sidebar')
                        </div>
                    </div>
                   @if($message_check)
                     <div class="col-lg-5 col-md-12">
                        <h3>All Messages</h3>
                       @foreach($message_comment as $item) 
                         @php  
                          if($item->type == 'User') {
                            $sender_data = App\Models\User::where('id', $item->sender_id)->first();
                          } else {
                              $sender_data = App\Models\Admin::where('id', $item->sender_id)->first();
                          }
                         @endphp
                        
                        <div class="message-item @if($item->type == 'Admin') message-item-admin-border @endif">
                            <div class="message-top">
                                <div class="left">
                                    <img src="{{asset('uploads/'.$sender_data->photo)}}" alt="">
                                </div>
                                <div class="right">
                                    <h4>{{$sender_data->name}}</h4>
                                    <h5>{{$item->type}}</h5>
                                    <div class="date-time">{{$item->created_at->format('Y-m-d H:i A')}}</div>
                                </div>
                            </div>
                            <div class="message-bottom">
                                <p>
                                    {!! $item->comment !!}
                                </p>
                            </div>
                        </div>
                       @endforeach 
                        <!-- <div class="message-item">
                            <div class="message-top">
                                <div class="left">
                                    <img src="uploads/team-1.jpg" alt="">
                                </div>
                                <div class="right">
                                    <h4>Smith Brent</h4>
                                    <h5>User</h5>
                                    <div class="date-time">2024-08-20 08:12:43 AM</div>
                                </div>
                            </div>
                            <div class="message-bottom">
                                <p>I forgot to tell one thing. Can you please allow some toys for my son in this tour? It will be very much helpful if you allow.</p>
                            </div>
                        </div> -->

                        

                     </div>

                     <div class="col-lg-4 col-md-12">
                        <h3>Write a message</h3>
                        <form action="{{route('user_message_submit')}}" method="post">
                            @csrf
                            <div class="mb-2">
                                <!-- <textarea name="comment" class="form-control h-150" cols="30" rows="10" placeholder="Write your message here"></textarea> -->
                                  <textarea name="comment" class="form-control h-150 @error('comment') is-invalid @enderror" cols="30" rows="10" placeholder="Write your message here">{{ old('comment') }}</textarea>

                                   @error('comment')
                                       <div class="invalid-feedback">{{ $message }}</div>
                                   @enderror
                            </div>
                            <div class="mb-2">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                     </div>
                    @else
                      <div class="col-lg-9 col-md-12">
                        <div class="alert alert-danger">
                            No message found<br>
                            <a href="{{route('user_message_start')}}" class="text-decoration-underline">Please click here to start writting the message</a>
                        </div>
                       
                      </div>

                    @endif
                </div>
            </div>
        </div>

@endsection