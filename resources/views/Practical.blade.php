@extends('layouts.app')

@section('content')
<div class="container">
    <form method="post" action="Practical/{{$id ?? ''->id}}/{{$questionareNew->id}}">
        {{ csrf_field() }}
        {{$questionareNew->id}}
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="my-addon">q_no16</span>
            </div>
            <input class="form-control" type="text" placeholder="Answer_16" aria-label="Recipient's " aria-describedby="my-addon" name='Answer_16' required>
        </div>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="my-addon">q_no17</span>
            </div>
            <input class="form-control" type="text" name='Answer_17' placeholder="Answer_17" aria-label="Recipient's " aria-describedby="my-addon" required>
        </div>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="my-addon">q_no18</span>
            </div>
            <input class="form-control" type="text" name='Answer_18' placeholder="Answer_18" aria-label="Recipient's " aria-describedby="my-addon" required>
        </div>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="my-addon">q_no19</span>
            </div>
            <input class="form-control" type="text" name='Answer_19' placeholder="Answer_19" aria-label="Recipient's " aria-describedby="my-addon" required>
        </div>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="my-addon">q_no20</span>
            </div>
            <input class="form-control" type="text" name='Answer_20' placeholder="Answer_20" aria-label="Recipient's " aria-describedby="my-addon" required>
        </div>

        <div class="form-group row mb-0">
            <div class="col-md-8 offset-md-4">
                <button type="submit" class="btn btn-primary">
                    Submite
                </button>


            </div>
        </div>

    </form>
</div>
 
@endsection