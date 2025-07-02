# Specify a base image
FROM node:alpine

# Create app directory
RUN mkdir -p /usr/src/app
RUN mkdir -p /usr/src/app/node_modiles
WORKDIR /usr/src/app
# Install app dependencies
COPY ./package.json /usr/src/app/
RUN npm install --force
RUN chmod -R 777 /usr/src/app/node_modules/
