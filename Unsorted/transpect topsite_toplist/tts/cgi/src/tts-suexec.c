/*
 * TranSpecT Top Site (TTS) List - SUID executable CGI
 *
 * Copyright 1997 - Rick Franchuk - TranSpecT Consulting
 *
 * This CGI is used to seteuid/gid to the uid/gid of the site owner 
 * thereby making things much more efficient and secure. Suid progs have
 * a tendancy to be 'weak spots' though, so make sure there's no buffer
 * overflows or easy break-in points.
 *
 */

#include "tts.h"

static char rcsid[] = "$Id: tts-suexec.c,v 2.1 1998/06/08 22:33:34 root Exp root $";

int main(int argc, char **argv) {
  char tmp[256];

  arg = argv;
  checksuid();

  snprintf(tmp, 255, "%s%s", REALCGIS, argv[0]);
  execv(tmp, argv);
  /* Uh-oh! */
  dodie("Could not execute real CGI! \"%s\" (%s%s)", strerror(errno), REALCGIS, arg[0]);
  return(-1);
}
