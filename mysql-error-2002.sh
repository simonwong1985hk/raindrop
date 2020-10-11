brew uninstall mysql
rm -rf /usr/local/var/mysql
rm -rf /usr/local/etc/my.cnf

brew install mysql
brew link --force mysql
brew services start mysql
