<script language="JavaScript">

  function ConfirmDelBook(bkId)
  {
    if(confirm('WARNING: You are about to permanently delete this eBook.'))
    {
      document.location.href = 'ebooks.php?strMethod=delete&bookId='+bkId;
    }  
  }

  function CheckAddBook()
  {
    var theForm = document.frmAddBook;

    if(theForm.strTitle.value == '')
    {
      alert('Please enter the title of this eBook');
      theForm.strTitle.focus();
      return false;
    }

    if(theForm.strURL.value == '')
    {
      alert('Please enter the URL to download this eBook');
      theForm.strURL.focus();
      return false;
    }

    return true;
  }

</script>