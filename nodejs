curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.34.0/install.sh | bash
(https://github.com/creationix/nvm)

nvm install stable

node -v

npm -v

#.htaccess
RewriteEngine On
RewriteRule ^$ http://127.0.0.1:80/ [P,L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ http://127.0.0.1:80/$1 [P,L]

node app.js &
