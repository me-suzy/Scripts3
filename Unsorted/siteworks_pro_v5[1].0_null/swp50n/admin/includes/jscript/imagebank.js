
	<script language="JavaScript">
	
		/*
			This function will allow us to insert an image, check for
			images to upload, etc.		
		*/
		
		function ConfirmDelImage(ImageId)
		{
			if(confirm('WARNING: You are about to permanently delete this image.'))
			{
				document.location.href = 'imagebank.php?strMethod=deleteImage&imageId='+ImageId;
			}
		}
		
		function insertImage(ImageId, SiteURL)
		{
			parent.opener.iView.focus();
			parent.window.close();
			parent.opener.iView.document.execCommand('insertimage', false, SiteURL+'/imageview.php?imageId='+ImageId);

		}
	
	</script>