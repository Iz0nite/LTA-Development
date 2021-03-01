#ifndef _SENDFILEINSERVER_H_
# define _SENDFILEINSERVER_H_

# include <mysql/mysql.h>
# include <sys/types.h>
# include <sys/stat.h>
# include <curl/curl.h>
# include <stdio.h>
# include <stdlib.h>
# include <string.h>

typedef struct s_ftpFile {
    char *filename;
    FILE *stream;
} t_ftpFile;

static size_t my_fwrite(void *buffer, size_t size, size_t nmemb, void *stream);

size_t read_callback(char *ptr, size_t size, size_t nmemb, void *stream);

void sendFileInServer(int idUser, char *srcFile, char *idBill);

void downloadTemplate(char *downloadPath);

#endif
