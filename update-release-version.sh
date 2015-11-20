#!/bin/bash

read -r -d '' usage <<EOF

Usage: ./$0 options

	-ov, --old-version        old version to update from e.g. 2.7.5
	-nv, --new-version        old version to update to e.g. 2.7.6
	--usage                   show usage
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
OPTIONS=$(getopt -o ov:nv: -l old-version::,new-version:: -- "$@")
eval set -- "$OPTIONS"

# extract options and their arguments into variables.
while true; do
    case "$1" in
	-ov|--old-version)
	    OLD_VERSION=$2; shift ;;
	-nv|--new-version)
	    NEW_VERSION=$2; shift ;;
	--usage)
	    echo "$USAGE"; exit 0 ;;
	--)
	    shift; break ;;
	*)
	    echoerr "Invalid option specified."; exit 1 ;;
    esac
    shift
done

echo "******************************************"
echo "Updating BardisCMS Release Version"
echo "OLD_VERSION: $OLD_VERSION"
echo "NEW_VERSION: $NEW_VERSION"
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
    sed -i'' -e "s/$oldversion/$newversion/g" nextBuildNumber
    sed -i'' -e "s/$oldversion/$newversion/g" bower.json
    sed -i'' -e "s/$oldversion/$newversion/g" package.json
    sed -i'' -e "s/$oldversion/$newversion/g" README.md

    echo "Release Version is now updated to version $newversion"

    return 0
}

if [ -z "OLD_VERSION" ] || [ -z "NEW_VERSION" ]; then
    echoerr "Release Versions must be specified. Arguments missing."
    exit 1
fi

bumpVersions $OLD_VERSION $NEW_VERSION;
