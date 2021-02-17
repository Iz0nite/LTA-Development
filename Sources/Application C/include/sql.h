#ifndef _SQL_H_
# define _SQL_H_

# include <mysql/mysql.h>
# include <stdio.h>
# include <stdlib.h>
# include <string.h>

void setUserData(int id, char *key, char *data);

int setupMysqlConexion(MYSQL *mysql);

int getId(char *email, char **log);

char *getUserData(int id, char *key, char **log);

#endif