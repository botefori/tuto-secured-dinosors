#!/usr/bin/env bash
curl --user ${CIRCLE_TOKEN}: \
    --request POST \
    --form revision=5325c413ba4bc7b18dcdb5a6b245d4d862b299d0 \
    --form config=config.yml \
    --form notify=false \
        https://circleci.com/api/v1.1/project/https://github.com/botefori/tuto-secured-dinosors/tree/master