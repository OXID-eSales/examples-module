#!/bin/bash

# Flags possible:
# -e for shop edition. Possible values: CE/PE/EE
# -b branch of deprecated tests repositories

while getopts e:b:u: flag; do
  case "${flag}" in
  e) edition=${OPTARG} ;;
  b) branch=${OPTARG} ;;
  *) ;;
  esac
done

SHARED_SCRIPT_PATH=$(dirname $0)

echo -e "\033[1;37m\033[1;42mRequire demodata package: Edition: ${edition}, Branch: ${branch}\033[0m\n"

if [ -z ${edition+x} ] || [ -z ${branch+x} ]; then
  echo -e "\e[1;31mThe edition (-e) and branch (-b) are required for require_demodata_package.sh\e[0m"
  exit 1
fi

if [ $edition = "CE" ]; then
  $SHARED_SCRIPT_PATH/require.sh -n"oxid-esales/oxideshop-demodata-ce" -g"https://github.com/OXID-eSales/oxideshop_demodata_ce" -v"dev-${branch}"
fi

if [ $edition = "PE" ]; then
  $SHARED_SCRIPT_PATH/require.sh -n"oxid-esales/oxideshop-demodata-pe" -g"https://github.com/OXID-eSales/oxideshop_demodata_pe" -v"dev-${branch}"
fi

if [ $edition = "EE" ]; then
  $SHARED_SCRIPT_PATH/require.sh -n"oxid-esales/oxideshop-demodata-ee" -g"https://github.com/OXID-eSales/oxideshop_demodata_ee" -v"dev-${branch}"
fi
