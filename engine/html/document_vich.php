<style>
	@media print{
		@page {size: landscape}
		#tablevich
		{
			height: 100vh;
			/*height: 100%;*/
		}
	}
	#tablevich
	{
		height: 100vh;
	}
</style>
<div class="rotator">
	<table id="tablevich" width="100%" border="1" style="border-collapse: collapse;" cellpadding="5" cellspacing="5">
		<tr>
			<td width="50%" valign="middle">
				<?php
				include ( "engine/html/document_vich_vich.php" );
				?>
			</td>
			<td width="50%" valign="middle">
				<?php
				include ( "engine/html/document_vich_rw.php" );
				?>
			</td>
		</tr>
		<tr>
			<td width="50%" valign="middle">
				<?php
				include ( "engine/html/document_vich_vich.php" );
				?>
			</td>
			<td width="50%" valign="middle">
				<?php
				include ( "engine/html/document_vich_ekg.php" );
				?>
			</td>
		</tr>
	</table>
</div>
<br>
<!--<b>ПРИ РАСПЕЧАТКЕ БЛАНКОВ ПЕРЕВЕДИТЕ СТРАНИЦУ В АЛЬБОМНЫЙ ФОРМАТ!</b>-->