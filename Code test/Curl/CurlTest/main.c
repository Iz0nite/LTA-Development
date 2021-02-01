#include <stdio.h>
#include <stdlib.h>
#include <curl.h>

void getApiViaCurl(FILE *fp){

    CURL *curl;
    int result;
    char errbuf[CURL_ERROR_SIZE];

    fp = fopen("file/DownloadedFile.pdf", "wb");

    curl = curl_easy_init(); //initialize CURL fonction
    if(curl){

        //curl_easy_setopt(curl, CURLOPT_URL, argv[1]);
        curl_easy_setopt(curl, CURLOPT_URL, "http://www.domainecomps.com/medias/files/test2.pdf"); //CURLOPT_ULR allow us to enter the url of the file we want to dl
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

int main(int argc, char **argv){

    FILE *fp = NULL;

    getApiViaCurl(fp);

    return 0;
}
