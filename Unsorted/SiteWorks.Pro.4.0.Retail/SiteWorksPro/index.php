<?php include_once("templates/top.php"); ?>

	<!-- Start Main Content -->
	<div align="center">
		<center>
		<table width="96%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="100%" colspan="2" class="BodyHeader2">
					<br><span class="BodyHeading">Recent Articles</span><br>&nbsp;
				</td>
			</tr>
			<?php ShowRecentArticles(10); ?>
		</table>
		</center>
	</div>
	<!-- End Main Content -->

<?php include_once("templates/bottom.php"); ?>
