<?php if ($VAR->server->webserver->apache->traceEnableCompliance): ?>
        <IfModule mod_rewrite.c>
            RewriteEngine On
            RewriteCond %{REQUEST_METHOD} ^TRACE
            RewriteRule .* - [F]
        </IfModule>
<?php endif; ?>
