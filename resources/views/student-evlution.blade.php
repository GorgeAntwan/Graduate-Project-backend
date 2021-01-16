@extends('layouts.app')

@section('content')
<div class="container">
    <form method="post" action="student/{{$id->id}}">
        {{ csrf_field() }}

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
                <span class="input-group-text" id="my-addon">q_no1</span>
            </div>
            <input class="form-control" type="text" placeholder="Answer_1" aria-label="Recipient's " aria-describedby="my-addon" name='Answer_1' required>
        </div>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="my-addon">q_no2</span>
            </div>
            <input class="form-control" type="text" name='Answer_2' placeholder="Answer_2" aria-label="Recipient's " aria-describedby="my-addon" required>
        </div>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="my-addon">q_no3</span>
            </div>
            <input class="form-control" type="text" name='Answer_3' placeholder="Answer_3" aria-label="Recipient's " aria-describedby="my-addon" required>
        </div>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="my-addon">q_no4</span>
            </div>
            <input class="form-control" type="text" name='Answer_4' placeholder="Answer_4" aria-label="Recipient's " aria-describedby="my-addon" required>
        </div>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="my-addon">q_no5</span>
            </div>
            <input class="form-control" type="text" name='Answer_5' placeholder="Answer_5" aria-label="Recipient's " aria-describedby="my-addon" required>
        </div>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="my-addon">q_no6</span>
            </div>
            <input class="form-control" type="text" name='Answer_6' placeholder="Answer_6" aria-label="Recipient's " aria-describedby="my-addon" required>
        </div>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="my-addon">q_no7</span>
            </div>
            <input class="form-control" type="text" name='Answer_7' placeholder="Answer_7" aria-label="Recipient's " aria-describedby="my-addon" required>
        </div>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="my-addon">q_no8</span>
            </div>
            <input class="form-control" type="text" name='Answer_8' placeholder="Answer_8" aria-label="Recipient's " aria-describedby="my-addon" required>
        </div>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="my-addon">q_no9</span>
            </div>
            <input class="form-control" type="text" name='Answer_9' placeholder="Answer_9" aria-label="Recipient's " aria-describedby="my-addon" required>
        </div>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="my-addon">q_no10</span>
            </div>
            <input class="form-control" type="text" name='Answer_10' placeholder="Answer_10" aria-label="Recipient's " aria-describedby="my-addon" required>
        </div>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="my-addon">q_no11</span>
            </div>
            <input class="form-control" type="text" name='Answer_11' placeholder="Answer_11" aria-label="Recipient's " aria-describedby="my-addon" required>
        </div>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="my-addon">q_no12</span>
            </div>
            <input class="form-control" type="text" name='Answer_12' placeholder="Answer_12" aria-label="Recipient's " aria-describedby="my-addon" required>
        </div>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="my-addon">q_no13</span>
            </div>
            <input class="form-control" type="text" name='Answer_13' placeholder="Answer_13" aria-label="Recipient's " aria-describedby="my-addon" required>
        </div>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="my-addon">q_no14</span>
            </div>
            <input class="form-control" type="text" name='Answer_14' placeholder="Answer_14" aria-label="Recipient's " aria-describedby="my-addon" required>
        </div>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="my-addon">q_no15</span>
            </div>
            <input class="form-control" type="text" name='Answer_15' placeholder="Answer_15" aria-label="Recipient's " aria-describedby="my-addon" required>
        </div>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text" id="my-addon">Your comment</span>
            </div>
            <input class="form-control" type="text" name='comment' placeholder="comment" aria-label="Recipient's " aria-describedby="my-addon" required>
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