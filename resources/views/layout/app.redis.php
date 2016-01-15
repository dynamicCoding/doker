<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,user-scalable=no">
	<title>{! $title !}</title>
	{! link_tag_css() !}
	{! script_tag() !}
</head>
<body>
<header></header>
<main role="main">
	<section class="row">
		<div class="x12">
			@yield('content');
		</div>
	</section>
</main>
<footer></footer>
</html>