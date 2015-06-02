<!doctype html>
<html class="no-js" lang="en">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
 
	<title>@yield('title')</title>
 
	{{ HTML::style('assets/css/foundation.css') }}
	{{ HTML::style('assets/css/app.css') }}
	{{ HTML::script('assets/js/vendor/modernizr.js') }}
</head>
<body>
	<nav class="top-bar" data-topbar>
		<ul class="title-area">
			<li class="name">
				<h1>{{ link_to('/admin', 'Admin Panel', null, $secure = false) }}</h1>
			</li>
 
            <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
		</ul>
	</nav>
 
	@yield('content')
 
    {{ HTML::script('assets/js/functions.js') }}
	{{ HTML::script('assets/js/vendor/jquery.js') }}
	{{ HTML::script('assets/js/foundation.min.js') }}
	<script>
		$(document).foundation();
	</script>
 
  <script>
      @yield('end_scripts')
  </script>
</body>
</html>