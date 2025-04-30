#!/bin/bash

# Flags possible:
# -e for edition. Possible values: CE/PE/EE
# -b for theme repository branch
# -d for dev environment (repo will be cloned)

while getopts e:b:d flag; do
  case "${flag}" in
  e) edition=${OPTARG} ;;
  b) branch=${OPTARG} ;;
  d) dev=1 ;;
  *) ;;
  esac
done

SHARED_SCRIPT_PATH=$(dirname $0)

if [ -z ${edition+x} ] || [ -z ${branch+x} ]; then
  echo -e "\e[1;31mThe edition (-e) and branch (-b) are required for require_twig_components.sh\e[0m"
  exit 1
fi

echo -e "\033[1;37m\033[1;42mEdition: ${edition}, Branch: ${branch}, Dev: ${dev}\033[0m\n"

# Configure twig themes in composer
$SHARED_SCRIPT_PATH/require.sh -n"oxid-esales/twig-component" -g"https://github.com/OXID-eSales/twig-component" -v"dev-${branch}" -a"--no-interaction"

if [ $edition = "PE" ] || [ $edition = "EE" ]; then
  $SHARED_SCRIPT_PATH/require.sh -n"oxid-esales/twig-component-pe" -g"https://github.com/OXID-eSales/twig-component-pe" -v"dev-${branch}"
fi

if [ $edition = "EE" ]; then
  $SHARED_SCRIPT_PATH/require.sh -n"oxid-esales/twig-component-ee" -g"https://github.com/OXID-eSales/twig-component-ee" -v"dev-${branch}"
fi

if [[ "$dev" -eq "1" ]]; then
  "$(dirname $0)/require_theme_dev.sh" -t"twig-admin" -b"${branch}"
else
  "$(dirname $0)/require_theme.sh" -t"twig-admin" -b"${branch}"
fi

