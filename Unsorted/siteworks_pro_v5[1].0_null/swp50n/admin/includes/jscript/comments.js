
	<script language="JavaScript">

		/*
			Filename: comments.js
			Desc: Handles all of the client side form
			      validation and data manipulation routines
		*/

			
		function ConfirmDelComments()
			{
				if(confirm('WARNING: This will permanently remove the selected comment(s) from the database. Click OK to continue.'))
					return true;
				else
					return false;
			}
			
	</script>