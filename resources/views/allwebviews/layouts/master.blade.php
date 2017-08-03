<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin:@yield('title')</title>


    <!--Page specific styles-->
    @yield('styles')

</head>

<body>

@yield('content')


<!-- js placed at the end of the document so the pages load faster -->
<!--common script for all pages-->

<!--page specific scripts-->
@yield('scripts')


</body>
</html>