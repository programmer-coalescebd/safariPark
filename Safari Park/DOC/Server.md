###Server configuration
You can host this portal on an `apache` or `nginx` based web server. `Nginx` is preferable because `nginx` process rewrite rules faster than `apache`. Web server requires `PHP 5.5` or above and `MySQL 5.5` above or `MariaDB 10` above.

####Additional Apache Components
`mod_rewrite`

`mod_headers`

`ssl` For SSL

`mod_expires` Optional

`mod_deflate` Optional

`mod_gzip` Optional


####Additional PHP Components
`PHP Iconv` PHP7 already has this

`PHP Image GD`

`PHP Curl`

`PHP PDO` PHP7 already has this

`PHP FPM` For Nginx


**All these above modules (apache + PHP) are already available on cPanel based hostings. For cPanel you have to use sub-domain rather than sub directory. For cloud based Linux or Unix servers you have to compile/install modules yourself.**

For Unix based server you can use `homebrew` as package installer. And linux server will require root access to install modules.


####Apache Rewrite Rules
```
RewriteEngine On
RewriteBase /

RewriteRule ^classes/(.*)$ 404.php [R=404,L]
RewriteRule ^api/v2/([a-zA-Z]+)$ includes/api/8a5da52ed126447d359e70c05721a8aa.php?$1 [NC,L]
RewriteRule ^api/v2/([a-zA-Z]+)/(.*)$ includes/api/8a5da52ed126447d359e70c05721a8aa.php?$1&token=$2 [NC,L]
RewriteRule ^confirm/(.*)$ index.php?action=confirm&for=$1 [NC,L]
RewriteRule ^reset/(.*)$ index.php?action=reset&for=$1 [NC,L]
RewriteRule ^payment/([a-zA-Z0-9]+)/(.*)$ index.php?action=payment&for=$1&job=$2 [NC,L]
RewriteRule ^admin/([a-zA-Z]+)$ index.php?action=admin&for=$1 [NC,L]
RewriteRule ^admin/([a-zA-Z]+)/(.*)$ index.php?action=admin&for=$1&job=$2 [NC,L]
RewriteRule ^admin index.php?action=admin [NC,L]
RewriteRule ^gate/([a-zA-Z]+)$ index.php?action=gate&for=$1 [NC,L]
RewriteRule ^gate/([a-zA-Z]+)/(.*)$ index.php?action=gate&for=$1&job=$2 [NC,L]
RewriteRule ^gate index.php?action=gate [NC,L]
```

####Nginx Rewrite Rules
```
rewrite ^/classes/(.*)$ /404.php redirect;
rewrite ^/api/v2/([a-zA-Z]+)$ /includes/api/8a5da52ed126447d359e70c05721a8aa.php?$1 last;
rewrite ^/api/v2/([a-zA-Z]+)/(.*)$ /includes/api/8a5da52ed126447d359e70c05721a8aa.php?$1&token=$2 last;
rewrite ^/confirm/(.*)$ /index.php?action=confirm&for=$1 last;
rewrite ^/reset/(.*)$ /index.php?action=reset&for=$1 last;
rewrite ^/payment/([a-zA-Z0-9]+)/(.*)$ /index.php?action=payment&for=$1&job=$2 last;
rewrite ^/admin/([a-zA-Z]+)$ /index.php?action=admin&for=$1 last;
rewrite ^/admin/([a-zA-Z]+)/(.*)$ /index.php?action=admin&for=$1&job=$2 last;
rewrite ^/admin /index.php?action=admin last;
rewrite ^/gate/([a-zA-Z]+)$ /index.php?action=gate&for=$1 last;
rewrite ^/gate/([a-zA-Z]+)/(.*)$ /index.php?action=gate&for=$1&job=$2 last;
rewrite ^/gate /index.php?action=gate last;
```


**Make sure to change configurations on `config.php` and set `content` directory writable and import the SQL file (`work.sql`).**