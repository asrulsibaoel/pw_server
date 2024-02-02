# PHP versions
# https://www.php.net/supported-versions.php

# Create and use CrossBuilder to be able to build for amd64 and arm64
docker buildx use CrossBuilder || (docker buildx create CrossBuilder && docker buildx use CrossBuilder)

#docker buildx build --platform linux/amd64,linux/arm64 -f Dockerfile -t zouloux/docker-debian-apache-php:8.3 --build-arg IMAGE_PHP_VERSION=8.3 --push .

versions=(
  7.2 7.3 7.4
  8.0 8.1 8.2 8.3 8.4
)

for version in "${versions[@]}"; do
  docker buildx build --platform linux/amd64,linux/arm64 \
    -f Dockerfile \
    -t "zouloux/docker-debian-apache-php:${version}" \
    --build-arg IMAGE_PHP_VERSION="${version}" \
    --push .
done
