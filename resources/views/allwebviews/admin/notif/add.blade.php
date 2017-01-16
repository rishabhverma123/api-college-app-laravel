@extends('allwebviews.layouts.admin-master')

@section('title')
    Add notification
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Add Notification</h1>
            <h6 style="color: blue;">Using Firebase Web API Key
                : {{substr(env('FCM_API_KEY'),0,5)."*****************".substr(env('FCM_API_KEY'),-5)}}</h6>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="col-lg-6 left">
        <div class="panel panel-default">
            <div class="panel-heading">
                <b>Create an app wide notification</b>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form action="{{route('admin.postAddNotification')}}" method="post" role="form"
                              enctype="multipart/form-data">
                            <div class="form-group">
                                <label>Title of Notification</label>
                                <input type="text" name="title" required="" placeholder="Enter Title"
                                       class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Body Message</label>
                                <input type="text" name="message" required="" placeholder="Enter Body Message"
                                       class="form-control">
                            </div>

                            <b>Open Action on clicking notification</b>
                            <div class="form-group col-lg-offset-1" style="padding: 3px">
                                <div class="form-group">

                                    <input type="checkbox" name="isTypeWebView" value="1"/> Open in Web View (For
                                    redirecting to a link)
                                    <input type="text" name="webViewUrl" placeholder="Enter URL to open (use http://)"
                                           class="form-control" value="http://">
                                </div>
                                <div class="form-group">
                                    <label>Content to be shown in app window</label>
                                    <textarea name="content" rows="5" class="form-control"></textarea>
                                </div>
                                <br>
                                <div class="form-group">
                                    <input type="checkbox" name="setOngoing" value="1"/><b>- setOngoing (Persistent
                                        notification)</b>
                                </div>
                            </div>


                            <b>Image Options</b><br>
                            <div class="form-group col-lg-offset-1" style="padding: 3px">
                                <div class="form-group">
                                    <input type="file" name="image" class="form-control">
                                </div>
                                <div class="col-lg-offset-5"><b>OR</b></div>
                                <div class="form-group">
                                    <input type="text" name="imageurl" placeholder="Enter Image URL with protocol"
                                           class="form-control">
                                </div>
                            </div>
                            <b style="color:red">Image will be uploaded as soon as you click here.
                                So please be careful!</b>
                            <div class="col-lg-offset-8">
                                <button type="submit" class="btn btn-danger">Prepare JSON -></button>
                            </div>
                            <input type="hidden" name="push_type" value="global">
                            {{csrf_field()}}

                        </form>
                    </div>
                    <!-- /.col-lg-6 (nested) -->

                    <!-- /.col-lg-6 (nested) -->
                </div>
                <!-- /.row (nested) -->
            </div>
            <!-- /.panel-body -->
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                Create a custom audience wide notification
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <form action="" method="get" role="form">

                        </form>
                    </div>
                    <!-- /.col-lg-6 (nested) -->

                    <!-- /.col-lg-6 (nested) -->
                </div>
                <!-- /.row (nested) -->
            </div>
            <!-- /.panel-body -->
        </div>
    </div>
    <!-- /.the whole col-lg-6 panels -->

    <div class="col-lg-6 right">
        <div class="panel panel-default">
            @if(Session::has('prepared_json'))
                <div class="panel-heading">
                    <b>The prepared JSON</b>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <b>
                            <pre>{{json_encode(Session::get('prepared_json'), JSON_PRETTY_PRINT)}}</pre>
                        </b>
                        <div class="col-lg-offset-8">
                            <form action="{{route('admin.postConfirmAddNotification')}}" method="post">
                                <input type="hidden" name="prepared_json"
                                       value="{{json_encode(Session::get('prepared_json'))}}">
                                <input type="hidden" name="id" value="{{Session::get('id')}}">
                                <button type="submit" class="btn btn-danger">Send to Firebase</button>
                                {{csrf_field()}}
                            </form>
                        </div>
                    </div>
                    <!-- /.row (nested) -->
                </div>

            @elseif(Session::has('response_json'))
                <div class="panel-heading">
                    <b>The response JSON from Firebase</b>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <b>
                            <pre>{{Session::get('response_json')}}</pre>
                        </b>
                    </div>
                    <!-- /.row (nested) -->
                </div>
        @endif
        <!-- /.panel-body -->
        </div>
    </div>


@endsection

