@extends('allwebviews.layouts.admin-master')

@section('content')

    <div class="mid-content ds">
        <h2>Add Resume</h2>

        <form class="form-horizontal" action="{{route('admin.postAddResume')}}" method="post" role="form" accept-charset="UTF-8"
              enctype="multipart/form-data">

            <div class="form-group">
                <label class="control-label col-sm-3" for="image">Choose File</label>
                <div class="col-sm-7">
                    <input class="form-control" type="file" name="file" id="file">
                </div>
                <div class="col-sm-2"></div>
            </div>
            <br>
            <div class="form-group">
                <label class="control-label col-sm-3" for="answer">Name</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" id="name" name="name" required="">
                </div>
                <div class="col-sm-2"></div>
            </div>
            <br>
            <div class="form-group">
                <label class="control-label col-sm-3" for="answer">Batch</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" id="batch" name="batch" required="">
                </div>
                <div class="col-sm-2"></div>
            </div>
            <br>

            <div class="form-group">
                <label class="control-label col-sm-3" for="branch">Branch</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" id="branch" name="branch" required="">
                </div>
                <div class="col-sm-2"></div>
            </div>
            <br>


            <div class="form-group">
                <div class="col-sm-offset-5 col-sm-2 col-sm-offset-5">
                    <button type="submit" class="btn btn-primary">Add Resume</button>
                </div>
            </div>
            <input type="hidden" name="_token" value="{{Session::token()}}">
        </form>
    </div>

@endsection