
        <script language="JavaScript">

                /*
                        Filename: personal
                        Desc: Handles all of the client side form
                              validation and data manipulation routines
                              for personal content, etc.
                */

                function CheckUpdateAbout()
                {
                        var thisForm = document.frmUpdateAbout;

                        if(document.all.toggleBut.alt != "Toggle Mode (to code)")
                        {
                                iText = iView.document.body.innerText;
                                iView.document.body.innerHTML = iText;
                                document.all.toggleBut.alt = "Toggle Mode (to code)";

                                // Show all controls
                                tblCtrls.style.display = 'inline';
                                document.all.selFont.style.display = 'inline';
                                document.all.selSize.style.display = 'inline';
                                document.all.selHeading.style.display = 'inline';

                                iView.focus();
                        }

                        thisForm.strAbout.value = iView.document.body.innerHTML;

                        if(thisForm.strAbout.value == '')
                        {
                                alert('You must enter content for the \'About Us\' field first.');
                                iView.focus();
                                return false;
                        }

                        return true;
                }

                function CheckUpdateContact()
                {
                        var thisForm = document.frmUpdateContact;

                        if(document.all.toggleBut.alt != "Toggle Mode (to code)")
                        {
                                iText = iView.document.body.innerText;
                                iView.document.body.innerHTML = iText;
                                document.all.toggleBut.alt = "Toggle Mode (to code)";

                                // Show all controls
                                tblCtrls.style.display = 'inline';
                                document.all.selFont.style.display = 'inline';
                                document.all.selSize.style.display = 'inline';
                                document.all.selHeading.style.display = 'inline';

                                iView.focus();
                        }

                        thisForm.strContact.value = iView.document.body.innerHTML;

                        if(thisForm.strContact.value == '')
                        {
                                alert('You must enter content for the \'Contact Us\' field first.');
                                iView.focus();
                                return false;
                        }

                        return true;
                }

            function CheckUpdatePrivacy()
                {
                        var thisForm = document.frmUpdatePrivacy;

                        if(document.all.toggleBut.alt != "Toggle Mode (to code)")
                        {
                                iText = iView.document.body.innerText;
                                iView.document.body.innerHTML = iText;
                                document.all.toggleBut.alt = "Toggle Mode (to code)";

                                // Show all controls
                                tblCtrls.style.display = 'inline';
                                document.all.selFont.style.display = 'inline';
                                document.all.selSize.style.display = 'inline';
                                document.all.selHeading.style.display = 'inline';

                                iView.focus();
                        }

                        thisForm.strPrivacy.value = iView.document.body.innerHTML;

                        if(thisForm.strPrivacy.value == '')
                        {
                                alert('You must enter content for the \'Privacy\' field first.');
                                iView.focus();
                                return false;
                        }

                        return true;
                }

        </script>