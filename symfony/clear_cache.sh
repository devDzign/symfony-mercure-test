#!/bin/bash

php bin/console doctrine:cache:clear-metadata
php bin/console doctrine:cache:clear-query
php bin/console doctrine:cache:clear-result

echo "Clear cache ...";
rm -R var/cache/*
echo "... cleared.";

echo "Clear session ...";
rm -R var/sessions/*
echo "... cleared.";