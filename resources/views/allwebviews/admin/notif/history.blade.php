@extends('allwebviews.layouts.admin-master')

@section('title')
    Add notification
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Notification History</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body" style="overflow-x: scroll">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                {{--<th class="col-sm-1">Id</th>--}}
                                {{--<th class="col-sm-1">Sent</th>--}}
                                {{--<th class="col-sm-1">Title</th>--}}
                                {{--<th class="col-sm-2">Message</th>--}}
                                {{--<th class="col-sm-1">Image</th>--}}
                                {{--<th class="col-sm-1">Timestamp</th>--}}
                                {{--<th class="col-sm-1">Delivered</th>--}}
                                {{--<th class="col-sm-1">Opened</th>--}}
                                {{--<th class="col-sm-2">Payload</th>--}}
                                {{--<th class="col-sm-2">Creator</th>--}}
                                <th>Id</th>
                                <th>Sent</th>
                                <th class="col-sm-3">Title</th>
                                <th>Message</th>
                                <th>Image</th>
                                <th>Timestamp</th>
                                <th>Deld</th>
                                <th>Opnd</th>
                                <th>Payload</th>
                                <th>Creator</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $imgPath = \App\Library\ConstantPaths::$PUBLIC_PATH . \App\Library\ConstantPaths::$PATH_NOTIFICATION_IMAGES;
                            ?>
                            @foreach($notifications as $notification)
                                <tr>
                                    <td>{{$notification['id']}}</td>
                                    <td>{!!$notification['sent'] == 0 ? 'No' : '<span style="color:red">Yes, '.$notification['message_id']
                                        .'</span>'!!}</td>
                                    <td>{{$notification['title']}}</td>
                                    <td>{{$notification['message']}}</td>
                                    <td>
                                        @if($notification['image'] != '')
                                            @if(strpos($notification['image'],'/')!==false)
                                                <img src="{{$notification['image']}}" height="65" width="120">
                                            @else
                                                <img src="{{$imgPath}}{{$notification['image']}}" height="65"
                                                     width="120">
                                            @endif
                                        @endif
                                    </td>
                                    <td>{{$notification['timestamp']}}</td>
                                    <td>{{$notification['count_delivered']}}</td>
                                    <td>{{$notification['count_opened']}}</td>

                                    <td>{{$notification['payload']}}</td>
                                    <td>{{$notification['author']}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
    </div>

@endsection

