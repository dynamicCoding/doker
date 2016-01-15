<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,user-scalable=no">
	<title>{! $title !}</title>
	<style>
		.textp {
			background: #646464;
			padding: 15px 10px;
			font-size: 17px;
			border-radius: 15px;
			color: #fff;
		}
		.error {
			background: #077F9A; /*#6173F0;*/
			padding: 15px 10px;
			border-radius: 15px;
		}
		.error p {
			color: white;
		}
		.error p b {
			color: black;
		}
		.error div {
			text-decoration: underline;
			font-wight: bold;
			color: white;
		}
	</style>
</head>
<body>
<header>
	<center><p class="textp">{! $title_header !}</p></center>
</header>
<main role="main">
	<section class="row">
		<div class="col x12 error">
			@yield('error');
		</div>
	</section>
</main>
</html>