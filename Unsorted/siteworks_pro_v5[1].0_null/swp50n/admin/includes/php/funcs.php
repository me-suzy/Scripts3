<?php
///////////////////////////////////////////////////////////////////////////////
//                                                                           //
//   Program Name         : SiteWorks Professional                           //
//   Release Version      : 5.0                                              //
//   Program Author       : SiteCubed Pty. Ltd.                              //
//   Supplied by          : CyKuH [WTN]                                      //
//   Nullified by         : CyKuH [WTN]                                      //
//   Packaged by          : WTN Team                                         //
//   Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                           //
//                       WTN Team `2000 - `2002                              //
///////////////////////////////////////////////////////////////////////////////
        function PrintWYSIWYGTable($frameContent = "", $width = "415px", $height = "205px")
        {
                /*
                        Function Name: PrintWYSIWYGTable()
                        Paramaters: N/A
                        Desc: Prints the buttons that the user can use to format content
                                  in the WYSIWYG iFrame used throughout the app
                */

                ?>

                        <style>

                          .butClass
                          {
                            border: 1px solid;
                            border-color: #D6D3CE;
                          }

                          .tdClass
                          {
                            padding-left: 3px;
                            padding-top:3px;
                          }

                        </style>

                                <table id="tblCtrls" width="<?php echo $width; ?>" height="30px" border="0" cellspacing="0" cellpadding="0" bgcolor="#D6D3CE">
                                <tr>
                                        <td class="tdClass">
                                                <img alt="Bold" class="butClass" src="bold.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doBold()">
                                                <img alt="Italic" class="butClass" src="italic.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doItalic()">
                                                <img alt="Underline" class="butClass" src="underline.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doUnderline()">

                                                <img alt="Left" class="butClass" src="left.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doLeft()">
                                                <img alt="Center" class="butClass" src="center.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doCenter()">
                                                <img alt="Right" class="butClass" src="right.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doRight()">

                                                <img alt="Ordered List" class="butClass" src="ordlist.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doOrdList()">
                                                <img alt="Bulleted List" class="butClass" src="bullist.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doBulList()">

                                                <img alt="Text Color" class="butClass" src="forecol.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doForeCol()">
                                                <img alt="Background Color" class="butClass" src="bgcol.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doBackCol()">

                                                <img alt="Hyperlink" class="butClass" src="link.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doLink()">
                                                <img alt="Image" class="butClass" src="image.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doImage()">
                                                <img alt="Horizontal Rule" class="butClass" src="rule.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doRule()">

                                        </td>
                                </tr>
                                </table>
                                <iframe name="iView" id="iView" style="width: <?php echo $width; ?>; height:<?php echo $height; ?>;"></iframe>
                                <input type="hidden" name="__data" value="">
                            <table width="<?php echo $width; ?>" height="30px" border="0" cellspacing="0" cellpadding="0" bgcolor="#D6D3CE">
                            <tr>
                                        <td class="tdClass" colspan="1" width="80%">
                                          <select style="font-family:verdana; font-size: 8pt" name="selFont" onChange="doFont(this.options[this.selectedIndex].value); this.selectedIndex = 0;">
                                            <option value="">-- Font --</option>
                                            <option value="Arial">Arial</option>
                                            <option value="Courier">Courier</option>
                                            <option value="Sans Serif">Sans Serif</option>
                                            <option value="Tahoma">Tahoma</option>
                                            <option value="Verdana">Verdana</option>
                                            <option value="Wingdings">Wingdings</option>
                                          </select>
                                          <select style="font-family:verdana; font-size: 8pt" name="selSize" onChange="doSize(this.options[this.selectedIndex].value); this.selectedIndex = 0;">
                                            <option value="">-- Size --</option>
                                            <option value="1">Very Small</option>
                                            <option value="2">Small</option>
                                            <option value="3">Medium</option>
                                            <option value="4">Large</option>
                                            <option value="5">Larger</option>
                                            <option value="6">Very Large</option>
                                          </select>
                                          <select style="font-family:verdana; font-size: 8pt" name="selHeading" onChange="doHead(this.options[this.selectedIndex].value); this.selectedIndex = 0;">
                                            <option value="">-- Heading --</option>
                                            <option value="Heading 1">H1</option>
                                            <option value="Heading 2">H2</option>
                                            <option value="Heading 3">H3</option>
                                            <option value="Heading 4">H4</option>
                                            <option value="Heading 5">H5</option>
                                            <option value="Heading 6">H6</option>
                                          </select>
                                        </td>
                                        <td class="tdClass" colspan="1" width="20%" align="right">
                                          <img id="toggleBut" alt="Toggle Mode (to code)" class="butClass" src="mode.gif" onMouseOver="selOn(this)" onMouseOut="selOff(this)" onMouseDown="selDown(this)" onMouseUp="selUp(this)" onClick="doToggleView()">
                                          &nbsp;&nbsp;&nbsp;
                                        </td>
                            </tr>
                            </table>

                        <script language="JavaScript">

                          var viewMode = 1; // WYSIWYG

                          function Init()
                          {
                            iView.document.designMode = "On";
                                iHTML = "<?php echo str_replace(chr(13) . chr(10), "\\r\\n", str_replace("&quot;", "\\\"", str_replace("\"", "&quot;", $frameContent))); ?>";

                                // Set a timeout to set the iFrames text. It can't be set
                                // directly (weird), but a timeout works.
                                setTimeout("iView.document.body.innerHTML = iHTML;", 0);
                          }

                            function closeWindows()
                          {
                                if(colorWin)
                                        colorWin.close();

                                if(imageWin)
                                        imageWin.close();
                          }

                          function selOn(ctrl)
                          {
                                ctrl.style.borderColor = '#000000';
                                ctrl.style.backgroundColor = '#B5BED6';
                                ctrl.style.cursor = 'hand';
                          }

                          function selOff(ctrl)
                          {
                                ctrl.style.borderColor = '#D6D3CE';
                                ctrl.style.backgroundColor = '#D6D3CE';
                          }

                          function selDown(ctrl)
                          {
                                ctrl.style.backgroundColor = '#8492B5';
                          }

                          function selUp(ctrl)
                          {
                            ctrl.style.backgroundColor = '#B5BED6';
                          }

                          function doBold()
                          {
                                iView.document.execCommand('bold', false, null);
                          }

                          function doItalic()
                          {
                                iView.document.execCommand('italic', false, null);
                          }

                          function doUnderline()
                          {
                                iView.document.execCommand('underline', false, null);
                          }

                          function doLeft()
                          {
                            iView.document.execCommand('justifyleft', false, null);
                          }

                          function doCenter()
                          {
                            iView.document.execCommand('justifycenter', false, null);
                          }

                          function doRight()
                          {
                            iView.document.execCommand('justifyright', false, null);
                          }

                          function doOrdList()
                          {
                            iView.document.execCommand('insertorderedlist', false, null);
                          }

                          function doBulList()
                          {
                            iView.document.execCommand('insertunorderedlist', false, null);
                          }

                          function doForeCol()
                          {
                            var left = (screen.availWidth/2) - (293/2);
                            var top = (screen.availHeight/2) - (100/2);

                            closeWindows();

                            document.all.__data.value = 'foreColor';
                            colorWin = window.open("colors.html", 'colors', 'scrollbars=0, toolbar=0, statusbar=0, width=293, height=100, left='+left+', top='+top);
                          }

                          function doBackCol()
                          {
                            var left = (screen.availWidth/2) - (293/2);
                            var top = (screen.availHeight/2) - (100/2);

                            closeWindows();

                            document.all.__data.value = 'backColor';
                            colorWin = window.open('colors.html', 'colors', 'scrollbars=0, toolbar=0, statusbar=0, width=293, height=100, left='+left+', top='+top);
                          }

                          function doLink()
                          {
                            iView.document.execCommand('createlink');
                          }

                          function OpenWin(URL, winName, width, height, scroll)
                          {
                                  var winLeft = (screen.width - width) / 2;
                                var winTop = (screen.height - height) / 2;

                                winData = 'height='+height+',width='+width+',top='+winTop+',left='+winLeft+',scrollbars='+scroll+',resizable';
                                win = window.open(URL, winName, winData);

                                if (parseInt(navigator.appVersion) >= 4) { win.window.focus(); }
                          }

                          function doImage()
                          {
                            var left = (screen.availWidth/2) - (560/2);
                            var top = (screen.availHeight/2) - (505/2);

                            closeWindows();

                            imageWin = window.open('imagebank.php', 'images', 'scrollbars=1, toolbar=0, statusbar=0, width=560, height=505, left='+left+', top='+top);
                          }

                          function doRule()
                          {
                            iView.document.execCommand('inserthorizontalrule', false, null);
                          }

                          function doFont(fName)
                          {
                            if(fName != '')
                              iView.document.execCommand('fontname', false, fName);
                          }

                          function doSize(fSize)
                          {
                            if(fSize != '')
                              iView.document.execCommand('fontsize', false, fSize);
                          }

                          function doHead(hType)
                          {
                            if(hType != '')
                            {
                              iView.document.execCommand('formatblock', false, hType);
                            }
                          }

                          function doToggleView()
                          {
                            if(document.all.toggleBut.alt == "Toggle Mode (to code)")
                            {
                              iHTML = iView.document.body.innerHTML;
                              iView.document.body.innerText = iHTML;
                              document.all.toggleBut.alt = "Toggle Mode (to WYSIWYG)";

                              // Hide all controls
                              tblCtrls.style.display = 'none';
                              document.all.selFont.style.display = 'none';
                              document.all.selSize.style.display = 'none';
                              document.all.selHeading.style.display = 'none';

                              iView.focus();

                            }
                            else
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
                          }

                          // Initialize the iFrame
                          Init();

                        </script>


                <?php
        }

     function MakeDate($Date)
        {
                return substr($Date, 4, 2) . "/" . substr($Date, 6, 2) . "/" . substr($Date, 0, 4);
        }







?>