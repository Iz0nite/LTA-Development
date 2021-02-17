#include "sendFileInServer.h"

size_t read_callback(char *ptr, size_t size, size_t nmemb, void *stream){
  curl_off_t nread;
  /* in real-world cases, this would probably get this data differently
     as this fread() stuff is exactly what the library already would do
     by default internally */
  size_t retcode = fread(ptr, size, nmemb, stream);

  nread = (curl_off_t)retcode;

  fprintf(stderr, "*** We read %" CURL_FORMAT_CURL_OFF_T
          " bytes from file\n", nread);
  return retcode;
}

void sendFileInServer(char* idUser){

    MYSQL mysql;
    mysql_init(&mysql);
    mysql_options(&mysql,MYSQL_READ_DEFAULT_GROUP,"option");

    if(mysql_real_connect(&mysql,"51.77.144.219","theo","jaimelethe","LTA",0,NULL,0)){

        CURL *curl;
        CURLcode result;
        struct stat file_info;
        curl_off_t speed_upload, total_time;

        char query[255];

        FILE *fp;

        fp = fopen("file/downloadedFile.txt", "rb"); /* open file to upload */
        if(!fp)
        return; /* can't continue */

        /* to get the file size */
        if(fstat(fileno(fp), &file_info) != 0)
        return; /* can't continue */

        curl = curl_easy_init();
        if(curl){

            strcpy(query, "sftp://51.77.144.219/var/www/html/users/");
            strcat(query, idUser);
            strcat(query, "/downloadedFile.txt");

            printf("query : %s\n", query);

            curl_easy_setopt(curl, CURLOPT_USERPWD, "root:n73r96uxZbfC");

            /* upload to this place */
            curl_easy_setopt(curl, CURLOPT_URL, query);

            curl_easy_setopt(curl, CURLOPT_READFUNCTION, read_callback);

            /* tell it to "upload" to the URL */
            curl_easy_setopt(curl, CURLOPT_UPLOAD, 1L);

            /* set where to read from (on Windows you need to use READFUNCTION too) */
            curl_easy_setopt(curl, CURLOPT_READDATA, fp);

            /* and give the size of the upload (optional) */
            curl_easy_setopt(curl, CURLOPT_INFILESIZE_LARGE,
                             (curl_off_t)file_info.st_size);

            /* enable verbose for easier tracing */
            curl_easy_setopt(curl, CURLOPT_VERBOSE, 1L);

            result = curl_easy_perform(curl);
            /* Check for errors */
            if(result != CURLE_OK) {
              fprintf(stderr, "curl_easy_perform() failed: %s\n",
                      curl_easy_strerror(result));

            }else{
              /* now extract transfer info */
              curl_easy_getinfo(curl, CURLINFO_SPEED_UPLOAD_T, &speed_upload);
              curl_easy_getinfo(curl, CURLINFO_TOTAL_TIME_T, &total_time);

              fprintf(stderr, "Speed: %" CURL_FORMAT_CURL_OFF_T " bytes/sec during %"
                      CURL_FORMAT_CURL_OFF_T ".%06ld seconds\n",
                      speed_upload,
                      (total_time / 1000000), (long)(total_time % 1000000));

            }
            /* always cleanup */
            curl_easy_cleanup(curl);
        }
        fclose(fp);
    }
    return;
}
