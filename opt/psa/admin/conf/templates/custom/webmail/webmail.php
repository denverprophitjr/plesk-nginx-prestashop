<?php echo AUTOGENERATED_CONFIGS; ?>
<?php /** @var Template_VariableAccessor $VAR */ ?>
<?php
if (!$VAR->domain->webmail->isActive) {
    echo "# Webmail is not enabled on the domain\n";
    return;
}
?>
SSLStaplingCache shmcb:/var/run/ocsp(128000)
<?php foreach ($VAR->domain->webmail->ipAddresses as $ipAddress): ?>
<VirtualHost <?php
    echo "{$ipAddress->escapedAddress}:{$VAR->server->webserver->httpPort}";
    echo $VAR->server->webserver->proxyActive ? " 127.0.0.1:{$VAR->server->webserver->httpPort}" : "";
    ?>>

    ServerName "webmail.<?php echo $VAR->domain->asciiName ?>"
    <?php foreach ($VAR->domain->mailAliases as $alias): ?>
        ServerAlias  "webmail.<?php echo $alias->asciiName ?>"
    <?php endforeach; ?>

    UseCanonicalName Off

    <?php switch ($VAR->domain->webmail->type) {
        case 'atmail':
            echo $VAR->includeTemplate('webmail/atmail.php');
            break;
        case 'horde':
            echo $VAR->includeTemplate('webmail/horde.php');
            break;
        case 'roundcube':
            echo $VAR->includeTemplate('webmail/roundcube.php');
            break;
    } ?>

    <?php echo $VAR->includeTemplate('domain/PCI_compliance.php') ?>

</VirtualHost>
<?php endforeach; ?>


<IfModule mod_ssl.c>
<?php foreach ($VAR->domain->webmail->ipAddresses as $ipAddress): ?>
<VirtualHost <?php
    echo "{$ipAddress->escapedAddress}:{$VAR->server->webserver->httpsPort}";
    echo $VAR->server->webserver->proxyActive ? " 127.0.0.1:{$VAR->server->webserver->httpsPort}" : "";
    ?>>

    ServerName "webmail.<?php echo $VAR->domain->asciiName ?>"
    <?php foreach ($VAR->domain->mailAliases as $alias): ?>
        ServerAlias  "webmail.<?php echo $alias->asciiName ?>"
    <?php endforeach; ?>

    UseCanonicalName Off

    <?php $sslCertificate = $VAR->server->sni && $VAR->domain->webmail->sslCertificate
            ? $VAR->domain->webmail->sslCertificate
            : $ipAddress->sslCertificate; ?>
    <?php if ($sslCertificate->ce): ?>
        SSLEngine on
        SSLVerifyClient none
        SSLSessionCacheTimeout 600
        SSLCertificateFile '<?php echo $sslCertificate->ceFilePath ?>'
    <?php if ($sslCertificate->ca): ?>
        SSLCACertificateFile '<?php echo $sslCertificate->caFilePath ?>'
        SSLVerifyClient require
        SSLVerifyDepth 2
        SSLCompression          off
        SSLSessionTickets       off
        SSLUseStapling on
        SSLStaplingResponderTimeout 5
        SSLStaplingReturnResponderErrors off
        Header always set Strict-Transport-Security "max-age=63072000; includeSubdomains; preload;"
    <?php endif; ?>
    <?php endif; ?>

    <?php switch ($VAR->domain->webmail->type) {
        case 'atmail':
            echo $VAR->includeTemplate('webmail/atmail.php');
            break;
        case 'horde':
            echo $VAR->includeTemplate('webmail/horde.php');
            break;
        case 'roundcube':
            echo $VAR->includeTemplate('webmail/roundcube.php');
            break;
    } ?>

    <?php echo $VAR->includeTemplate('domain/PCI_compliance.php') ?>

</VirtualHost>
<?php endforeach; ?>
</IfModule>
