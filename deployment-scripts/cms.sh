#!/usr/bin/bash

read -r -d '' usage <<EOF

Usage: ./$0 options

	-a, --action            action to execute [reset,version]
	--usage                 show usage
EOF

START_TIME=`date +%s`

# function to print errors
function echoerr() {
    local msg=$1
    local displayUsage=$2

    if [ -z "$displayUsage" ]; then
        displayUsage=1
    fi

    echo "ERROR: $msg" 1>&2
    if [ "$displayUsage" != "0" ]; then
        echo "$usage" 1>&2
    fi
}

if [ "$#" -lt 1 ]; then
    echoerr "Incorrect number of arguments specified."
    exit 1
fi

# read the options
OPTIONS=$(getopt -o a: -l action::,usage::,cur-version::,new-version:: -- "$@")
eval set -- "$OPTIONS"

# extract options and their arguments into variables.
while true; do
    case "$1" in
	-a|--action)
	    ACTION=$2; shift ;;
	-c|--cur-version)
	    CUR_VERSION=$2; shift ;;
	-n|--new-version)
	    NEW_VERSION=$2; shift ;;
	--usage)
	    echo "$usage"; exit 0 ;;
	--)
	    shift; break ;;
	*)
	    echoerr "Invalid option specified."; exit 1 ;;
    esac
    shift
done

# function to reset the CMS
function cms_reset() {

    echo "******************************************"
    echo "Resetting Livin Circle website"
    echo "******************************************"

    php ../app/console cache:clear -e=dev --no-debug --no-warmup
    php ../app/console cache:warmup -e=dev --no-debug
    php ../app/console cache:clear -e=prod --no-debug --no-warmup
    php ../app/console cache:warmup -e=prod --no-debug

    php ../app/console doctrine:schema:drop --force
    php ../app/console doctrine:schema:create
    php ../app/console doctrine:fixtures:load --append

    php ../app/console sonata:media:sync sonata.media.provider.image default
    php ../app/console sonata:media:sync sonata.media.provider.image intro
    php ../app/console sonata:media:sync sonata.media.provider.image bgimage
    php ../app/console sonata:media:sync sonata.media.provider.image icon
    php ../app/console sonata:media:sync sonata.media.provider.image admin

    php ../app/console assets:install ../web/ --env=prod --no-debug
    php ../app/console assetic:dump --env=dev --no-debug
    php ../app/console assetic:dump --env=prod --no-debug

    echo "******************************************"
    echo "Livin Circle website has been reset"
    echo "******************************************"

    return 0
}

if [ -z "ACTION" ]; then
    echoerr "An action must be specified. Argument missing."
    exit 1
fi

case "$ACTION" in
	reset)
            cms_reset
            ;;
	version)
            ./bump-version.sh --cur-version=$CUR_VERSION --new-version=$NEW_VERSION
            ;;
esac

END_TIME=`date +%s`

TOTAL_RUNTIME=$((END_TIME-START_TIME))

function displayTime {
    print '$ACTION was completed in '
    local T=$1
    local D=$((T/60/60/24))
    local H=$((T/60/60%24))
    local M=$((T/60%60))
    local S=$((T%60))
    (( $D > 0 )) && printf '%d days ' $D
    (( $H > 0 )) && printf '%d hours ' $H
    (( $M > 0 )) && printf '%d minutes ' $M
    (( $D > 0 || $H > 0 || $M > 0 )) && printf 'and '
    printf '%d seconds\n' $S
}

displayTime $TOTAL_RUNTIME
