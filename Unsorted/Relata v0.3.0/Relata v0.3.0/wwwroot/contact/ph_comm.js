var phcommbox_rows = 10;
var phcommbox_cols = 10;

if(screen.width == 800)
{
	phcommbox_rows = 20;
	phcommbox_cols = 35;
}
else if(screen.width == 1024)
{
	phcommbox_rows = 30;
	phcommbox_cols = 60;
}
else if(screen.width == 1152)
{
	phcommbox_rows = 30;
	phcommbox_cols = 70;
}
else if(screen.width == 1280)
{
	phcommbox_rows = 30;
	phcommbox_cols = 90;
}
else
{
	phcommbox_rows = Math.round(screen.width / 35);
	phcommbox_cols = Math.round(screen.width / 20);
}

// if we are using mozilla or n6.. make the textbox smaller
if(!document.all)
{
	phcommbox_rows = Math.round(phcommbox_rows - (phcommbox_rows * 0.2));
	phcommbox_cols = Math.round(phcommbox_cols - (phcommbox_cols * 0.4));
}
