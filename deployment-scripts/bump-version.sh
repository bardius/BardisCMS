#!/bin/bash

read -r -d '' usage <<EOF

Usage: ./$0 options

	-c, --cur-version           current version to update from e.g. 2.7.5
	-n, --new-version           version to update to e.g. 2.7.6
	--usage                     show usage
EOF

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
OPTIONS=$(getopt -o c:n: -l cur-version::,new-version::,usage:: -- "$@")
eval set -- "$OPTIONS"

# extract options and their arguments into variables.
while true; do
    case "$1" in
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

echo "******************************************"
echo "Updating Livin Circle website Release Version"
echo "Current Version: $CUR_VERSION"
echo "New Version: $NEW_VERSION"
echo "******************************************"

# function to bunp release version numbers
function bumpVersions() {
    local oldversion=$1
    local newversion=$2

    echo "Updating Release Version from $oldversion to $newversion"

    if [ -z "$oldversion" ]; then
        echoerr "Both Release Versions must be specified. Arguments missing old Release Version."
        return 1
    fi

    if [ -z "$newversion" ]; then
        echoerr "Both Release Versions must be specified. Arguments missing new Release Version."
        return 1
    fi

    # updating release version in required files
    sed -i'' -e "s/$oldversion/$newversion/g" ../README.md
    sed -i'' -e "s/$oldversion/$newversion/g" ../bower.json
    sed -i'' -e "s/$oldversion/$newversion/g" ../package.json
    sed -i'' -e "s/$oldversion/$newversion/g" ../app/config/config.yml
    sed -i'' -e "s/$oldversion/$newversion/g" ../src/BardisCMS/PageBundle/Listener/ResponseListener.php
    sed -i'' -e "s/$oldversion/$newversion/g" ../web/.htaccess

    echo "Release Version is now updated to version $newversion"

    return 0
}

if [ -z "CUR_VERSION" ] || [ -z "NEW_VERSION" ]; then
    echoerr "Current & target Release Versions must be specified. Arguments missing."
    exit 1
fi

bumpVersions $CUR_VERSION $NEW_VERSION;
