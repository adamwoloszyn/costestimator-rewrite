FROM docker-images.labcorp.com/mi/apache-httpd:latest

#if you need to override settings
#COPY  httpd.conf /etc/httpd/conf/httpd.conf

COPY ./frontend/dist /var/www/
