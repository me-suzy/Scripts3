   <!-- START of header.php -->
		<!-- start of imported menu script -->
		<!-- This script and many more are available free online at -->
		<!-- The JavaScript Source!! http://javascript.internet.com -->
		<!-- Original:  Younes Bouab (bouaby@hotmail.com ) -->
		<!-- Web Site:  http://www.SUPEReDITION.com -->
		<script language="javascript" src="menuConfig.js"></script>
		<script language="javascript" src="menu.js"></script>
		<!-- end of imported menu script -->

<?php
if(ORGANIZATION=="DreamRiver") {
	echo"<script language='javascript' src='dr_nav.js'></script>";
}else{
	echo"<script language='javascript' src='nav_dist.js'></script>";
}?>


	<table class="headerColor" width="100%" border=0 cellpadding=0 cellspacing=0>

		
		<tr class="favcolor2" width="100%">
			<td colspan=4 align="center">
	            <a href="<?php echo PHPCHECKOUTPAGE;?>">
					<div align="left"><b>Home</b></div>
				</a>
			</td>
		</tr>



		<tr align="left">
			<td colspan=3>
			<!-- this spans 3 of 4 cells in the row -->
			<table background="appimage/bow650x83.jpg" width="650" style="border-style:none;">
				<tr>
					<td valign="middle" align="left" style="font-size:12px;font-weight:bold;">
						<?php if (ORGANIZATION == "DreamRiver") :?>
				         <a href="<?php echo PHPCHECKOUTPAGE;?>">
							<img src="appimage/naUp.jpg" width="69" height="71" border=0 alt="Home">
							</a>
						<?php else:?>
							
						<?php endif;?>
					</td>
					<td valign="middle" colspan=2 align="right" style="font-size:52px;font-weight:bold;color:#F5F5F5;">
						<?php echo IMPLEMENTATIONNAME;?>&nbsp;<br>
						<span style="color:#696969;font-size:10px;font-weight:bold;">
							<?php echo "'" . BENEFIT . "'&nbsp;";?>
						</span>
					</td>
				</tr>
			</table>
			</td>
			<!-- this is the 4th cell in the row -->
			<td>
				<span style="color:silver;font-style:italic;font-weight:bold;font-size:12px;">
					<?php echo ORGANIZATION;?>
				</span>
			</td>
		</tr>


		<tr class="favcolor2" width="100%">
			<td colspan=2 align="left" style="color:silver;font-size:10px;font-weight:bold;">
					<?php echo "<i>'" . SLOGAN . "'</i>";?>
         </td>
			<td colspan=2 align="right" style="color:silver;font-size:8px;font-weight:bold;">
				<?php echo $todaysTextDate;?></td>
		</tr>
	</table>
   <!-- end of header.php -->