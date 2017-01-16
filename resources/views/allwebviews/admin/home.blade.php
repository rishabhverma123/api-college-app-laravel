@extends('allwebviews.layouts.admin-master')

@section('title')
    Home
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{{env('APP_NAME')}} - Home</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-clock-o fa-fw"></i> Ongoing News
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <ul class="timeline">
                        @if($newsFeeds!=null)
                            <?php $i = 0;?>
                            @foreach($newsFeeds as $newsFeed)
                                @if($i%2==0)
                                    <li>
                                        <div class="timeline-badge"><i class="fa fa-check"></i>
                                        </div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <h4 class="timeline-title">{{$newsFeed['author']}}</h4>
                                                <p>
                                                    <small class="text-muted"><i class="fa fa-clock-o"></i>
                                                        {{$newsFeed['timeString']}}
                                                    </small>
                                                </p>
                                            </div>
                                            <div class="timeline-body">
                                                <p>{{$newsFeed['content']}}</p>
                                                @if($newsFeed['image']!="null")
                                                    <p><img style="max-width: 100px" src="{{$newsFeed['image']}}"></p>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                @else
                                    <li class="timeline-inverted">
                                        <div class="timeline-badge warning"><i class="fa fa-check"></i>
                                        </div>
                                        <div class="timeline-panel">
                                            <div class="timeline-heading">
                                                <h4 class="timeline-title">{{$newsFeed['author']}}</h4>
                                                <p>
                                                    <small class="text-muted"><i class="fa fa-clock-o"></i>
                                                        {{$newsFeed['timeString']}}
                                                    </small>
                                                </p>
                                            </div>
                                            <div class="timeline-body">
                                                <p>{{$newsFeed['content']}}</p>
                                                @if($newsFeed['image']!="null")
                                                    <p><img style="max-width: 100px" src="{{$newsFeed['image']}}"></p>
                                                @endif
                                            </div>
                                        </div>
                                    </li>
                                @endif
                                <?php $i++; ?>
                            @endforeach
                        @endif
                    </ul>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-8 -->
        <div class="col-lg-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-bell fa-fw"></i> Notifications Panel
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="list-group">
                        <a href="#" class="list-group-item">
                            <i class="fa fa-comment fa-fw"></i> New Comment
                                    <span class="pull-right text-muted small"><em>4 minutes ago</em>
                                    </span>
                        </a>
                    </div>
                    <!-- /.list-group -->
                    <a href="#" class="btn btn-default btn-block">View All Alerts</a>
                </div>
                <!-- /.panel-body -->
            </div>

            <!-- /.panel -->
            <div class="chat-panel panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-comments fa-fw"></i> Chat
                </div>
                <!-- /.panel-heading -->

                <form action="{{route('admin.postNewChatMessage')}}" method="post">
                    <div class="panel-footer">
                        <div class="input-group">
                            <input id="btn-input" type="text" class="form-control input-sm"
                                   placeholder="Type your message here..." name="message"/>
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-warning btn-sm" id="btn-chat">
                                        Send
                                    </button>
                                </span>
                        </div>
                    </div>
                    {{csrf_field()}}
                </form>
                <!-- /.panel-footer -->
                <div class="panel-body">
                    <ul class="chat">
                        @if($adminMessages!=null)
                            @foreach($adminMessages as $adminMessage)
                                <li class="left clearfix">

                                    <div class="header">
                                        <strong class="primary-font">{{$adminMessage['author']}}</strong>
                                        <small class="pull-right text-muted">
                                            <i class="fa fa-clock-o fa-fw"></i> {{$adminMessage['timeString']}}
                                        </small>

                                        <p>
                                            {{$adminMessage['message']}}
                                        </p>
                                    </div>
                                </li>
                            @endforeach
                        @else
                            <li class="left clearfix">

                                <div class="header">
                                    No Chat Available!
                                </div>
                            </li>
                        @endif

                    </ul>
                </div>
                <!-- /.panel-body -->

            </div>
            <!-- /.panel .chat-panel -->
        </div>
        <!-- /.col-lg-4 -->
    </div>
    <!-- /.row -->




@endsection

