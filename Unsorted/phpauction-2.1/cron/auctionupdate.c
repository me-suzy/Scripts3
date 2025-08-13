/*

   Copyright (c), 1999, 2000 - Marcello Scacchetti - Nextrem
   http://www.nextrem.it
   http://www.nextremnet.it                  
   
   This program is free software; you can redistribute it and/or modify 
   it under the terms of the GNU General Public License as published by
   the Free Software Foundation (version 2 or later).                   
                                                                      
   This program is distributed in the hope that it will be useful,   
   but WITHOUT ANY WARRANTY; without even the implied warranty of   
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the      
   GNU General Public License for more details.                       
                                                                     
   You should have received a copy of the GNU General Public License   
   along with this program; if not, write to the Free Software       
   Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.^M
   
*/


//test

#include <sys/stat.h>
#include <sys/types.h>
#include <termios.h>                   
#include <stdio.h>
#include <string.h>
#include <fcntl.h>
#include <unistd.h>
#include <sys/syslog.h>
#include <sys/param.h>
#include <sys/times.h>             
#include <sys/time.h>                           
#include <sys/socket.h>
#include <netinet/in.h>
#include <sys/signal.h>
#include <arpa/inet.h>
#include <netdb.h>

main (int argc, char *argv[])
{
  struct sockaddr_in sin;
  struct hostent *hp;
  char FileBuf[8097]; 
  int sock, i=0, X;
  if(argc != 3) {
        printf("Wrong number of parameters!\n");
	exit(1);
  }
  hp = gethostbyname (argv[1]);
  bzero((char*) &sin, sizeof(sin));
  bcopy(hp->h_addr, (char *) &sin.sin_addr, hp->h_length);
  sin.sin_family = hp->h_addrtype;
  sin.sin_port = htons(80);
  sock = socket(AF_INET, SOCK_STREAM, 0);
  X=connect(sock,(struct sockaddr *) &sin, sizeof(sin));
  write(sock,"GET ",strlen("GET ")*sizeof(char));
  write(sock,argv[2],strlen(argv[2])*sizeof(char));
  write(sock,"\n\n",strlen("\n\n")*sizeof(char));  
  while((X=read(sock,FileBuf,8096))!=0)
  write(1,FileBuf,X);
}
