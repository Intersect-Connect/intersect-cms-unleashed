# Docker Setup for Intersect CMS

This document describes how to run Intersect CMS using Docker.

## Requirements

- Docker Engine 20.10+
- Docker Compose 2.0+

## Quick Start

### Using Docker Compose (Recommended for Development)

1. **Copy the environment file:**
   ```bash
   cp .env.docker .env
   ```

2. **Edit the `.env` file** with your configuration:
   - Change `APP_SECRET` to a random string
   - Adjust database credentials if needed
   - Configure mailer settings if required

3. **Start the containers:**
   ```bash
   docker-compose up -d
   ```

4. **Wait for the application to be ready:**
   The application will automatically:
   - Wait for the database to be ready
   - Run database migrations
   - Clear and warm up the cache

5. **Access the application:**
   - **Website:** http://localhost:8080
   - **phpMyAdmin:** http://localhost:8081

### Using Pre-built Docker Image (Production)

When a release is published, a Docker image is automatically built and pushed to GitHub Container Registry.

1. **Pull the latest image:**
   ```bash
   docker pull ghcr.io/nerdscorp/intersect-cms:latest
   ```

2. **Run with environment variables:**
   ```bash
   docker run -d \
     --name intersect-cms \
     -p 8080:80 \
     -e APP_ENV=prod \
     -e APP_SECRET=your_secret_here \
     -e DATABASE_URL=mysql://user:password@db_host:3306/database_name?serverVersion=8.0 \
     ghcr.io/nerdscorp/intersect-cms:latest
   ```

   Or use docker-compose with the pre-built image:
   ```yaml
   version: '3.8'
   services:
     app:
       image: ghcr.io/nerdscorp/intersect-cms:latest
       ports:
         - "8080:80"
       environment:
         APP_ENV: prod
         APP_SECRET: your_secret_here
         DATABASE_URL: mysql://user:password@db_host:3306/database_name?serverVersion=8.0
   ```

## Environment Variables

| Variable | Description | Default |
|----------|-------------|---------|
| `APP_ENV` | Application environment (prod/dev) | `prod` |
| `APP_SECRET` | Secret key for Symfony | Required |
| `DATABASE_URL` | Database connection URL | Required |
| `MAILER_DSN` | Mailer DSN for email sending | `smtp://localhost` |
| `DB_NAME` | Database name (docker-compose only) | `intersect_cms` |
| `DB_USER` | Database user (docker-compose only) | `intersect` |
| `DB_PASSWORD` | Database password (docker-compose only) | `intersect` |
| `DB_ROOT_PASSWORD` | Database root password (docker-compose only) | `rootpassword` |

## Container Details

### Technology Stack

- **PHP:** 8.2 with Apache
- **Database:** MySQL 8.0
- **Web Server:** Apache with mod_rewrite enabled

### Installed PHP Extensions

- pdo_mysql
- mbstring
- exif
- pcntl
- bcmath
- gd
- zip
- intl
- opcache

### Volumes

The docker-compose setup creates the following volumes:

- `db_data`: MySQL database files
- `app_var`: Application cache and logs
- `app_public`: Uploaded files

## Useful Commands

### View logs:
```bash
# All services
docker-compose logs -f

# Application only
docker-compose logs -f app

# Database only
docker-compose logs -f db
```

### Access the application container:
```bash
docker-compose exec app bash
```

### Run Symfony console commands:
```bash
# Clear cache
docker-compose exec app php bin/console cache:clear

# Run migrations
docker-compose exec app php bin/console doctrine:migrations:migrate

# Create admin user (example)
docker-compose exec app php bin/console app:create-user
```

### Stop containers:
```bash
docker-compose down
```

### Stop and remove volumes (WARNING: This will delete all data):
```bash
docker-compose down -v
```

## Building the Image Manually

If you want to build the Docker image yourself:

```bash
docker build -t intersect-cms:local .
```

## Troubleshooting

### Database connection errors

Make sure the database container is fully started and healthy:
```bash
docker-compose ps
```

The app container will wait for the database to be ready before starting.

### Permission issues

If you encounter permission issues with the cache or log directories:
```bash
docker-compose exec app chown -R www-data:www-data /var/www/html/var
```

### Clear all data and start fresh

```bash
docker-compose down -v
docker-compose up -d
```

## Production Deployment

For production deployments:

1. Use a strong random `APP_SECRET`
2. Use secure database credentials
3. Configure proper backup strategy for the `db_data` volume
4. Use HTTPS with a reverse proxy (nginx, Traefik, etc.)
5. Set appropriate resource limits in your docker-compose or orchestration platform
6. Monitor logs and application health

### Example with Traefik reverse proxy:

```yaml
version: '3.8'
services:
  app:
    image: ghcr.io/nerdscorp/intersect-cms:latest
    environment:
      APP_ENV: prod
      APP_SECRET: ${APP_SECRET}
      DATABASE_URL: ${DATABASE_URL}
    labels:
      - "traefik.enable=true"
      - "traefik.http.routers.intersect.rule=Host(`yourdomain.com`)"
      - "traefik.http.routers.intersect.entrypoints=websecure"
      - "traefik.http.routers.intersect.tls.certresolver=letsencrypt"
```

## Automated Builds

Docker images are automatically built and published to GitHub Container Registry when:

- A new release is published on GitHub
- Manual workflow dispatch is triggered

Available tags:
- `latest`: Latest stable release
- `v{version}`: Specific version (e.g., `v2.3`)
- `v{major}.{minor}`: Minor version (e.g., `v2.3`)
- `v{major}`: Major version (e.g., `v2`)

## Support

For issues related to the Docker setup, please open an issue on GitHub.
