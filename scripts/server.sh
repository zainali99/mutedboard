#!/bin/bash

# MutedBoard - Server Management Script

cd "$(dirname "$0")/.."

DOCKER_COMPOSE="docker compose -f docker/docker-compose.yml"

case "$1" in
    start)
        echo "Starting MutedBoard development server..."
        $DOCKER_COMPOSE up -d
        echo ""
        echo "✓ Server started successfully!"
        echo ""
        echo "  Application: http://localhost:8088"
        echo "  MySQL:       localhost:33088"
        ;;
    
    stop)
        echo "Stopping MutedBoard development server..."
        $DOCKER_COMPOSE down
        echo ""
        echo "✓ Server stopped successfully!"
        ;;
    
    restart)
        echo "Restarting MutedBoard development server..."
        $DOCKER_COMPOSE restart
        echo ""
        echo "✓ Server restarted successfully!"
        echo ""
        echo "  Application: http://localhost:8088"
        ;;
    
    rebuild)
        echo "Rebuilding MutedBoard containers..."
        $DOCKER_COMPOSE up -d --build
        echo ""
        echo "✓ Containers rebuilt and started successfully!"
        echo ""
        echo "  Application: http://localhost:8088"
        ;;
    
    logs)
        SERVICE="${2:-app}"
        echo "Showing logs for: $SERVICE"
        echo "Available services: app, nginx, db"
        echo "Press Ctrl+C to exit"
        echo ""
        $DOCKER_COMPOSE logs -f "$SERVICE"
        ;;
    
    exec)
        if [ $# -lt 2 ]; then
            echo "Usage: ./scripts/server.sh exec <command>"
            echo ""
            echo "Examples:"
            echo "  ./scripts/server.sh exec bash"
            echo "  ./scripts/server.sh exec php -v"
            exit 1
        fi
        shift
        $DOCKER_COMPOSE exec app "$@"
        ;;
    
    mysql)
        echo "Connecting to MySQL database..."
        echo ""
        $DOCKER_COMPOSE exec db mysql -u muteduser -pmutedpass mutedboard
        ;;
    
    status)
        echo "MutedBoard Server Status:"
        echo ""
        $DOCKER_COMPOSE ps
        ;;
    
    *)
        echo "MutedBoard Server Management"
        echo ""
        echo "Usage: ./scripts/server.sh <command> [options]"
        echo ""
        echo "Commands:"
        echo "  start          Start the development server"
        echo "  stop           Stop the development server"
        echo "  restart        Restart the development server"
        echo "  rebuild        Rebuild and start containers"
        echo "  status         Show container status"
        echo "  logs [service] View container logs (default: app)"
        echo "  exec <cmd>     Execute command in PHP container"
        echo "  mysql          Open MySQL shell"
        echo ""
        echo "Examples:"
        echo "  ./scripts/server.sh start"
        echo "  ./scripts/server.sh logs nginx"
        echo "  ./scripts/server.sh exec bash"
        echo "  ./scripts/server.sh mysql"
        exit 1
        ;;
esac
