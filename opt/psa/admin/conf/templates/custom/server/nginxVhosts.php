<?php /** @var Template_VariableAccessor $VAR */ ?>
<?php /** @var array $OPT */ ?>
<?php /** @var Template_Variable_IpAddress $ipAddress */ ?>


<?php foreach ($VAR->server->ipAddresses->all as $ipAddress): ?>
server {
    listen <?php echo "{$ipAddress->escapedAddress}:{$OPT['frontendPort']}" .
        ($ipAddress->isIpV6 ? ' ipv6only=on' : '') .
        ($OPT['ssl'] ? ' ssl' : '') .
                      ( $OPT['ssl'] && $VAR->domain->physicalHosting->proxySettings['nginxHttp2'] ? ' http2' : '' ) ?>;

<?php if ($OPT['ssl']): ?>
<?php $sslCertificate = $ipAddress->sslCertificate; ?>
<?php   if ($sslCertificate->ce): ?>
    ssl_certificate             <?php echo $sslCertificate->ceFilePath ?>;
    ssl_certificate_key         <?php echo $sslCertificate->ceFilePath ?>;
<?php       if ($sslCertificate->ca): ?>
    ssl_client_certificate      <?php echo $sslCertificate->caFilePath ?>;
    ssl_trusted_certificate        <?php echo $sslCertificate->caFilePath ?>;
    ssl_stapling on;
    ssl_stapling_verify on;
<?php       endif ?>
<?php   endif ?>
<?php endif ?>
    add_header Strict-Transport-Security "max-age=63072000; includeSubDomains" always;
<?php echo $VAR->includeTemplate('service/nginxSitePreview.php') ?>

    location / {
<?php if ($OPT['ssl']): ?>
        proxy_pass https://<?php echo $ipAddress->proxyEscapedAddress . ':' . $OPT['backendPort']; ?>;
<?php else: ?>
        proxy_pass http://<?php echo $ipAddress->proxyEscapedAddress . ':' . $OPT['backendPort']; ?>;
<?php endif ?>
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    }
}

<?php endforeach; ?>
