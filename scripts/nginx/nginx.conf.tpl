worker_processes  1;

events {
    worker_connections  1024;
}

http {
    include       mime.types;
    default_type  application/octet-stream;

    gzip  on;

    server {
        listen 80;
        listen 443 ssl;
        server_name localhost;
        root $TRAVIS_BUILD_DIR/public;

        index index.html index.htm index.php;

        charset utf-8;

        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }

        location = /favicon.ico { access_log off; log_not_found off; }
        location = /robots.txt  { access_log off; log_not_found off; }

        access_log off;
        error_log  /var/log/nginx/iserveu.local-error.log error;

        sendfile off;

        client_max_body_size 100m;

        location ~ \.php$ {
            fastcgi_split_path_info ^(.+\.php)(/.+)$;
            fastcgi_pass unix:/var/run/php/php7.0-fpm.sock;
            fastcgi_index index.php;
            include fastcgi_params;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;

            fastcgi_intercept_errors off;
            fastcgi_buffer_size 16k;
            fastcgi_buffers 4 16k;
            fastcgi_connect_timeout 300;
            fastcgi_send_timeout 300;
            fastcgi_read_timeout 300;
        }

        location ~ /\.ht {
            deny all;
        }

        ssl_certificate     /etc/nginx/ssl/iserveu.local.crt;
        ssl_certificate_key /etc/nginx/ssl/iserveu.local.key;
    }
}
