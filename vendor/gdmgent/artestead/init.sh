#!/usr/bin/env bash

if [[ -n "$1" ]]; then
    cp -i resources/Artestead.json Artestead.json
else
    cp -i resources/Artestead.yaml Artestead.yaml
fi

cp -i resources/after.sh after.sh
cp -i resources/aliases.sh aliases.sh

echo "Artestead initialized!"
