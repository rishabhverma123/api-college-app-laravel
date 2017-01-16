@extends('allwebviews.layouts.admin-master')

@section('content')

    <div class="mid-content ds">
        <h2>Add Question Paper</h2>

        <form class="form-horizontal" action="{{route('admin.postAddQP')}}" method="post" role="form" accept-charset="UTF-8"
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
                <label class="control-label col-sm-3" for="answer">Subject Code</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" id="code" name="code" required="">
                </div>
                <div class="col-sm-2"></div>
            </div>
            <br>
            <div class="form-group">
                <label class="control-label col-sm-3" for="answer">Semester</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" id="semester" name="semester" required="">
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
                <label class="control-label col-sm-3" for="answer">Contributor</label>
                <div class="col-sm-7">
                    <input type="text" class="form-control" id="contributor" name="contributor" required="">
                </div>
                <div class="col-sm-2"></div>
            </div>
            <br>

            <div class="form-group">
                <div class="col-sm-offset-5 col-sm-2 col-sm-offset-5">
                    <button type="submit" class="btn btn-primary">Add Paper</button>
                </div>
            </div>
            <input type="hidden" name="_token" value="{{Session::token()}}">
        </form>
    </div>

@endsection