#include "downloadTemplate.h"

static size_t my_fwrite(void *buffer, size_t size, size_t nmemb, void *stream){
    struct FtpFile *out = (struct FtpFile *)stream;
    if(!out->stream) {
        /* open file for writing */
        out->stream = fopen(out->filename, "wb");
    if(!out->stream)
        return -1; /* failure, can't open file to write */
    }
    return fwrite(buffer, size, nmemb, out->stream);
}

void downloadTemplate(){
    CURL *curl;
    CURLcode res;
    struct FtpFile ftpfile = {
        "templateDownloaded.csv", /* name to store the file as if successful */
        NULL
    };

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
