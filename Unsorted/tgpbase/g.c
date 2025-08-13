#include <iostream.h>
#include <fstream.h>
#include <stdio.h>
#include <string>
#include <sys/file.h>
#include <time.h>
#include <math.h>

string * catrecords = new string[1];
string * seenrecords = new string[1];
int growArray(int len,string newdata);
int growCookie(int len,string newdata);
string getcookie(string location);
int returnintegerlength(int i);

char x2c(char *what) {
    register char digit;

    digit = (what[0] >= 'A' ? ((what[0] & 0xdf) - 'A')+10 : (what[0] -
'0'));
    digit *= 16;
    digit += (what[1] >= 'A' ? ((what[1] & 0xdf) - 'A')+10 : (what[1] -
'0'));
    return(digit);
}


void unescape_url(char *url) {
    register int x,y;

    for(x=0,y=0;url[y];++x,++y) {
        if((url[x] = url[y]) == '%') {
            url[x] = x2c(&url[y+1]);
            y+=2;
        }
    } url[x] = '\0';
}

int growArray(int len,string newdata)
{
	int i=0;

	string * temp = new string[len];
	for (i=0; i<(len-1); i++)
	{
		temp[i] = catrecords[i];
	}

	temp[len-1] = newdata;

	catrecords = temp;

	return 1;
}

int growCookie(int len,string newdata)
{
	int i=0;

	string * temp = new string[len];
	for (i=0; i<(len-1); i++)
	{
		temp[i] = seenrecords[i];
	}

	temp[len-1] = newdata;

	seenrecords = temp;

	return 1;
}

int main()
{

	char * s;
	string query;
	char galleryid[255];
	char recipid[255];
	int count=0;
	char temp[255];
	int charcount=0;
	FILE *fp;
	int failed = 0;
	string strings;
	int i=0;
	string qs;

	int cookielen=0;

	// get which cookies are available for the user
	if (getenv("HTTP_COOKIE") != NULL)
	{ qs = getenv("HTTP_COOKIE");}
	int tempint;

	if (qs.length()>1)
	{
		while ((tempint = qs.find(";"))>-1)
		{
			while (qs[0] == ' ') { qs = qs.substr(1,qs.length()); }
			string thiscookie = qs.substr(0,tempint);
			int eqlsposition = thiscookie.find("=");
			string cookiename = thiscookie.substr(0,eqlsposition);
			string cookievalue = thiscookie.substr(eqlsposition+1,thiscookie.length());
			cookielen++;
			growCookie(cookielen,cookiename);
			qs = qs.substr(tempint+1,qs.length());
		}

		tempint = qs.find(";");
		while (qs[0] == ' ') { qs = qs.substr(1,qs.length()); }
		string thiscookie = qs.substr(0,tempint);
		int eqlsposition = thiscookie.find("=");
		string cookiename = thiscookie.substr(0,eqlsposition);
		string cookievalue = thiscookie.substr(eqlsposition+1,thiscookie.length());
		cookielen++;
		growCookie(cookielen,cookiename);
		qs = qs.substr(tempint+1,qs.length());
	}	



	//read the stupid configuration file :D
	char buffer2[256];
    ifstream configfile ("vars.cgi");
    if (! configfile.is_open())
    { cout << "Error opening file"; exit (1); }

	char tempbuf[255];
	charcount = 0;

    while (! configfile.eof() )
	{
		configfile.getline (buffer2,100);
		if ((buffer2[0] == '$')&&(buffer2[1] == 'b')&&(buffer2[2] == 'a')&&(buffer2[3] == 'd'))
		{
			for(int f=0;f<strlen(buffer2);f++)
			{
				if ( (f>5) && (f<strlen(buffer2)-2) )
				{
					tempbuf[charcount] = buffer2[f];
					charcount++;
				}
			}
		}
	}

	tempbuf[charcount]='\0';
	string badurl;
	badurl = tempbuf;

	int bad = 0;

	if ((s=getenv("QUERY_STRING")) != NULL) 
	{ 
		query = s;
	}

	//query = "bernard";

	if (query.length() <1)
	{
		cout << "Location: " << badurl << endl << endl;
		bad =1;
	}

	if (bad == 0)
	{
		char buffer[1024];
		ifstream examplefile ("hahadatabase.txt");
		if (! examplefile.is_open())
		{ cout << "Error opening file"; exit (1); }

		int len=0;
		int tempint = 0;
		int tempin2 = 0;
		string code;
		string url;
		string category;
		int found = 0;
		
		while (! examplefile.eof() )
		{
			examplefile.getline (buffer,1024);
			strings = buffer;
			category="";
			tempint = strings.find("|");
			code = strings.substr(0,tempint);
			strings = strings.substr(tempint+1,strings.length());
			tempint = strings.find("|");
			url = strings.substr(0,tempint);
			strings = strings.substr(tempint+1,strings.length()-1);
			category = strings.substr(0,strings.length());
		
			if (code == query)
			{
				found = 1;
				break;
			}

			if (category == query)
			{
				// check that the user hasnt already seen
				// this gallery
				// if they have then dont add it
				// as a possible gallery for them to see :D
				int ok =1;

				int where=  0;
				string newurl;
				newurl = url;
				while ((where=newurl.find("="))>-1)
				{
					newurl.replace(where,1,"-");
				}

				if (cookielen>0)
				{
					for(int i=0;i<cookielen;i++)
					{
						if (ok==1)
						{
							string tempstr = seenrecords[i];
							if (tempstr == newurl)
							{
								ok = 0;
							}
						}
					}
				}
				if (ok ==1)
				{
					len++;
					growArray(len,url);
				}
			}

		}

		if ( (len > 0) && (found == 0) )
		{
			int randomnumber = 0;
			srand ( time(NULL) );
			randomnumber = (rand()%len); 
			url = catrecords[randomnumber];
			if (url.length() > 2)
			{
				found = 1;
			}

		}

		if (found == 1)
		{
			getcookie(url);
			cout << "Location: " << url << endl << endl;
		}
		else
		{
			getcookie(badurl);
			cout << "Location: " << badurl << endl << endl;
		}
	}


}

