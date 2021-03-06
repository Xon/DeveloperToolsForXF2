#!/bin/sh

if [[ "$#" -ne 1 ]]; then
    echo "Require an add-on name"
    exit 1;
fi

if [[ ! -d "src/addons/$1" ]]; then
    echo "Expect src/addons/$1  to exist"
    exit 1;
fi

php cmd.php xf:addon-upgrade  "$1" -v
php cmd.php xf-addon:sync-json "$1" -v -f
php cmd.php xf-addon:build-release "$1" -v