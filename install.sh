#!/usr/bin/env bash

docker info > /dev/null 2>&1

# Ensure that Docker is running...
if [ $? -ne 0 ]; then
    echo "Docker is not running."

    exit 1
fi

docker run --rm \
    --pull=always \
    -v "$(pwd)":/opt \
    -w /opt \
    laravelsail/php82-composer:latest \
    bash -c "
    composer install && \
    php artisan key:generate && \
    curl -fsSL https://deb.nodesource.com/setup_20.x | bash - && apt-get install -y nodejs && \
    npm install && \
    npm run build \
    "

./vendor/bin/sail build

BOLD='\033[1m'
NC='\033[0m'

echo ""

if sudo -n true 2>/dev/null; then
    sudo chown -R $USER: .
    echo -e "${BOLD}Get started with:${NC} ./vendor/bin/sail up"
else
    echo -e "${BOLD}Please provide your password so we can make some final adjustments to your application's permissions.${NC}"
    echo ""
    sudo chown -R $USER: .
    echo ""
    echo -e "${BOLD}Thank you! Dive in with:${NC} ./vendor/bin/sail up"
fi
