#!/bin/bash

#### 
# To Generate  .gitignore for git separate module in magento
####

SITEGITDIRECTORY=".git"
SITEGITIGNORE=".gitignore"

SITEBKPGITDIRECTORY=".site.git"
SITEBKPGITIGNORE=".site.gitignore"

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


echo "Choose any of these actions."
echo "Generate  .gitignore for a separate magento module: 1"
echo "Move separate magento module git and put back site repo: 2"
echo "Move site repo  git and put back  separate magento module: 3"
echo "Exit: 4"

echo "Enter your action (Proceed if you know what you are doing):"
read USERACTION

if [ $USERACTION -ne "4" ]
then

echo "Enter namespace (Case sensitive):"
read NAMESPACE

echo "Enter Module Name (Case sensitive):"
read MODULENAME

echo "Enter codepool (Case sensitive)  Eg. local or community:"
read CODEPOOL


fi


NAMESPACE_SMALLER_CASE=$(to_lowercase $NAMESPACE)
MODULE_SMALLER_CASE=$(to_lowercase $MODULENAME)
CODEPOOL=$(to_lowercase $CODEPOOL)

#NAMESPACE=$(to_lowercase $NAMESPACE)
#MODULENAME=$(to_lowercase $MODULENAME)

#NAMESPACE=$(capitalise_first_char $NAMESPACE)
#MODULENAME=$(capitalise_first_char $MODULENAME)

case $USERACTION in
	"1")
			
echo "Want to create default directories?[Make sure to check folder permissions after creating] Y/N:"
read DEFAULTDIRECTORIES



if [ ! -d "${SITEBKPGITDIRECTORY}" ] && [ ! -f  "${SITEBKPGITIGNORE}" ]
then
if [ -d "${SITEGITDIRECTORY}" ] ; then
    mv ${SITEGITDIRECTORY} ${SITEBKPGITDIRECTORY}
	echo "!!##YOUR CURRENT  ${SITEGITDIRECTORY} HAS BEEN RENAMED to ${SITEBKPGITDIRECTORY} ##!!"
fi

if [ -f "${SITEGITIGNORE}" ]
then
    mv ${SITEGITIGNORE} ${SITEBKPGITIGNORE}
	echo "!!##YOUR CURRENT ${SITEGITIGNORE} HAS BEEN RENAMED to ${SITEBKPGITIGNORE} ##!!"
fi

# creates default needed directories #
if [ "${DEFAULTDIRECTORIES}"="Y" ] && [ "${DEFAULTDIRECTORIES}"="y" ]
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
touch "${NAMESPACE_SMALLER_CASE}_${MODULE_SMALLER_CASE}_readme.md"

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
!app/etc/modules/${NAMESPACE}_${MODULENAME}.xml

!/lib/
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
 
!${NAMESPACE_SMALLER_CASE}_${MODULE_SMALLER_CASE}_readme.md
!.gitignore


var/
downloader/
/errors/
/includes/
.idea/
.htaccess.sample
.phpstorm.meta.php
LICENSE.html
LICENSE.txt
LICENSE_AFL.txt
RELEASE_NOTES.txt
/api.php
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


echo "${SITEGITIGNORE} for your ${NAMESPACE}_${MODULENAME} generated."
else 
echo "${SITEBKPGITDIRECTORY} or  ${SITEBKPGITIGNORE} already exist, please check to keep it safe."
fi

;;

	"2")
			echo "Moving module git"
			
			if [ ! -d ".${NAMESPACE_SMALLER_CASE}_${MODULE_SMALLER_CASE}.git" ] && [ ! -f  ".${NAMESPACE_SMALLER_CASE}_${MODULE_SMALLER_CASE}.gitignore" ]
			then
			mv ${SITEGITDIRECTORY} ".${NAMESPACE_SMALLER_CASE}_${MODULE_SMALLER_CASE}.git"
			mv ${SITEGITIGNORE} ".${NAMESPACE_SMALLER_CASE}_${MODULE_SMALLER_CASE}.gitignore"
			
			mv ${SITEBKPGITDIRECTORY}  ${SITEGITDIRECTORY}
			mv ${SITEBKPGITIGNORE}  ${SITEGITIGNORE}
			
			else 
			echo "${NAMESPACE_SMALLER_CASE}_${MODULE_SMALLER_CASE} related git files already exist, please check to keep it safe."
			fi
			;;
    "3")
			echo "Moving magento git for to pull/fetch the module to update"
			if [ ! -d "${SITEBKPGITDIRECTORY}" ] && [ ! -f  "${SITEBKPGITIGNORE}" ]
			then
			mv  ${SITEGITDIRECTORY} ${SITEBKPGITDIRECTORY}
			mv  ${SITEGITIGNORE} ${SITEBKPGITIGNORE}
			
			mv  ".${NAMESPACE_SMALLER_CASE}_${MODULE_SMALLER_CASE}.git" ${SITEGITDIRECTORY}
			mv  ".${NAMESPACE_SMALLER_CASE}_${MODULE_SMALLER_CASE}.gitignore" ${SITEGITIGNORE}
			
			else 
			echo "${SITEBKPGITDIRECTORY} or  ${SITEBKPGITIGNORE} already exist, please check to keep it safe."
			fi
			;;
	 "4")
			echo "Bye"
			
			;;
esac
