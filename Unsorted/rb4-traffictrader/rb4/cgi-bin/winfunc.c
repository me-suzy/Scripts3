// FCJ TRAFFIC MANAGEMENT
// 
// WARRANTY DISCLAIMER AND COPYRIGHT NOTICE
// THE DBA STYLUS MAKES NO REPRESENTATION ABOUT THE SUITABILITY OR
// ACCURACY OF THIS SOFTWARE OR DATA FOR ANY PURPOSE, AND MAKES NO WARRANTIES,
// EITHER EXPRESS OR IMPLIED, INCLUDING MERCHANTABILITY AND FITNESS
// FOR A PARTICULAR PURPOSE OR THAT THE USE OF THIS SOFTWARE OR
// DATA WILL NOT INFRINGE ANY THIRD PARTY PATENTS, COPYRIGHTS,
// TRADEMARKS, OR OTHER RIGHTS. THE SOFTWARE AND DATA ARE PROVIDED "AS IS".
// 
// Licensee is not allowed to distribute the binary and source code (if released)
// to third parties. Licensee is not allowed to reverse engineer, disassemble or
// decompile code, or make any modifications of the binary or source code,
// remove or alter any trademark, logo, copyright or other proprietary notices,
// legends, symbols, or labels in the Software.
// Licensee is not allowed to sub-license the Software or any derivative
// work based on or derived from the Software.
// 
// Copyright c 2001, 2002 by DBA STYLUS (USA)
// All Rights Reserved




int chrcnt(char *str, char cc){
	int count = 0;
	long i = 0;
	while (str[i] != 0){
		if(str[i++] == cc){ count ++;}
		}
return count;
	}







long substr(char *big, char *small){
	long i, j, lens,lenb;

	lens = strlen(small);
	lenb = strlen( big );

	for (i = 0; i < lenb; i++){
		if(big [i] == small [0]){
			j = 0;
			while (small[j] == big [i+j]){
				j++;
				}
			if( j >= lens ){
				return i;
				}
			}
		}
return (-1);
	}





char *strlww(char *s){
	long i, len;

	len = strlen (s);
	for(i = 0; i < len; i++){
		if ((s[i] >= 'A') && (s[i] <= 'Z')) s[i] = s[i] + 32;
		}
return s;
	}








char * charless (char *str, char cc){
	long len, i, j;
        char *nn;

	len = strlen(str);
	nn = (char *) malloc (len - chrcnt(str, cc) + 1);
	j = 0;
	for(i = 0; i < len; i++){
		if (str [i] != cc){
			nn[j++] = str[i];
			}
		}
	nn [j] = 0;
return nn;
	}









char *strcpp(char *ss, long frm, long len){
	char *nn;
	long i;
	nn = (char *) malloc (len + 1);
	for (i = 0; i < len; i ++) {
		nn[i] = ss[frm];
		frm ++;
		}
	nn [len] = 0;
return nn;
	}



void strctt(char *inn, char *into, long frm, long offset, long spot){
	long i = 0;
	for(i = 0; i < offset; i ++){
		into [i + spot] = inn [i + frm];
		}
	}


char *strdel (char *str, long frm, long offset){
	char *tmp;
	long leno, lenn;
	leno = strlen(str);
	lenn = leno - offset;
	tmp = (char *) malloc (lenn + 1);
	strctt(str, tmp, 0,  frm, 0);
	strctt(str, tmp, frm + offset, lenn - frm, frm);
	tmp [lenn] = 0;
	strcpy (str ,tmp);
	free (tmp);
return str;
	}




char *clrdot(char *s){
	int dotno, dotcount;
	long i, len;
	char *nn, *tmp;

	dotno = chrcnt (s, '.');
	dotcount = 0;
	i = 0;
	while (dotcount < (dotno - 1)){
		while (s[i++] != '.');
		dotcount ++;
		}
	len = strlen(s);
	nn = strcpp(s, i, len-i);
	strcpy (s, nn);
	free (nn);
return s;
	}


char *movslash(char *s){
	long len, frm;
	len = strlen(s);
	frm = substr(s, "/");
	if (frm != (-1) )
		s = strdel(s, frm, len-frm);
return s;
	}



char *coolsmb(char *s){
	long len, i,j;
	char *tmp;

	len = strlen(s);
	tmp = (char *) malloc(len + 1);
	j = 0;
	for ( i = 0; i < len; i++)
   		if(
			((s[i] <= 'z') && (s [i] >= 'a' ))
			||
			(s[i] == '-')
			||
			(s[i] == '.')
			||
			((s[i] <= '9') && (s [i] >= '0' ))
		)
	tmp [j++] = s[i];
	strcpy (s, tmp);
	s[j] = 0;
return s;
	}


char *dstrip(char *s){
	long tmp;
	char *url;
        url = (char *) malloc(strlen(s) + 1);
	strcpy(url, s);

	strlww(url);
	tmp = substr (url, "http://");
	if( tmp != (-1) ) strdel (url, tmp, 7);
	movslash(url);
	clrdot (url);
	coolsmb(url);
return url;
	}



