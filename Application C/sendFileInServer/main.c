#include <stdio.h>
#include <stdlib.h>
#include <curl.h>
#include <MYSQL/mysql.h>
#include <string.h>

void sendFileInServer(int idUser){

    MYSQL_RES *result=NULL;
    MYSQL_ROW row;

    MYSQL mysql;
    mysql_init(&mysql);
    mysql_options(&mysql,MYSQL_READ_DEFAULT_GROUP,"option");

    if(mysql_real_connect(&mysql,"51.77.144.219","theo","jaimelethe","LTA",0,NULL,0)){

        CURL *curl;
        int result;
        char errbuf[CURL_ERROR_SIZE];

        char query[255];

        FILE *fp = NULL;

        fp = fopen("file/DownloadedFile.txt", "wb");

        //System("curl -u name:passwd ftp://machine.domain:port/full/path/to/file")

        curl = curl_easy_init(); //initialize CURL fonction
        if(curl){

            //curl_easy_setopt(curl, CURLOPT_URL, argv[1]);

            curl_easy_setopt(curl, CURLOPT_URL, "https://www.lta-development.fr/users/"); //CURLOPT_ULR allow us to enter the url of the file we want to dl

            strcpy(query, "https://www.lta-development.fr/users/");
            strcat(query, idUser);
            strcat(query, "' AND date_periode='");
            strcat(query, data_date_periode);
            strcat(query, "' AND lieu1='");
            strcat(query, data_lieu1);
            strcat(query, "' AND libelle_type='");
            strcat(query, data_libelle_type);
            strcat(query, "'");

            curl_easy_setopt(curl, CURLOPT_URL, query);

            curl_easy_setopt(curl, CURLOPT_WRITEDATA, fp); //alow to write on the file we dl
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

    sendFileInServer(idUser);

    return 0;
}
