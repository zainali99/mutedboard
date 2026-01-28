#!/bin/bash

# Set full permissions recursively for the parent directory
chmod -R 777 ../

COMPOSE_DOCKER_CLI_BUILD=1 docker compose -f docker-compose.yml "$@"
