#!/usr/bin/env bash
curl --user ${CIRCLE_TOKEN}: \
    --request POST \
    --form revision=43dc197f11aeb882124312a80c433d7163c93b29\
    --form config=./cercleci/config.yml \
    --form notify=false \
        https://circleci.com/api/v1.1/project/https://github.com/botefori/tuto-secured-dinosors/tree/master