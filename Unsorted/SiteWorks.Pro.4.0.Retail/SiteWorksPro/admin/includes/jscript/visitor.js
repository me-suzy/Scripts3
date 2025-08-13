
	<script language="JavaScript">

		/*
			Filename: visitor.js
			Desc: Handles all of the client side form
			      validation and data manipulation routines
		*/
		
		function ConfirmDelVisitor(intVisitorId)
			{
				if(confirm('Warning: You are about to delete this user from the devArticles user database.'))
					{ document.location.href = 'visitors.php?strMethod=delete&uId='+intVisitorId; }			
			}	
	
	</script>