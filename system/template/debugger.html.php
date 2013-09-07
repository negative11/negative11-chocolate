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
			<?php	if (IN_PRODUCTION): // Hide errors in production mode ?>
				<p style="color:#bb0000;font-weight:bold;">An error has occurred. Please contact the site administrator for further assistance.</p>
      <? else: ?>	
			<p style="color:#bb0000;font-weight:bold;"><strong><?php echo $message;?></strong></p>
			<p><strong>Line Number: </strong><?php echo $line_number;?></p>
			<p><strong>File Name: </strong><?php echo $file_name;?></p>
      <p><strong>Type: </strong><?php echo $error_type;?></p>
			<h2>Stack Trace</h2>
        <p>Displayed in reverse order.</p>
        <?php if (is_array($backtrace)): ?>
          <?php 
            $styles = array('background-color:#fff', 'background-color:#eee');
            $current = 0;
          ?>
          <?php foreach ($backtrace as $item): ?>
            <?php $current = $current == 2 ? 0 : $current; ?>
            <table style="<?php echo $styles[$current++] ?>;width:100%;overflow:auto;border-spacing:0;border-collapse:collapse;">
            <?php foreach ($item as $key => $value): ?>
              <tr >
                <td style="text-align:left;width:25%;border:1px solid lightgray;padding:10px;vertical-align:top;"><?php echo ucfirst($key) ?></td>
                <td style="text-align:left;border:1px solid lightgray;padding:10px;"><pre><?php print_r($value) ?></pre></td>
              </tr>
            <?php endforeach; ?>
            </table>
            <br>
          <?php endforeach ?>
        <?php endif ?>
      <?php endif ?>
		</div>
	</body>
</html>