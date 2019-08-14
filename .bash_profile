# PATH
export PATH="~/.composer/vendor/bin:/usr/local/sbin:$PATH"

# Alias
alias ll='ls -alhG'

alias grep='grep -irn --color=auto'

# IP Address
ip() {
	dig +short myip.opendns.com @resolver1.opendns.com
}

# DNS Information
dns() {
	open https://who.is/dns/$1
}

# Empty trash
empty() {
	rm -rf ~/.Trash/*
}

# Go to phpMyAdmin
db() {
	open http://phpmyadmin.test/
}

# cPanel SSH Access
DOMAIN() {
	ssh USERNAME@IP_ADDRESS -i ~/.ssh/SSH_KEY
}

# Go to GitHub
github() {
	open https://github.com/simonwong1985hk
}


# Go to Sites
go() {
	cd /Users/simon/Sites
	pwd
}

# Create Database
create() {
	if [ -z $1 ]; then

		echo 'For example: create DATABASE_NAME* DATABASE_USER DATABASE_PASSWORD'

	else

		username=${2:-root}
		password=${3:-root}

		mysql -u$username -p$password -e "DROP DATABASE IF EXISTS $1; CREATE DATABASE IF NOT EXISTS $1;"

		echo "Database $1 Created."

	fi
}

# Drop Database
drop() {
	if [ -z $1 ]; then

		echo 'For example: drop DATABASE_NAME* DATABASE_USER DATABASE_PASSWORD'

	else

		username=${2:-root}
		password=${3:-root}

		mysql -u$username -p$password -e "DROP DATABASE IF EXISTS $1;"

		echo "Database $1 Dropped."

	fi
}

# Deploy Project
up() {

	# If the second parameter is omitted, use the first parameter as default value.
	if [ -z "$2" ]; then

		project=$1

	else

		project=$2

	fi

	# Deploy Laravel
	if [ "$1" == "laravel" ]; then

		# Create Database
		create $project

		go

		if [ -d "$project" ]; then

			rm -rf $project

		fi

		composer create-project laravel/laravel $project

		cd $project

		php artisan --version

		subl .

		open http://$project.test

	# Deploy WordPress
	elif [ "$1" == "wordpress" ]; then

		# Create Database
		create $project

		go

		if [ -d "$project" ]; then

			rm -rf $project

		fi

		mkdir $project

		cd $project

		wp core download

		wp core version

		wp core config --dbname=$project --dbuser=root --dbpass=root

		wp core install --url=$project.test --title=WordPress --admin_user=admin --admin_password=passw0rd --admin_email=admin@admin.com

		subl .

		open http://$project.test
		open http://$project.test/admin

		sudo rm -rf /var/mail/project

	# Deploy Magento 1
	elif [ "$1" == "magento1" ]; then

		go

		if [ -d "$project" ]; then

			rm -rf $project

			git clone https://github.com/simonwong1985hk/magento1.git $project

			cd $project

		else

			git clone https://github.com/simonwong1985hk/magento1.git $project

			cd $project

		fi

		# Create Database
		create $project

		php install.php --license_agreement_accepted yes \
		--locale en_US --timezone "Asia/Hong_Kong" --default_currency HKD \
		--db_host localhost --db_name $project --db_user root --db_pass root \
		--db_prefix "" \
		--url "http://$project.test/" --use_rewrites yes \
		--use_secure no --secure_base_url "" --use_secure_admin no \
		--admin_lastname admin --admin_firstname admin --admin_email "admin@admin.com" \
		--admin_username admin --admin_password passw0rd \
		--encryption_key ""

		php shell/indexer.php reindexall

		subl .

		open http://$project.test
		open http://$project.test/admin

	# Deploy Magento 1 with sample data
	elif [ "$1" == "magento1demo" ]; then

		go

		if [ -d "$project" ]; then

			rm -rf $project

			git clone https://github.com/simonwong1985hk/magento1-demo.git $project

			cd $project

		else

			git clone https://github.com/simonwong1985hk/magento1-demo.git $project

			cd $project

		fi

		# Create Database
		mysql -u root -p"root" <<EOF

		DROP DATABASE IF EXISTS $project;

		CREATE DATABASE IF NOT EXISTS $project;

		USE $project;

		SOURCE magento_sample_data_for_1.9.2.4.sql;

		EXIT
EOF

		php install.php --license_agreement_accepted yes \
		--locale en_US --timezone "Asia/Hong_Kong" --default_currency HKD \
		--db_host localhost --db_name $project --db_user root --db_pass root \
		--db_prefix "" \
		--url "http://$project.test/" --use_rewrites yes \
		--use_secure no --secure_base_url "" --use_secure_admin no \
		--admin_lastname admin --admin_firstname admin --admin_email "admin@admin.com" \
		--admin_username admin --admin_password passw0rd \
		--encryption_key ""

		php shell/indexer.php reindexall

		subl .

		open http://$project.test
		open http://$project.test/admin

	# Deploy Magento 2
	elif [ "$1" == "magento2" ]; then

		# Create Database
		create $project

		go

		if [ -d "$project" ]; then

			rm -rf $project

		fi

		git clone https://github.com/simonwong1985hk/magento2.git $project

		cd $project/bin

		php magento setup:install --use-rewrites=1 \
		--base-url=http://$project.test/ --backend-frontname=admin \
		--language=en_US --currency=HKD --timezone=Asia/Hong_Kong \
		--db-host=localhost --db-name=$project --db-user=root --db-password=root \
		--admin-firstname=admin --admin-lastname=admin --admin-email=admin@admin.com --admin-user=admin --admin-password=passw0rd

		php magento indexer:reindex

		php magento cache:flush

		php magento --version

		cd ..

		subl .

		open http://$project.test
		open http://$project.test/admin

	# Deploy Magento 2 with sample data
	elif [ "$1" == "magento2demo" ]; then

		# Create Database
		create $project

		go

		if [ -d "$project" ]; then

			rm -rf $project

		fi

		git clone https://github.com/simonwong1985hk/magento2-demo.git $project

		cd $project/bin

		php magento setup:install --use-rewrites=1 \
		--base-url=http://$project.test/ --backend-frontname=admin \
		--language=en_US --currency=HKD --timezone=Asia/Hong_Kong \
		--db-host=localhost --db-name=$project --db-user=root --db-password=root \
		--admin-firstname=admin --admin-lastname=admin --admin-email=admin@admin.com --admin-user=admin --admin-password=passw0rd

		php magento indexer:reindex

		php magento cache:flush

		php magento --version

		cd ..

		subl .

		open http://$project.test
		open http://$project.test/admin

	elif [ "$1" == "help" ]; then

		echo 'up $1 $2'

		echo '$1 = laravel,wordpress,magento1,magento2,magento1demo,magento2demo'

		echo '$2 = PROJECT_NAME'

	fi
}

# Destroy Project
down() {
	if [ -z "$1" ]; then
		echo 'For example: down PROJECT_NAME'
	else
		# Drop Codebase
		go

		if [ -d "$1" ]; then
			rm -rfv $1
		fi

		# Drop Database
		drop $1
	fi
}
