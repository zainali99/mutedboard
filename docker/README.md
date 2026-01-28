# Docker Configuration for MutedBoard

This directory contains Docker configuration files for running MutedBoard in a containerized environment.

## Files

- **Dockerfile** - PHP-FPM container configuration
- **docker-compose.yml** - Multi-container orchestration
- **nginx.conf** - Nginx web server configuration

## Quick Start

```bash
# From the docker directory
docker compose up -d

# Or from project root
docker compose -f docker/docker-compose.yml up -d
```

## Services

- **app** (PHP 8.2-FPM) - Application container
- **nginx** (1.25) - Web server on port 8088
- **db** (MySQL 8.0) - Database on port 33088

## Access

- Application: http://localhost:8088
- MySQL: localhost:33088

## Default Credentials

- Database: `mutedboard`
- User: `muteduser`
- Password: `mutedpass`
- Root Password: `rootpass`
