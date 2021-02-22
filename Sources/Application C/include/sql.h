#ifndef _SQL_H_
# define _SQL_H_

# include <mysql/mysql.h>
# include <stdio.h>
# include <stdlib.h>
# include <string.h>

void setUserData(int id, char *key, char *data);

char ***getPrice(char *typeDelivery, int *nbRow, char ***log);

char ***getListDeposit(int *nbRow);

char *getUserData(int id, char *key, char **log);

int setupMysqlConexion(MYSQL *mysql);

int getId(char *email, char **log);

int addNewOrder(int idUser, char *deliveryType, double price, char ***log);

int addPackage(char *weight, char *volumeSize, char *emailDest, char *address, char *city, char *idOrder, char *idDeposit, char ***log);

#endif