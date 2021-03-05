cmake_minimum_required(VERSION 3.17)
project(LTA-Development C)
set(CMAKE_C_STANDARD 99)

add_executable(LTA-Development main.c gtk.c sql.c orderProcess.c sendFileInServer.c qrcodegen.c qrcodegen.c qrcodeProcess.c)

find_package(PkgConfig REQUIRED)

pkg_check_modules(GTK3 REQUIRED gtk+-3.0)

include_directories("./include")
include_directories(${GTK3_INCLUDE_DIRS})
include_directories(${CURL_INCLUDE_DIRS})

link_directories(${GTK3_LIBRARY_DIRS})
link_directories(${CURL_LIBRARY_DIRS})
link_directories("./lib")

add_definitions(${GTK3_CFLAGS_OTHER})

target_link_libraries(LTA-Development ${GTK3_LIBRARIES})
target_link_libraries(LTA-Development curl)
target_link_libraries(LTA-Development mariadb)