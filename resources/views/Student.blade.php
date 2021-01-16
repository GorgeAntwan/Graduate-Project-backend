@extends('layouts.auth')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Student Dashboard</div>

                <div class="card-body">
                    Hello Student!
                    <div class="list-group">
                        <ul class="list-group list-group">


                            @foreach($all_information_curse as $co)
                            <li class="list-group-item  " class="list-group-item active"><a href="student/{{$co->id}}">{{$co->course_code}}</a></li>

                            @endforeach



                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


 

@endsection