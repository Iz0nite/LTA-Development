#ifndef _GTK_H_
# define _GTK_H_

# define G_MODULE_EXPORT __declspec(dllexport)
# define GLADE_FILENAME "./../../connection.glade"

# include <gtk/gtk.h>
# include <stdio.h>
# include <stdlib.h>
# include <string.h>
# include "sql.h"
# include "orderProcess.h"
# include "sendFileInServer.h"



typedef struct s_connection
{
	GtkWidget *box;
	GtkWidget *entry_email;
	GtkWidget *lbl_messageError;
} t_connection;

typedef struct s_menu
{
	GtkWidget *box;
	GtkWidget *selectorDownloadFolder;
    GtkWidget *lbl_messageError;
} t_menu;

typedef struct s_uploadFile
{
	GtkWidget *box;
	GtkWidget *inputFile;
	GtkWidget *selector;
	GtkWidget *lbl_messageError;
} t_uploadFile;

typedef struct s_processProgress
{
	GtkWidget *box;
	GtkWidget *progressBar;
	GtkWidget *lbl_message;
} t_processProgress;

typedef struct s_data
{
	int						    id;
	struct s_connection		    *windowConnection;
	struct s_menu			    *windowMenu;
	struct s_uploadFile		    *windowUploadFile;
	struct s_processProgress	*windowProgressProcess;
} t_data;



void orderProcess(t_data *data, char *deliveryType);

void updateProgressBar(GtkWidget *progressBar, int progressValue);

int gtkInit(int argc, char **argv);

G_MODULE_EXPORT void destroyWindow(GtkWidget *widget, t_data *data);

G_MODULE_EXPORT void logOut(GtkButton *btn_submit, t_data *data);

G_MODULE_EXPORT void signIn(GtkButton *btn_submit, t_data *data);

G_MODULE_EXPORT void uploadFile(GtkButton *btn_submit, t_data *data);

G_MODULE_EXPORT void uploadFileToMenu(GtkButton *btn_submit, t_data *data);

G_MODULE_EXPORT void createStandardOrder(GtkButton *btn_submit, t_data *data);

G_MODULE_EXPORT void createExpressOrder(GtkButton *btn_submit, t_data *data);

#endif