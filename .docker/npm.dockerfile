FROM node:18

WORKDIR /var/www/html

COPY . .

RUN npm install
RUN npm run build

COPY public /var/www/html/public