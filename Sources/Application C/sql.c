#include "sql.h"

int setupMysqlConexion(MYSQL *mysql)
{
	mysql_init(mysql);
	mysql_options(mysql,MYSQL_READ_DEFAULT_GROUP,"option");

	if(mysql_real_connect(mysql, "51.77.144.219", "armanddfl", "Sa4L5V6ve", "LTA", 0, NULL, 0))
	{
		if(mysql_set_character_set(mysql, "utf8"))
			printf("Set encoding to utf-8 failed!\n");
		
		return 1;
	}

	return 0;
}



int getId(char *email, char **log)
{
	MYSQL mysql;
	MYSQL_RES *resultSql;
	MYSQL_ROW rowSql;
	char query[256];

	if(setupMysqlConexion(&mysql))
	{
		strcpy(query, "SELECT idUser FROM USERS WHERE email = '");
		strcat(query, email);
		strcat(query, "'");

		mysql_query(&mysql, query);

		resultSql = mysql_store_result(&mysql);

		if(resultSql)
		{
			rowSql = mysql_fetch_row(resultSql);
			if(rowSql)
			{
				mysql_close(&mysql);
				return atoi(rowSql[0]);
			}
		}

		strcpy(*log, "<span foreground='red'>");
		strcat(*log, "The email is incorrect or does not exist!");
		strcat(*log, "</span>");
		mysql_close(&mysql);
		return 0;
	}
	else
	{
		strcpy(*log, "<span foreground='red'>");
		strcat(*log, "Cannot connect to the database!");
		strcat(*log, "</span>");
		mysql_close(&mysql);
		return 0;
	}
}



char *getUserData(int id, char *key, char **log)
{
	MYSQL mysql;
	MYSQL_RES *resultSql;
	MYSQL_ROW rowSql;
	char query[256];
	char stringId[5];

	if(setupMysqlConexion(&mysql))
	{
		itoa(id, stringId, 10);
		strcpy(query, "SELECT ");
		strcat(query, key);
		strcat(query, " FROM USERS WHERE idUser = '");
		strcat(query, stringId);
		strcat(query, "'");

		mysql_query(&mysql, query);

		resultSql = mysql_store_result(&mysql);

		if(resultSql)
		{
			rowSql = mysql_fetch_row(resultSql);
			if(rowSql)
			{
				strcpy(*log, "<span foreground='#10ac84'>");
				strcat(*log, "Please connect to the website and then resend\n the connection verification to the application.");
				strcat(*log, "</span>");

				mysql_close(&mysql);
				return rowSql[0];
			}
		}

		strcpy(*log, "<span foreground='red'>");
		strcat(*log, "Incorrect id user!");
		strcat(*log, "</span>");
		mysql_close(&mysql);
		return NULL;
	}
	else
	{
		strcpy(*log, "<span foreground='red'>");
		strcat(*log, "Cannot connect to the database!");
		strcat(*log, "</span>");
		mysql_close(&mysql);
		return NULL;
	}
}



void setUserData(int id, char *key, char *data)
{
	MYSQL mysql;
	char query[256];
	char stringId[5];

	if(setupMysqlConexion(&mysql))
	{
		itoa(id, stringId, 10);
		strcpy(query, "UPDATE USERS SET ");
		strcat(query, key);
		strcat(query, "='");
		strcat(query, data);
		strcat(query, "' WHERE idUser='");
		strcat(query, stringId);
		strcat(query, "'");

		mysql_query(&mysql, query);
	}
}