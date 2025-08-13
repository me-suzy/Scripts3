/*
 * tts-in.c - The TranSpecT Top Site incoming link CGI
 * 
 * Running in C, smokin' up the back alleys of pornmongers everywhere!
 *
 * Copyright 1997 - Rick Franchuk - TranSpecT Consulting
 *
 * All Rights Reserved. Violators will be shot and pissed upon.
 *
 */

#include "tts.h"

static char rcsid[] = "$Id: tts-refout.c,v 2.9 1998/06/14 04:23:18 root Exp root $";

#define DEBUG 0
/* #define VERBOSE */

void missing();

int main(int argc, char **argv) {
  char *tptr;
  struct stat buf;
  FILE *fh;
  int a;

  char ref[64];
  char qs[64];
  char ip[16];
  char ck[128];
  char tmp[256];
  char tmp2[64];

  arg=argv;

  checksuid();

  *qs = *ip = *ref = *ck = 0;

  if((tptr=getenv("QUERY_STRING"))) { if(!strncasecmp(tptr, "id=", 3)) tptr+=3; strncpy(qs, tptr, 64); qs[63]=0; }
  if((tptr=getenv("REMOTE_ADDR")))  { strncpy(ip,  tptr,  16);  ip[15]=0; }
  if((tptr=getenv("HTTP_REFERER"))) { strncpy(ref, tptr,  64); ref[63]=0; }
  if((tptr=getenv("HTTP_COOKIE")))  { strncpy(ck,  tptr, 128); ck[127]=0; }

  if(! *qs) { missing(); dodie("QUERY_STRING not set"); }

  for(tptr=qs;*tptr;tptr++) if(*tptr=='.' || *tptr=='/') *tptr='_';
  if(snprintf(tmp, 255, "%s/%s%s", ACCOUNTPATH, qs, ACCT_SUFFIX)==-1) { missing(); dodie("QUERY_STRING too long: %s", tmp); }
  if(stat(tmp, &buf)==-1) { missing(); dodie("Account not found: %s (ck: %s  ip: %s)", tmp, ck, ip); }
  if(!(fh = fopen(tmp, "r"))) { missing(); dodie("Can't read account file: %s", tmp); }

  while(!feof(fh)) {
    if(!fgets(tmp, 255, fh)) continue; 
    tmp[255] = 0;
    if(!strncasecmp("http://", tmp, 7)) {
      printf("Location: %s\n", tmp);
      break;
    }
  }

  if(feof(fh)) { missing(); dodie("Can't find URL to forward to: %s", qs); }
  fclose(fh);

  if(! *ip) dodie("REMOTE_ADDR not set");
  if(! *ref || !strcasestr(ref, AUTHORIZED)) dodie("Illegal access from %s (ref: %s, qs: %s, ck: %s)", ip, ref, qs, ck);

  snprintf(tmp, 255, "%s/%s%s", VISITORPATH, qs, OUT_SUFFIX); tmp[255]=0;

  if(!(fh = fopen(tmp, "a"))) dodie("Can't append to file: %s", tmp);

  fprintf(fh, "%s", ip);
  if(*ck) fprintf(fh, " %s", ck);
  fprintf(fh, "\n");
  fclose(fh);

  if(*ck) {
    snprintf(tmp, 256, "ID=TTS~%s~", UNIQUE); tmp[255]=0;
    if(strncasecmp(tmp, ck, strlen(tmp))) dodie("Foreign HTTP_COOKIE: %s", ck);
    strncpy(tmp, ck, 256);
    strtok(tmp, "~");
    for(a=0;a<3;a++) {
      tptr = strtok(NULL, "~");
      if(!tptr) dodie("Foreign HTTP_COOKIE: %s", ck);
    }
    strncpy(tmp2, tptr, 64);
    if(!strncmp(tmp2, qs, strlen(qs))) dodie("Self-referential click - Ignored: %s", ck);

    if(snprintf(tmp, 255, "%s/%s%s", ACCOUNTPATH, tmp2, ACCT_SUFFIX)==-1) dodie("Cannot find cookie account (sp): %s", ck);
    if(stat(tmp, &buf)==-1) dodie("Cannot find cookie account (st): %s --- %s (ref: %s)", ck, tmp, ref);
    snprintf(tmp, 255, "%s/%s%s", VISITORPATH, tmp2, REF_OUT_SUFFIX);
    if(!(fh = fopen(tmp, "a"))) dodie("Can't append to file: %s", tmp);
    fprintf(fh, "%s %s %s %s\n", ck, qs, ref, ip);
    fclose(fh);
  } 
#ifdef VERBOSE
  else dodie("No HTTP_COOKIE: ip=%s, ref=%s, qs=%s", ip, ref, qs); 
#endif
  exit(0);
}

void missing()
{
  printf("%s\n",MISSING);
}
