<?php /** @var Template_VariableAccessor $VAR */ ?>
<?php
if (!$VAR->domain->webmail->isActive) {
    echo "# Webmail is not enabled on the domain\n";
    return;
}
?>
<?php foreach ($VAR->domain->webmail->ipAddresses as $ipAddress): ?>
server {
    listen <?php echo $ipAddress->escapedAddress . ':' . $OPT['frontendPort'] . ( $OPT['ssl'] ? ' ssl' : '' ) .
	                  ( $OPT['ssl'] && $VAR->domain->physicalHosting->proxySettings['nginxHttp2'] ? ' http2' : '' ) ?>;
    server_name "webmail.<?php echo $VAR->domain->asciiName ?>";
    <?php foreach ($VAR->domain->mailAliases as $alias): ?>
    server_name  "webmail.<?php echo $alias->asciiName ?>";
    <?php endforeach; ?>

<?php if ($OPT['ssl']): ?>
<?php $sslCertificate = $VAR->server->sni && $VAR->domain->webmail->sslCertificate
        ? $VAR->domain->webmail->sslCertificate
        : $ipAddress->sslCertificate; ?>
<?php   if ($sslCertificate->ce): ?>
    ssl_certificate             <?php echo $sslCertificate->ceFilePath ?>;
    ssl_certificate_key         <?php echo $sslCertificate->ceFilePath ?>;
<?php       if ($sslCertificate->ca): ?>
    ssl_client_certificate      <?php echo $sslCertificate->caFilePath ?>;
    ssl_verify_depth 2;

    ssl_trusted_certificate     <?php echo $sslCertificate->caFilePath ?>;
    ssl_session_timeout 1d;
    ssl_session_cache shared:SSL:50m;
    ssl_session_tickets off;
    ssl_stapling on;
    ssl_stapling_verify on;
<?php       endif ?>
<?php   endif ?>
<?php endif ?>
    add_header Strict-Transport-Security "max-age=63072000; includeSubDomains" always;
    client_max_body_size 128m;

    location / {
<?php if ($OPT['ssl']): ?>
        proxy_pass https://<?php echo $ipAddress->proxyEscapedAddress . ':' . $OPT['backendPort'] ?>;
<?php else: ?>
        proxy_pass http://<?php echo $ipAddress->proxyEscapedAddress . ':' . $OPT['backendPort'] ?>;
<?php endif ?>
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    }
}

<?php endforeach; ?>
