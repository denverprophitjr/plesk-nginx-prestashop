<?php
    $ipAddresses = $VAR->server->ipAddresses->all;
    $ipLimit = $VAR->server->webserver->apache->vhostIpCapacity;
?>

<?php for($ipAddress = reset($ipAddresses); $ipAddress; $ipAddress = next($ipAddresses)): ?>
<VirtualHost <?php
    echo "{$ipAddress->escapedAddress}:{$VAR->server->webserver->httpPort}";
    for ($n = 1; $n < $ipLimit && $ipAddress = next($ipAddresses); ++$n) {
        echo " {$ipAddress->escapedAddress}:{$VAR->server->webserver->httpPort}";
    }
    echo $VAR->server->webserver->proxyActive ? " 127.0.0.1:{$VAR->server->webserver->httpPort}" : '';
    ?>>
    DocumentRoot "<?php echo $VAR->server->webserver->httpDir ?>"
    ServerName lists
    ServerAlias lists.*
    UseCanonicalName Off

<?php foreach ($VAR->server->mailman->scriptAliases as $urlPath => $filePath): ?>
    ScriptAlias "<?php echo $urlPath ?>" "<?php echo $filePath ?>"
<?php endforeach; ?>

<?php foreach ($VAR->server->mailman->aliases as $urlPath => $filePath): ?>
    Alias "<?php echo $urlPath ?>" "<?php echo $filePath ?>"
<?php endforeach; ?>

    <IfModule mod_ssl.c>
        SSLEngine off
    </IfModule>

<?php echo $VAR->includeTemplate('domain/PCI_compliance.php') ?>

    <Directory <?php echo $VAR->server->mailman->varDir ?>/archives/>
        Options FollowSymLinks
    <?php if ($VAR->server->webserver->apache->useRequireOption): ?>
        Require all granted
    <?php else: ?>
        Order allow,deny
        Allow from all
    <?php endif; ?>
    </Directory>

</VirtualHost>
<?php endfor; ?>

<IfModule mod_ssl.c>
<?php for($ipAddress = reset($ipAddresses); $ipAddress; $ipAddress = next($ipAddresses)): ?>
<?php if ($ipAddress->sslCertificate->ce): ?>
<VirtualHost <?php
    echo "{$ipAddress->escapedAddress}:{$VAR->server->webserver->httpsPort}";
    for ($n = 1; $n < $ipLimit && $ipAddress = next($ipAddresses); ++$n) {
        echo " {$ipAddress->escapedAddress}:{$VAR->server->webserver->httpsPort}";
    }
    echo $VAR->server->webserver->proxyActive ? " 127.0.0.1:{$VAR->server->webserver->httpsPort}" : '';
    ?>>
    DocumentRoot "<?php echo $VAR->server->webserver->httpsDir ?>"
    ServerName lists
    ServerAlias lists.*
    UseCanonicalName Off

<?php foreach ($VAR->server->mailman->scriptAliases as $urlPath => $filePath): ?>
    ScriptAlias "<?php echo $urlPath ?>" "<?php echo $filePath ?>"
<?php endforeach; ?>

<?php foreach ($VAR->server->mailman->aliases as $urlPath => $filePath): ?>
    Alias "<?php echo $urlPath ?>" "<?php echo $filePath ?>"
<?php endforeach; ?>

    SSLEngine on
    SSLVerifyClient none
    SSLCertificateFile "<?php echo $VAR->server->defaultSslCertificate->ceFilePath ?>"
    SSLUseStapling on
<?php echo $VAR->includeTemplate('domain/PCI_compliance.php') ?>

    <Directory <?php echo $VAR->server->mailman->varDir ?>/archives/>
        Options FollowSymLinks
    <?php if ($VAR->server->webserver->apache->useRequireOption): ?>
        Require all granted
    <?php else: ?>
        Order allow,deny
        Allow from all
    <?php endif; ?>
    </Directory>

</VirtualHost>
<?php endif; ?>
<?php endfor; ?>
</IfModule>
