#!/bin/bash
$1
docker-compose exec -it --user root server /bin/bash $1