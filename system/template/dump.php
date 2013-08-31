<div style="background-color:#bcd;width:65%;border:2px solid #000;padding:10px;margin:10px auto 10px auto;font-family:courier;">
<strong>Core Dump
<br/>
Generated <?php echo date('r');?></strong>
<br/>
<strong>Total Objects:<?php echo $count;?></strong>
<hr/>
<?php
$styles = array('background-color:#fff;', 'background-color:#ddd;');
$current = 0;
foreach ($data as $datum)
{
	$current = $current == 2 ? 0 : $current;
	print '<pre style="'.$styles[$current++].'border:1px solid black;padding:10px;overflow:auto;">';
	var_dump($datum);
	print '</pre>';
}
?>
</div>