<?php foreach ($VAR->server->ipAddresses->all as $ipaddress): ?>
NameVirtualHost <?php echo $ipaddress->escapedAddress ?>:<?php
    echo ($OPT['ssl']
        ? $VAR->server->webserver->httpsPort
        : $VAR->server->webserver->httpPort) . "\n" ?>
<?php endforeach; ?>

<?php if ($VAR->server->webserver->proxyActive): ?>
NameVirtualHost 127.0.0.1:<?php
    echo ($OPT['ssl']
        ? $VAR->server->webserver->httpsPort
        : $VAR->server->webserver->httpPort) . "\n" ?>
<?php endif ?>