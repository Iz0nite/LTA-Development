#include <stdio.h>
#include <stdlib.h>
#include <curl.h>
#include <MYSQL/mysql.h>
#include <string.h>

size_t readFunction(char *contents, size_t size, size_t nmemb, void *userp){
  FILE *readhere = (FILE *)userp;
  curl_off_t nread;

  /* copy as much data as possible into the 'contents' buffer, but no more than
     'size' * 'nmemb' bytes! */
  size_t retcode = fread(contents, size, nmemb, readhere);

  nread = (curl_off_t)retcode;

  fprintf(stderr, "*** We read %" CURL_FORMAT_CURL_OFF_T
          " bytes from file\n", nread);
  return retcode;
}

void sendFileInServer(char* idUser){

//    MYSQL_RES *result=NULL;
//    MYSQL_ROW row;

    MYSQL mysql;
    mysql_init(&mysql);
    mysql_options(&mysql,MYSQL_READ_DEFAULT_GROUP,"option");

    if(mysql_real_connect(&mysql,"51.77.144.219","theo","jaimelethe","LTA",0,NULL,0)){

        CURL *curl;
        int result;
        char errbuf[CURL_ERROR_SIZE];

        FILE *fp = NULL;

        fp = fopen("file/DownloadedFile.txt", "rb");

        char query[255];

        //System("curl -u name:passwd ftp://machine.domain:port/full/path/to/file")

        curl = curl_easy_init(); //initialize CURL fonction

        if(curl){

            //curl_easy_setopt(curl, CURLOPT_URL, argv[1]);

            curl_off_t uploadsize = -1;

//            strcpy(query, "https://www.lta-development.fr/users/");
            strcpy(query, "ftp://lta-development.fr/users/");
            strcat(query, idUser);
            strcat(query, "/");

            printf("query = %s\n\n", query);

            curl_easy_setopt(curl, CURLOPT_URL, query); //CURLOPT_ULR allow us to enter the url of the file we want to dl


            size_t function(char *bufptr, size_t size, size_t nitems, void *userp); //maybe not necessary

            curl_easy_setopt(curl, CURLOPT_READFUNCTION, readFunction);    //maybe not necessary

            curl_easy_setopt(curl, CURLOPT_READDATA, (void *)fp);    ////maybe not necessary


            curl_easy_setopt(curl, CURLOPT_UPLOAD, 1L); //tell libcurl that we want to upload

            curl_easy_setopt(curl, CURLOPT_INFILESIZE_LARGE, uploadsize);




            //curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp); //alow to write on the file we dl
            curl_easy_setopt(curl, CURLOPT_FAILONERROR, 1L);
            curl_easy_setopt(curl, CURLOPT_ERRORBUFFER, errbuf);
            errbuf[0] = 0;
            curl_easy_setopt(curl, CURLOPT_SSL_VERIFYPEER, 0);

            curl_easy_setopt(curl, CURLOPT_NOPROGRESS, 0L);//enable progress meter

            result = curl_easy_perform(curl); //return if the dl was successful (might take few seconds)

            if(result != CURLE_OK) {
                size_t len = strlen(errbuf);
                fprintf(stderr, "\nlibcurl: (%d) ", result);
                if(len){
                    fprintf(stderr, "%s%s", errbuf, ((errbuf[len - 1] != '\n') ? "\n" : ""));
                }
            }else{
                    fprintf(stderr, "\n%s\n", curl_easy_strerror(result));
                    printf("Download successful !\n");
                }

            fclose(fp);
            curl_easy_cleanup(curl);
        }
    }

}

int main(int argc, char **argv){

    int idUser = 2;
    char* idUserChar;

    idUserChar = malloc(sizeof(char)*3+1);

    sendFileInServer(itoa(idUser,  idUserChar, 10));

    return 0;
}
