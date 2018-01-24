<html>
    <head>
        <title>JDLBox Downloads - @yield('title')</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" type="image/png" href="img/box.png" />
	@section('topjs')
	@show
	@section('css')
	@show
    </head>
    <body>
        <div class="container">
            @yield('content') 
		<div class="card">
			<div class="card-body">
	    			@yield('footer')
			</div>
		</div>
	</div>
	<!-- scripts -->
	@section('js')
	@show
    </body>
</html>
