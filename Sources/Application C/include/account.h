#ifndef _ACCOUNT_H_
# define _ACCOUNT_H_

# define G_MODULE_EXPORT __declspec(dllexport)

# include <gtk/gtk.h>
# include <stdio.h>
# include <stdlib.h>
# include <string.h>
# include "sql.h"

typedef struct s_widgets
{
    GtkWidget *entry_email;
    GtkWidget *lbl_messageError;
    int id;
} t_widgets;

G_MODULE_EXPORT void signIn(GtkButton *btn_submitConnection, t_widgets *widgets);

#endif