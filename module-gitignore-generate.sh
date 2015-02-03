#!/bin/bash

#### 
# To Generate  .gitignore for git separate module in magento
####

SITEGITDIRECTORY=".git"
SITEGITIGNORE=".gitignore"

SITEBKPGITDIRECTORY=".bkp.git"
SITEBKPGITIGNORE=".bkp.gitignore"

NAMESPACE_SMALLER_CASE=""
MODULE_SMALLER_CASE=""

## make  lowercase
to_lowercase () 
{
	tmpVar=$1
	echo $tmpVar | tr '[:upper:]' '[:lower:]'
}

## make  capitalised
capitalise_first_char ()
{
	foo=$1	
	fl=${foo:0:1}
	f1=$1	
	if [[ ${fl} == [a-z] ]] 
		then	    
		ord=$(printf '%o' "'${fl}") 	    
		ord=$(( ord - 40 )) 
		fl=$(printf '\'${ord}) 
	fi	
	# echo $f1	
	echo "${fl}${foo:1}"
}

#- takes user variables -#
echo "You are here to generate  .gitignore and new .git repo for a separate magento module. "

echo "Enter namespace (Case sensitive):"
read NAMESPACE

echo "Enter Module Name (Case sensitive):"
read MODULENAME

echo "Enter codepool (Case sensitive)  Eg. local or community:"
read CODEPOOL

echo "Want to create default directories? Y/N:"
read DEFAULTDIRECTORIES


NAMESPACE_SMALLER_CASE=$(to_lowercase $MODULENAME)
MODULE_SMALLER_CASE=$(to_lowercase $MODULENAME)
CODEPOOL=$(to_lowercase $CODEPOOL)

NAMESPACE=$(to_lowercase $NAMESPACE)
MODULENAME=$(to_lowercase $MODULENAME)

NAMESPACE=$(capitalise_first_char $NAMESPACE)
MODULENAME=$(capitalise_first_char $MODULENAME)

if [[ -d "${SITEGITDIRECTORY}" && ! -L "${SITEGITDIRECTORY}" ]] ; then
    mv ${SITEGITDIRECTORY} ${SITEBKPGITDIRECTORY}
	echo "!!##YOUR CURRENT  ${SITEGITDIRECTORY} HAS BEEN RENAMED to ${SITEBKPGITDIRECTORY} ##!!"
fi

if [ -fi"${SITEGITIGNORE}" ]
then
    mv ${SITEGITIGNORE} ${SITEBKPGITIGNORE}
	echo "!!##YOUR CURRENT ${SITEGITIGNORE} HAS BEEN RENAMED to ${SITEBKPGITIGNORE} ##!!"
fi

# creates default needed directories #
if [[ $DEFAULTDIRECTORIES = "Y"  || $DEFAULTDIRECTORIES = "y" ]]
then
	
mkdir -p app/code/$CODEPOOL/$NAMESPACE/$MODULENAME/Block/
mkdir -p app/code/$CODEPOOL/$NAMESPACE/$MODULENAME/controllers/
mkdir -p app/code/$CODEPOOL/$NAMESPACE/$MODULENAME/etc/
mkdir -p app/code/$CODEPOOL/$NAMESPACE/$MODULENAME/Helper/

#mkdir -p app/code/$CODEPOOL/$NAMESPACE/$MODULENAME/Controller/
#mkdir -p app/code/$CODEPOOL/$NAMESPACE/$MODULENAME/Model/
#mkdir -p app/code/$CODEPOOL/$NAMESPACE/$MODULENAME/sql/

mkdir -p app/design/frontend/base/default/layout/$NAMESPACE_SMALLER_CASE/
mkdir -p app/design/frontend/base/default/template/$NAMESPACE_SMALLER_CASE/

mkdir -p app/design/adminhtml/default/default/layout/$NAMESPACE_SMALLER_CASE/
mkdir -p app/design/adminhtml/default/default/template/$NAMESPACE_SMALLER_CASE/

mkdir -p /js/$NAMESPACE_SMALLER_CASE/

mkdir -p /lib/$NAMESPACE_SMALLER_CASE/$MODULE_SMALLER_CASE/

mkdir -p /media/$NAMESPACE_SMALLER_CASE/$MODULE_SMALLER_CASE/

fi


touch $SITEGITIGNORE

cat > $SITEGITIGNORE << EOF
/*
!app/
/app/*
!app/code/
/app/code/*
!app/code/$CODEPOOL/
/app/code/$CODEPOOL/*
!app/code/$CODEPOOL/$NAMESPACE/
/app/code/$CODEPOOL/$NAMESPACE/*
!app/code/$CODEPOOL/$NAMESPACE/$MODULENAME

!app/design/
/app/design/*
!app/design/frontend/
/app/design/frontend/*
!app/design/frontend/base/
/app/design/frontend/base/*
!app/design/frontend/base/default/
/app/design/frontend/base/default/*

!app/design/frontend/base/default/template/
/app/design/frontend/base/default/template/*
!app/design/frontend/base/default/template/$NAMESPACE_SMALLER_CASE/
/app/design/frontend/base/default/template/$NAMESPACE_SMALLER_CASE/*
!app/design/frontend/base/default/template/$NAMESPACE_SMALLER_CASE/$MODULE_SMALLER_CASE/

!app/design/frontend/base/default/layout/
/app/design/frontend/base/default/layout/*
!app/design/frontend/base/default/layout/$NAMESPACE_SMALLER_CASE/$MODULE_SMALLER_CASE.xml


!app/design/adminhtml/
/app/design/adminhtml/*
!app/design/adminhtml/default/
/app/design/adminhtml/default/*
!app/design/adminhtml/default/default/
/app/design/adminhtml/default/default/*

!app/design/adminhtml/default/default/template/
/app/design/adminhtml/default/default/template/*
!app/design/adminhtml/base/default/template/$NAMESPACE_SMALLER_CASE/
/app/design/adminhtml/default/default/template/$NAMESPACE_SMALLER_CASE/*
!app/design/adminhtml/default/default/template/$NAMESPACE_SMALLER_CASE/$MODULE_SMALLER_CASE/

!app/design/adminhtml/default/default/layout/
/app/design/adminhtml/default/default/layout/*
!app/design/adminhtml/default/default/layout/$NAMESPACE_SMALLER_CASE/$MODULE_SMALLER_CASE.xml

!app/etc/
/app/etc/*
!app/etc/modules
/app/etc/modules/*
!app/etc/modules/$NAMESPACE_$MODULENAME.xml

!lib/
/lib/*
!/lib/$NAMESPACE_SMALLER_CASE/
/lib/$NAMESPACE_SMALLER_CASE/*
!/lib/$NAMESPACE_SMALLER_CASE/$MODULE_SMALLER_CASE/

!/media/
/media/*
!/media/$NAMESPACE_SMALLER_CASE/
/media/$NAMESPACE_SMALLER_CASE/*
!/media/$NAMESPACE_SMALLER_CASE/$MODULE_SMALLER_CASE/

!skin/
/skin/*
!skin/frontend/
/skin/frontend/*
!skin/frontend/base/
/skin/frontend/base/*
!skin/frontend/base/default/
/skin/frontend/base/default/*
!skin/frontend/base/default/$NAMESPACE_SMALLER_CASE/
/skin/frontend/base/default/$NAMESPACE_SMALLER_CASE/*
!skin/frontend/base/default/$NAMESPACE_SMALLER_CASE/$MODULE_SMALLER_CASE/

!/js/
/js/*
!/js/$NAMESPACE_SMALLER_CASE/
/js/$NAMESPACE_SMALLER_CASE/*
!/js/$NAMESPACE_SMALLER_CASE/$MODULE_SMALLER_CASE/

!/shell/
/shell/*
 
!$NAMESPACE_SMALLER_CASE_$MODULE_SMALLER_CASE_readme.md
!.gitignore


var/
downloader/
errors/
includes/
.idea/
.htaccess
.htaccess.sample
.phpstorm.meta.php
LICENSE.html
LICENSE.txt
LICENSE_AFL.txt
RELEASE_NOTES.txt
api.php
mage
php.ini.sample
pkginfo/
composer.json
composer.phar
cron.php
cron.sh
favicon.ico
get.php
index.php
index.php.sample
install.php
EOF


echo "$SITEGITIGNORE for your $NAMESPACE_$MODULENAME generated."