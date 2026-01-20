# MutedBoard Scripts

Convenient scripts for managing the MutedBoard development environment.

## Available Scripts

### Server Management

- **`./scripts/start.sh`** - Start the development server
- **`./scripts/stop.sh`** - Stop the development server
- **`./scripts/restart.sh`** - Restart the development server
- **`./scripts/rebuild.sh`** - Rebuild and start containers

### Utilities

- **`./scripts/logs.sh [service]`** - View container logs
  ```bash
  ./scripts/logs.sh        # View app logs
  ./scripts/logs.sh nginx  # View nginx logs
  ./scripts/logs.sh db     # View database logs
  ```

- **`./scripts/exec.sh <command>`** - Execute commands in PHP container
  ```bash
  ./scripts/exec.sh bash              # Open shell
  ./scripts/exec.sh php -v            # Check PHP version
  ./scripts/exec.sh composer install  # Install dependencies
  ```

- **`./scripts/mysql.sh`** - Open MySQL shell

## Quick Start

```bash
# Make scripts executable (first time only)
chmod +x scripts/*.sh

# Start server
./scripts/start.sh

# View logs
./scripts/logs.sh

# Stop server
./scripts/stop.sh
```
