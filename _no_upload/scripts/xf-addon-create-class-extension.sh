#!/bin/sh

if [[ "$#" -ne 2 ]]; then
    echo "Require an add-on name and class path"
    exit 1;
fi

if [[ ! -d "src/addons/$1" ]]; then
    echo "Expect src/addons/$1  to exist"
    exit 1;
fi

php cmd.php -v tdt:create-class-extension "$1" "$2"