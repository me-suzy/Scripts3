<script language=javascript>


function validRequired(formField,fieldLabel)
{
	var result = true;

	if (formField.value == "")
	{
		var alertText = '<?php print $this->textArray['Please enter a value for']; ?> ' + fieldLabel;
		alert(alertText);
		formField.focus();
		result = false;
	}

	return result;
}

function validEmail(formField,fieldLabel,required)
{
	var result = true;

	if (required && !validRequired(formField,fieldLabel))
		result = false;

	if (result && ((formField.value.length < 3) || !isEmailAddr(formField.value)) )
	{
	    var alertText = '<?php print $this->textArray['Please enter a complete email address in the form: yourname@yourdomain.com']; ?>';
		alert(alertText);
		formField.focus();
		result = false;
	}

  return result;

}

function confirmDelete(page){
    if (confirm('<?php print $this->textArray['Confirm Delete']; ?>')){
        window.location.href=page;
    }
}

function confirmIgnore(page){
    if (confirm('<?php print $this->textArray['Ignore'] . " " . ucfirst($this->textArray['user']) . "?"; ?>')){
        window.location.href=page;
    }
}
</script>
