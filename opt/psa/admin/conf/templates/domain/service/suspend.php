<IfModule mod_rewrite.c>
    RewriteEngine on
    RewriteCond %{ENV:REDIRECT_STATUS} !=503
    RewriteCond %{REQUEST_URI} !\.(jpe?g?|png|gif|ico|css)$ [NC]
    RewriteCond %{REQUEST_URI} !robots\.txt$ [NC]
    RewriteRule ^ - [L,R=503]
</IfModule>
