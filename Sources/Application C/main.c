#include "gtk.h"

int main(int argc , char* argv[])
{
    GtkBuilder *builder;

    if(gtkInit(argc, argv, &builder))
        updateWindow(builder, "window_connection");

    return 0;
}
