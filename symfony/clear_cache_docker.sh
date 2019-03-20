#!/bin/bash

docker exec -it st_php72 php bin/console doctrine:cache:clear-metadata
docker exec -it st_php72 php bin/console doctrine:cache:clear-query
docker exec -it st_php72 php bin/console doctrine:cache:clear-result

echo "Clear cache ...";
rm -R var/cache/*
echo "... cleared.";

echo "Clear session ...";
rm -R var/sessions/*
echo "... cleared.";