#ifndef _GTK_H_
# define _GTK_H_

# define G_MODULE_EXPORT __declspec(dllexport)
# define GLADE_FILENAME "connection.glade"

# include <gtk/gtk.h>
# include <stdio.h>
# include <stdlib.h>
# include <string.h>
# include "sql.h"



typedef struct s_connection
{
	GtkWidget *box;
	GtkWidget *entry_email;
	GtkWidget *lbl_messageError;
} t_connection;

typedef struct s_menu
{
	GtkWidget *box;
	GtkWidget *entry_email;
	GtkWidget *lbl_messageError;
} t_menu;

typedef struct s_data
{
	int						id;
	struct s_connection		*windowConnection;
	struct s_menu			*windowMenu;
} t_data;



int gtkInit(int argc, char **argv);

G_MODULE_EXPORT void destroyWindow(GtkWidget *widget, t_data *data);

G_MODULE_EXPORT void logOut(GtkButton *btn_submitConnection, t_data *data);

G_MODULE_EXPORT void signIn(GtkButton *btn_submitConnection, t_data *data);

#endif