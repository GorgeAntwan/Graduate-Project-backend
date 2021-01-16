 @extends('layouts.auth')

 @section('content')
 <div class="container">
     <div class="row justify-content-center">
         <div class="col-md-8">
             <div class="card">
                 <div class="card-header">Assistant Dashboard</div>

                 <div class="card-body">
                     Hi Assistant! {{Auth::User()->name}}
                     @foreach($assitant as $s)

                          <P>{{$s->semester }}</P>

                     @endforeach

                 </div>
             </div>
         </div>
     </div>
 </div>
 @endsection