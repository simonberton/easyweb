FROM ubuntu:18.04

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC

RUN apt-get clean -y && apt-get update && apt-get upgrade -y
RUN apt-get install -y tzdata apt-utils vim curl git unzip
RUN apt-get install -y nginx-extras

ADD etc/nginx/nginx.conf /etc/nginx/
ADD etc/nginx/sites-available/easyweb.conf /etc/nginx/sites-available/

RUN ln -s /etc/nginx/sites-available/easyweb.conf /etc/nginx/sites-enabled/easyweb.conf
RUN rm /etc/nginx/sites-enabled/default

WORKDIR /home/app

EXPOSE 80

CMD ["nginx"]
