# Docker Documentation for Intersect CMS

## Quick Start

### Local Development with Docker Compose

```bash
# Start all services (app, database, phpMyAdmin)
docker-compose up -d

# View logs
docker-compose logs -f app

# Stop services
docker-compose down
```

The application will be available at:
- **App**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081

## Building Docker Images

### Option 1: Using the Build Script (Recommended)

```bash
# Build locally (no push)
./build-docker.sh 1.0

# Build and push to GitHub Container Registry
./build-docker.sh 1.0 --push
```

### Option 2: Manual Docker Build

#### For Local Use Only (No Registry)
```bash
docker build -t intersect-cms:1.0 --target production .
```

#### For Pushing to GitHub Container Registry
```bash
# Full tag format is required: registry/organization/image:version
docker build -t ghcr.io/nerdscorp/intersect-cms:1.0 --target production .

# Login to GitHub Container Registry
echo $GITHUB_TOKEN | docker login ghcr.io -u YOUR_USERNAME --password-stdin

# Push the image
docker push ghcr.io/nerdscorp/intersect-cms:1.0
```

#### Multi-platform Build (AMD64 + ARM64)
```bash
# Create buildx builder (first time only)
docker buildx create --name mybuilder --use

# Build for multiple platforms and push
docker buildx build \
  --platform linux/amd64,linux/arm64 \
  --target production \
  -t ghcr.io/nerdscorp/intersect-cms:1.0 \
  --push \
  .
```

## Understanding the Tag Error

**Error**: `ERROR: failed to build: tag is needed when pushing to registry`

**Cause**: When pushing to a registry, Docker needs the **full image reference** including:
1. Registry URL (e.g., `ghcr.io`)
2. Organization/Username (e.g., `nerdscorp`)
3. Image name (e.g., `intersect-cms`)
4. Version tag (e.g., `1.0`)

**Wrong** ❌:
```bash
docker build -t 1.0 --push .
docker build -t intersect-cms:1.0 --push .
```

**Correct** ✅:
```bash
docker build -t ghcr.io/nerdscorp/intersect-cms:1.0 .
docker push ghcr.io/nerdscorp/intersect-cms:1.0
```

## GitHub Actions Automated Builds

The repository includes automated Docker builds via GitHub Actions:

### On Release (Recommended)
```bash
# Create a new release on GitHub
gh release create v1.0.0 --title "Release 1.0.0" --notes "Release notes"
```

This automatically builds and pushes:
- `ghcr.io/nerdscorp/intersect-cms:1.0.0`
- `ghcr.io/nerdscorp/intersect-cms:1.0`
- `ghcr.io/nerdscorp/intersect-cms:1`
- `ghcr.io/nerdscorp/intersect-cms:latest` (if main branch)

### Manual Workflow Dispatch
```bash
# Trigger manual build via GitHub CLI
gh workflow run docker-release.yml -f tag=custom-tag
```

## Environment Variables

Configure the application using environment variables in `.env` file or docker-compose:

```env
APP_ENV=prod
APP_SECRET=your-random-secret-here
DATABASE_URL=mysql://user:password@db:3306/database
MAILER_DSN=smtp://localhost
```

## Production Deployment

### Pull and Run from Registry

```bash
# Pull the image
docker pull ghcr.io/nerdscorp/intersect-cms:1.0

# Run with environment variables
docker run -d \
  -p 8080:80 \
  -e APP_ENV=prod \
  -e APP_SECRET=your-secret \
  -e DATABASE_URL=mysql://user:pass@host:3306/db \
  --name intersect-cms \
  ghcr.io/nerdscorp/intersect-cms:1.0
```

### Using Docker Compose with Registry Image

Update `docker-compose.yml` to use the registry image instead of building:

```yaml
services:
  app:
    image: ghcr.io/nerdscorp/intersect-cms:1.0
    # Remove the 'build' section
```

## Troubleshooting

### Cannot push to registry
- Ensure you're logged in: `docker login ghcr.io`
- Check you have write permissions to the repository
- Verify the full tag includes the registry URL

### Build fails
- Check Dockerfile syntax
- Ensure all COPY sources exist
- Review build logs for specific errors

### Container won't start
- Check logs: `docker logs intersect-cms-app`
- Verify environment variables are set correctly
- Ensure database is accessible and running
