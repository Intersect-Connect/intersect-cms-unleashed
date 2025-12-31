#!/bin/bash

# Docker Build Script for Intersect CMS
# Usage: ./build-docker.sh [version] [--push]

VERSION=${1:-latest}
PUSH=${2}
REGISTRY="ghcr.io"
IMAGE_NAME="nerdscorp/intersect-cms"
FULL_TAG="${REGISTRY}/${IMAGE_NAME}:${VERSION}"

echo "Building Docker image: ${FULL_TAG}"

# Build the image
docker buildx build \
  --platform linux/amd64,linux/arm64 \
  --target production \
  -t "${FULL_TAG}" \
  .

if [ "$PUSH" == "--push" ]; then
  echo "Pushing image to registry..."
  docker buildx build \
    --platform linux/amd64,linux/arm64 \
    --target production \
    -t "${FULL_TAG}" \
    --push \
    .
  echo "Successfully pushed ${FULL_TAG}"
else
  echo "Build complete! Image tagged as: ${FULL_TAG}"
  echo "To push to registry, run: ./build-docker.sh ${VERSION} --push"
fi
