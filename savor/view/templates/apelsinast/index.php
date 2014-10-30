<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?=$sHead?>
</head>
<body>

<div id="plotis">

	<div id="kaire">
		<div class="skalpas">
			<a href="<?=adresas()?>" title="<?=adresas()?>">
				<div class="logo"></div>
			</a>

			<div class="sonas2"></div>
		</div>
		<?=Blocks('L')?>
	</div>

	<div id="kunas">
		<div id="meniu_juosta">
			<ul>
				<?=Menu(8)?>
			</ul>
		</div>

		<div id="centras">
			<?=Blocks('C')?>
		</div>

		<div id="desine">
			<?=Blocks('R')?>
		</div>

		<div class="sonas"></div>
		<div id="kojos">
			<div class="tekstas"><?=$sCopyRight ?></div>
			<a href="http://mightmedia.lt" target="_blank" title="Mightmedia">
				<div class="logo"></div>
			</a>
		</div>
	</div>
</div>
<div id="another" class="clear">
	<div class="lygiuojam">
		<div class="taisom"></div>
	</div>
</div>
</body>
</html>