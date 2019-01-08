#!/bin/bash

docker exec -it pt_php72 php bin/console doctrine:database:drop --force
docker exec -it pt_php72 php bin/console doctrine:database:create
docker exec -it pt_php72 php bin/console doctrine:schema:update --force
docker exec -it pt_php72 php bin/console doctrine:fixtures:load --no-interaction
