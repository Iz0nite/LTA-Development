#include <stdio.h>
#include <stdlib.h>
#include <MYSQL/mysql.h>
#include <string.h>

int signIn(){

    char pseudo[50];
    char password[100];
    char query[255];
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

            printf("Enter your password:\n\n");
            fflush(stdin);
            fgets(password,100,stdin);
            if(password[strlen(password)-1]=='\n'){
                password[strlen(password)-1]='\0';
            }

            strcpy(query,"SELECT idUser,password FROM USERS WHERE email='");
            strcat(query,pseudo);
            strcat(query,"'");

            printf("%s\n",query);

            mysql_query(&mysql,query);
            result = mysql_store_result(&mysql);
            row = mysql_fetch_row(result);

            if(!row){
                printf("Incorrect pseudo");
                check=0;
            }

            if(check!=0){
                if(strstr(password,row[1])==NULL){
                    printf("Incorrect password");
                    check=0;
                }
            }

        }while(check!=1);

        printf("You are connected");
        sscanf(row[0],"%d",&id);

       return id;

    }

}

int main()
{
    int id;

    id=signIn();
    printf("finish");
    return 0;
}