string getcookie(string location)
{
	int where=  0;
	while ((where=location.find("="))>-1)
	{
		location.replace(where,1,"-");
	}

	time_t rawtime;
    struct tm * timeinfo;
	time ( &rawtime );
	rawtime = rawtime+86400;
    timeinfo = localtime ( &rawtime );

	string wdays[] = {"Sun","Mon","Tue","Wed","Thu","Fri","Sat"};

	string mons[] = {
		"Jan",
		"Feb",
		"Mar",
		"Apr",
		"May",
	    "Jun",
		"Jul",
		"Aug",
		"Sep",
		"Oct",
		"Nov",
		"Dec"

	} ;

	int hours = timeinfo->tm_hour+1;

	int minutes = timeinfo->tm_min;
	int seconds = timeinfo->tm_sec;

	cout << "Set-Cookie: " << location << "=out; Expires=" << wdays[timeinfo->tm_wday] << ", " << timeinfo->tm_mday << "-" << mons[timeinfo->tm_mon] << "-" << (timeinfo->tm_year+1900) << " ";

	if (returnintegerlength(hours)<2)
	{  cout << "0" << hours; } else { cout << hours; }
	cout << ":";
	if (returnintegerlength(minutes)<2)
	{  cout << "0" << minutes; } else { cout << minutes; }
	cout << ":";
	if (returnintegerlength(seconds)<2)
	{  cout << "0" << seconds; } else { cout << seconds; }

	cout << " GMT; path=/" << endl;

	return "";

}


int returnintegerlength(int i)
{
		int len;
		len=(i==0)?1:(int)floor(log10(10*i));
		return len;
}