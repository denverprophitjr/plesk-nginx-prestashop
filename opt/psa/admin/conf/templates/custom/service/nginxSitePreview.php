<?php /** @var Template_VariableAccessor $VAR */ ?>
location ^~ /plesk-site-preview/ {
    proxy_pass <?php echo $VAR->server->sitePreviewAddress ?>;
    proxy_set_header Host             plesk-site-preview.local;
    proxy_set_header X-Real-IP        $remote_addr;
    proxy_set_header X-Forwarded-For  $proxy_add_x_forwarded_for;
    proxy_cookie_domain plesk-site-preview.local $host;
    access_log off;
}
