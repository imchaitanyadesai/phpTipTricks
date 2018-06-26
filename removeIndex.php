//For wamp

RewriteEngine On
RewriteBase /pg/YourLifeRockApp/
RewriteCond %{REQUEST_URI} ^system.*
RewriteRule ^(.*)$ /index.php/$1 [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L]
