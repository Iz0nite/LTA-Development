#ifndef _GTK_H_
# define _GTK_H_

# define G_MODULE_EXPORT __declspec(dllexport)
# define GLADE_FILENAME "connection.glade"

# include <gtk/gtk.h>
# include "account.h"

void updateWindow(GtkBuilder *builder, char *windowObjectName);

int gtkInit(int argc, char **argv, GtkBuilder **builder;);

G_MODULE_EXPORT void destroyWindow(GtkWidget *widget, t_widgets *widgets);

G_MODULE_EXPORT void connexion();

#endif