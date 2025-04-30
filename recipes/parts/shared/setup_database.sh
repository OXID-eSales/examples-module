SHARED_SCRIPT_PATH=$(dirname $0)
DEMODATA=1
CONSOLE_PATH=$( [ -e "source/bin/oe-console" ] && echo "bin/oe-console" || echo "vendor/bin/oe-console" )

#Pass arguments to the script
flags()
{
    while test $# -gt 0
    do
        case "$1" in
        -n|--no-demodata)
            DEMODATA=0
            ;;
        esac

        # and here we shift to the next argument
        shift
    done
}
flags "$@"

echo -e "\033[1;37m\033[1;42mSetup shop\033[0m\n"

# Set DIRECTORY_PATH based on the existence of /var/www/source/tmp/
if [ -d "/var/www/source/tmp/" ]; then
    DIRECTORY_PATH="/var/www/source/tmp"
else
    DIRECTORY_PATH="/var/www/var/cache"
fi

docker compose exec -T php sh -c "if [ ! -d '${COMPILE_DIRECTORY:-${DIRECTORY_PATH}}' ]; then mkdir -p '${COMPILE_DIRECTORY:-${DIRECTORY_PATH}}'; fi"

docker compose exec php ${CONSOLE_PATH} oe:setup:shop  --db-host=${MYSQL_HOST:-mysql} --db-port=${MYSQL_PORT:-3306} \
  --db-name=${MYSQL_DATABASE:-example} --db-user=${MYSQL_USER:-root} --db-password=${MYSQL_ROOT_PASSWORD:-root} \
  --shop-url=${SHOP_URL:-http://localhost.local/} --shop-directory=${SHOP_DIRECTORY:-/var/www/source/} \
  --compile-directory="${COMPILE_DIRECTORY:-${DIRECTORY_PATH}}"

$SHARED_SCRIPT_PATH/reset_database.sh

if [[ $DEMODATA -eq 1 ]]; then
  docker compose exec -T php ${CONSOLE_PATH} oe:setup:demodata
fi
