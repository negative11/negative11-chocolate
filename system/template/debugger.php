<html>
	<head>
		<title>Framework Error</title>
		<style type="text/css">
		.main
		{
			width:65%;
			margin:50px auto 50px auto;
			line-height:1.8em;
		}
		h1, h2
		{
			border-bottom:1px dashed #ccc;
			padding-bottom:.5em;
		}
		</style>
	</head>
	<body>
		<div class="main">
			<h1>Framework Error</h1>
			<?php
			// Shield error output in production mode.
			if (IN_PRODUCTION)
			{
				?>
				<p style="color:#bb0000;font-weight:bold;">An error has occurred. Please contact the site administrator for further assistance.</p>
				<?php
				exit;
			}
			?>			
			<p>Message: <strong><?=$message;?></strong>
			<br/>
			Line Number: <?=$line_number;?>
			<br/>
			File Name: <?=$file_name;?>
			<br/>
			Type: <?=$error_type;?>
			</p>
			<h2>Backtrace</h2>
		</div>
		<?Core::dump($backtrace);?>
	</body>
</html>