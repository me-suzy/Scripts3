var inputboxwidth;

// size the textarea box to the screen width
if(document.all)
{
	if(screen.width == 800)
	{
		inputboxwidth=70;
	}
	else if(screen.width == 1024)
	{
		inputboxwidth=90;
	}
	else if(screen.width == 1152)
	{
		inputboxwidth=120;
	}
	else if(screen.width == 1280)
	{
		inputboxwidth=140;
	}
	else
	{
		inputboxwidth = Math.round(screen.width / 15);
	}
}
else
{
	if(screen.width == 800)
	{
		inputboxwidth=65;
	}
	else if(screen.width == 1024)
	{
		inputboxwidth=100;
	}
	else if(screen.width == 1152)
	{
		inputboxwidth=120;
	}
	else if(screen.width == 1280)
	{
		inputboxwidth=140;
	}
	else
	{
		inputboxwidth = Math.round(screen.width / 15);
	}
}