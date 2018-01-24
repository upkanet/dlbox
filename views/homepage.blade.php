@extends('layouts.master')

@section('title',$directory->name)

@section('css')
	@parent
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
	<!-- Bootstrap 4.0 Beta -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha256-m/h/cUDAhf6/iBRixTbuc8+Rg2cIETQtPcH9D3p2Kg0=" crossorigin="anonymous" />
	<!-- open-iconic-bootstrap (icon set for bootstrap) -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/open-iconic/1.1.1/font/css/open-iconic-bootstrap.min.css" integrity="sha256-BJ/G+e+y7bQdrYkS2RBTyNfBHpA9IuGaPmf9htub5MQ=" crossorigin="anonymous" />
@endsection

@section('content')
	<button id="AddMagnetBtn" class="btn btn-primary">Add Magnet</button> 
	<button id="DownloadsBtn" class="btn btn-primary" type="button" data-toggle="collapse" data-target="#DownloadList">Downloads (0)</button> 
	<button class="btn btn-primary">Load Subtitles</button> 
	@if(isset($alert))
	@section('js')
		@parent
		<script>
			$(function(){
				sendAlert("{{$alert['type']}}", "{{$alert['message']}}");
			});
		</script>
	@endsection
	@endif
	<div id="AlertArea"></div>
	
	<div class="collapse" id="DownloadList">
		<a href="http://{{$_SERVER['HTTP_HOST']}}:8112" target="_blank">Deluge</a>
		<table class="table table-striped" id="TorList">
		</table>
	</div>

	<!-- Large Display Nav -->
	<nav class="d-none d-md-block" aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"></li>
			@foreach($directory->nav as $d => $sp)
				@if($d == $directory->name)
				<li class="breadcrumb-item active" aria-current="page">{{$d}}</li>
				@else
				<li class="breadcrumb-item"><a href="?dir={{$sp}}">{{$d}}</a></li>
				@endif
			@endforeach
		</ol>
	</nav>
	<!-- Small Display Nav -->
	<nav class="d-block d-md-none" aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item"></li>
			@foreach($directory->shortnav as $d => $sp)
				@if($d == $directory->name)
				<li class="breadcrumb-item active" aria-current="page">{{$d}}</li>
				@else
				<li class="breadcrumb-item"><a href="?dir={{$sp}}">{{$d}}</a></li>
				@endif
			@endforeach
		</ol>
	</nav>

	<table class="table table-striped">
	@foreach($directory->files as $f)
		<tr>
		@if($f->isdir)
			<td><a href="?dir={{$directory->shortpath}}/{{$f->name}}"><span class="oi oi-folder"></span> <span class="d-none d-md-inline">{{$f->basename}}</span><span class="d-inline d-md-none">{{$f->shortname}}</span></a></td>
			<td></td>
			<td></td>
		@else
			@if($f->istvshow)
				<td><a href="{{$f->shortpath}}" title="{{$f->basename}}"><span class="oi oi-{{$f->icon}}"></span> {{$f->tvshow['name']}} {{$f->tvshow['season']}}x{{$f->tvshow['episode']}}</a></td>
			@else
				<td><a href="{{$f->shortpath}}"><span class="oi oi-{{$f->icon}}"></span> <span class="d-none d-md-inline">{{$f->basename}}</span><span class="d-inline d-md-none">{{$f->shortname}}</span></a></td>
			@endif
			<td>
			@if($f->type == "video")
				<a href="javascript:showVideo('{{$f->shortpath}}');"><span class="oi oi-video"></span> Watch</a>
			@endif
			</td>
			<td>{{$f->size}}</td>
		@endif
		@if($f->name != "..")
			<td><a href="javascript:deleteFile('{{$directory->shortpath}}','{{$f->shortpath}}','{{$f->basename}}');"><span class="oi oi-delete"></span></a></td>
		@else
			<td></td>
		@endif
		</tr>
	@endforeach
	</table>
	@include('videosection')
@endsection

@section('footer')
<a href="login-form.php">Log Out</a> | {{$freespace}} | DLBox &copy;2018
@endsection

@section('js')
	@parent
	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<script src="js/dlbox.js"></script>
@endsection
