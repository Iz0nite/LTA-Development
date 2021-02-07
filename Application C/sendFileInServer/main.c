#include "sendFileInServer.h"

int main(int argc, char **argv){

    int idUser = 2;
    char* idUserChar;
    idUserChar = malloc(sizeof(char)*3+1);

    sendFileInServer(itoa(idUser,  idUserChar, 10));

    return 0;
}
