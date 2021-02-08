#include <stdio.h>
#include <stdlib.h>
#include <MYSQL/mysql.h>
#include <string.h>

int signIn(){

    char pseudo[50];
    char password[100];
    char query[255];
    char httpRequest[255];
    int check=1;
    MYSQL_RES *result=NULL;
    MYSQL_ROW row;
    int id;

    MYSQL mysql;
    mysql_init(&mysql);
    mysql_options(&mysql,MYSQL_READ_DEFAULT_GROUP,"option");

    if(mysql_real_connect(&mysql,"51.77.144.219","armanddfl","Sa4L5V6ve","LTA",0,NULL,0)){

        printf("\nSign in:\n");


        do{
           check=1;
           printf("Enter your mail:\n\n");
           fflush(stdin);
           fgets(pseudo,50,stdin);
           if(pseudo[strlen(pseudo)-1]=='\n'){
               pseudo[strlen(pseudo)-1]='\0';
           }


            strcpy(query,"SELECT idUser,appSignIn FROM USERS WHERE email='");
            strcat(query,pseudo);
            strcat(query,"'");

            mysql_query(&mysql,query);
            result = mysql_store_result(&mysql);
            row = mysql_fetch_row(result);

            if(!row){
                printf("Incorrect pseudo");
                check=0;
            }

        }while(check!=1);

        check=0;
        strcpy(httpRequest, "start https://lta-development.fr/en/appConnection");
        system(httpRequest);

        do{
            strcpy(query,"SELECT idUser,appSignIn FROM USERS WHERE email='");
            strcat(query,pseudo);
            strcat(query,"'");

            mysql_query(&mysql,query);
            result = mysql_store_result(&mysql);
            row = mysql_fetch_row(result);

            if(strcmp(row[1],"1")==0){
                check=1;
            }

        }while(check!=1);

        printf("\nYou are connected");
        sscanf(row[0],"%d",&id);

       return id;

    }else{

        printf("ERROR: no connexion with bdd");
    }

    mysql_close(&mysql);

}

void menu(int id){

    int choice;
    char txtId[10];
    char query[255];
    MYSQL_RES *result=NULL;
    MYSQL_ROW row;

    MYSQL mysql;
    mysql_init(&mysql);
    mysql_options(&mysql,MYSQL_READ_DEFAULT_GROUP,"option");

    do{

        printf("welcom in the Quick Baluchon compagnon app.\nWhat do you want to do?\n1) leave\n");

        scanf("%d",&choice);

    }while(choice!=1);

    if(choice==1){

            if(mysql_real_connect(&mysql,"51.77.144.219","armanddfl","Sa4L5V6ve","LTA",0,NULL,0)){

                strcpy(query,"UPDATE USERS SET appSignIn='0' WHERE idUser='");
                itoa(id,txtId,10);
                strcat(query,txtId);
                strcat(query,"'");
                mysql_query(&mysql,query);

            }else{
                printf("ERROR: no connexion with bdd");
            }

            mysql_close(&mysql);

    }




}

int main()
{
    int id;

    id=signIn();

    menu(id);

    //printf("leave");
    return 0;
}
