FROM ubuntu:18.04

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC

RUN apt-get clean -y && apt-get update && apt-get upgrade -y
RUN apt-get install -y tzdata apt-utils vim curl git unzip

# Install Node
RUN apt-get install -y gnupg
RUN curl -sL https://deb.nodesource.com/setup_8.x -o /tmp/nodesource_setup.sh
RUN bash /tmp/nodesource_setup.sh
RUN apt install -y nodejs
RUN npm install gulp-cli -g

WORKDIR /home/app
