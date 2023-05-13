<!doctype html>

<html >

<head>
	<meta name="csrf-token" content="{{ csrf_token() }}">
	

	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <meta name="app-url" content="{{url('app')}}">
	<meta name="file-base-url" content="{{url('app')}}">

	<!-- Favicon -->
	<link rel="icon" >
	<title>Admin</title>
	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> -->
	<!-- google font -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700">
	
	<!-- aiz core css -->
	<link rel="stylesheet" href="http://localhost/admin2/public/assets/css/vendors.css">

	<link rel="stylesheet" href="http://localhost/admin2/public/assets/css/aiz-core.css">

    <style>
        body {
            font-size: 12px;
        }
    </style>
	<script>
    	var AIZ = AIZ || {};
        AIZ.local = {
            nothing_selected: 'Nothing selected',
            nothing_found: 'Nothing found',
            choose_file: 'Choose file',
            file_selected: 'File selected',
            files_selected: 'Files selected',
            add_more_files: 'Add more files',
            adding_more_files: 'Adding more files',
            drop_files_here_paste_or: 'Drop files here, paste or',
            browse: 'Browse',
            upload_complete: 'Upload complete',
            upload_paused: 'Upload paused',
            resume_upload: 'Resume upload',
            pause_upload: 'Pause upload',
            retry_upload: 'Retry upload',
            cancel_upload: 'Cancel upload',
            uploading: 'Uploading',
            processing: 'Processing',
            complete: 'Complete',
            file: 'File',
            files: 'Files',
        }
	</script>

</head>
<body class="">

	<div class="aiz-main-wrapper">
        @include('inc.admin_sidenav')
		<div class="aiz-content-wrapper">
            @include('inc.admin_nav')
			<div class="aiz-main-content">
				<div class="px-15px px-lg-25px">
                    @yield('content')
				</div>
				
			</div><!-- .aiz-main-content -->
		</div><!-- .aiz-content-wrapper -->
	</div><!-- .aiz-main-wrapper -->

    @yield('modal')


	<script src="http://localhost/admin2/public/assets/js/vendors.js" ></script>
	<script src="http://localhost/admin2/public/assets/js/aiz-core.js" ></script>

    @yield('script')

    <script type="text/javascript">
	    @foreach (session('flash_notification', collect())->toArray() as $message)
	        AIZ.plugins.notify('{{ $message['level'] }}', '{{ $message['message'] }}');
	    @endforeach
    </script>

</body>
</html>
