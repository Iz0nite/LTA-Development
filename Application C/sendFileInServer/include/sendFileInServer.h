#ifndef _SENDFILEINSERVER_H_

#define _SENDFILEINSERVER_H_

#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#include <sys/types.h>
#include <sys/stat.h>

#include <curl.h>
#include <MYSQL/mysql.h>

size_t read_callback(char *ptr, size_t size, size_t nmemb, void *stream);
void sendFileInServer(char* idUser);

#endif // _SENDFILEINSERVER_H_
