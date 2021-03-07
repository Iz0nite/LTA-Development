#include "sendFileInServer.h"

static size_t my_fwrite(void *buffer, size_t size, size_t nmemb, void *stream)
{
    t_ftpFile *out = (t_ftpFile *)stream;
    if(!out->stream) {
        /* open file for writing */
        out->stream = fopen(out->filename, "wb");
        if(!out->stream)
            return -1; /* failure, can't open file to write */
    }
    return fwrite(buffer, size, nmemb, out->stream);
}



void downloadTemplate(char *downloadPath)
{
    CURL *curl;
    CURLcode res;
    t_ftpFile ftpfile;
    char *downloadFilePath;

    downloadFilePath = malloc(sizeof(char) * 512);
    ftpfile.filename = malloc(sizeof(char) * 512);

    strcpy(downloadFilePath, downloadPath);
    strcat(downloadFilePath, "\\orderForm.csv");

    strcpy(ftpfile.filename, downloadFilePath);
    ftpfile.stream = NULL;

    printf("#%s#\n", downloadFilePath);

    curl_global_init(CURL_GLOBAL_DEFAULT);

    curl = curl_easy_init();
    if(curl){

        curl_easy_setopt(curl, CURLOPT_USERPWD, "root:n73r96uxZbfC");

        /* You better replace the URL with one that works! */
        curl_easy_setopt(curl, CURLOPT_URL, "sftp://51.77.144.219/var/www/html/files/template.csv");

        /* Define our callback to get called when there's data to be written */
        curl_easy_setopt(curl, CURLOPT_WRITEFUNCTION, my_fwrite);

        /* Set a pointer to our struct to pass to the callback */
        curl_easy_setopt(curl, CURLOPT_WRITEDATA, &ftpfile);

        /* Switch on full protocol/debug output */
        curl_easy_setopt(curl, CURLOPT_VERBOSE, 1L);

        res = curl_easy_perform(curl);

        /* always cleanup */
        curl_easy_cleanup(curl);

        if(CURLE_OK != res) {
            /* we failed */
            fprintf(stderr, "curl told us %d\n", res);
        }
    }

    if(ftpfile.stream)
        fclose(ftpfile.stream); /* close the local file */

    curl_global_cleanup();

    return;
}



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



void sendFileInServer(int idUser, char *srcFile, char *idFile, int fileType)
{
    MYSQL mysql;
    char txtIdUser[5];

    itoa(idUser, txtIdUser, 10);

    mysql_init(&mysql);
    mysql_options(&mysql,MYSQL_READ_DEFAULT_GROUP,"option");

    if(mysql_real_connect(&mysql,"51.77.144.219","theo","jaimelethe","LTA",0,NULL,0)){

        CURL *curl;
        CURLcode result;
        struct stat file_info;
        curl_off_t speed_upload, total_time;

        char query[255];

        FILE *fp;

        fp = fopen(srcFile, "rb"); /* open file to upload */
        if(!fp)
            return; /* can't continue */

        /* to get the file size */
        if(fstat(fileno(fp), &file_info) != 0)
            return; /* can't continue */

        curl = curl_easy_init();
        if(curl){
            strcpy(query, "sftp://51.77.144.219/var/www/html/users/");
            strcat(query, txtIdUser);

            printf("file type: %d\n", fileType);
            switch(fileType)
            {
                case 0:
                    strcat(query, "/bill/");
                    strcat(query, idFile);
                    strcat(query, ".csv");
                    printf("file to send: %s\n", srcFile);
                    break;

                case 1:
                    strcat(query, "/qrcode/");
                    strcat(query, srcFile);
                    printf("file to send: %s\n", srcFile);
                    break;
            }

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
