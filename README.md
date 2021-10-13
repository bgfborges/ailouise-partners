# Wordpress-Docker Dev Environment
Wordpress-Docker development environment

Copied and edited with our pull requests from [feliperinaldi](https://github.com/feliperinaldi/wordpress-docker)

Shortcutes to install directly in your machine.
$ git clone git@github.com:ikoba218/wordpress-docker.git && cd wordpress-docker && docker-compose up -d

To customize your setup:
1. Firstly, clone this repo and go into the folder: 
$ git clone git@github.com:ikoba218/wordpress-docker.git && cd wordpress-docker

2. And then edit the file .env on line 5: PROJECT_NAME - changing dockerwp to your slug.
It will make your containers slugs have the same slug before the container name.

3. Start the containers:
$ docker-compose up -d

## To WP Cli:

This setup uses the WP Cli in the wordmove container. Then, after followed the previous steps:

1. In the new setup folder (default wordpress-docker):
$ docker exec -it your_slug_wordmove /bin/bash

2. Within bash, go to the WP Container and be happy:
$ cd /var/www/html && wp --version --allow-root
Notice: in the end of command line, always use the --allow-root to make your command works with wp cli.


hello mundo