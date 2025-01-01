#!/bin/bash

RED='\033[0;31m'
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
CYAN='\033[0;36m'
NC='\033[0m'
BOLD='\033[1m'

progressBar() {
    local duration=$1
    local width=50
    local progress=0
    local step=$((width * 100 / (duration * 100)))

    printf "${BLUE}["
    while [ $progress -lt $width ]; do
        printf "▓"
        progress=$((progress + step))
        sleep 0.1
    done
    printf "]${NC}\n"
}

obviousEcho() {
    echo -e "${CYAN}${BOLD}$1${NC}"
    progressBar 2
}

echo -e "\n${YELLOW}${BOLD}Starting Unhinged Build Process, oh dear.${NC}\n"

if ! docker info > /dev/null 2>&1; then
    echo -e "${RED}${BOLD}The whales are inert - please start docker.${NC}"
    exit 1
fi

if [ ! -f .env ]; then
    obviousEcho "No .env detected! Creating one for you..."
    cp .env.example .env
    php artisan key:generate
    echo -e "${GREEN}✓ .env file created${NC}"
fi

obviousEcho "Installing initial Laravel requirements"
composer install

obviousEcho "Setting up Sail"
composer require laravel/sail --dev

obviousEcho "Containers, containers, where art thou (booting, they're booting.)"
./vendor/bin/sail up -d

obviousEcho "Giving the whales a moment to get unhinged..."
echo -e "${YELLOW}Please wait while the containers settle...${NC}"
progressBar 10

obviousEcho "Checking if MySQL is ready..."
while ! ./vendor/bin/sail exec mysql mysqladmin ping -h"localhost" -u"sail" -p"password" --silent; do
    echo -e "${YELLOW}Waiting for MySQL to be ready...${NC}"
    sleep 3
done

obviousEcho "Running Composer Things."
./vendor/bin/sail composer install

obviousEcho "Setting up the unhinged database. you'll need to give it some time to populate tickets, however,"
./vendor/bin/sail artisan migrate:fresh --seed

obviousEcho "Installing packages"
npm install
obviousEcho "putting the front end together, nearly there."
npm run build

obviousEcho "Setting up the ticket scheduler"
nohup ./vendor/bin/sail artisan schedule:work &

echo -e "\n${GREEN}${BOLD}Build complete! you can now visit http://yeslocalhost:8080${NC}"
echo -e "${YELLOW}${BOLD}or better yet, go to https://unhinged.inski.io for a better experience${NC}\n"