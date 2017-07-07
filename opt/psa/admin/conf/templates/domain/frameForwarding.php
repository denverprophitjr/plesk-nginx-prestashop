<VirtualHost <?php echo $OPT['ipAddress']->escapedAddress?>:<?php echo $OPT['ssl'] ? $VAR->server->webserver->httpsPort : $VAR->server->webserver->httpPort ?> <?php echo ($VAR->server->webserver->proxyActive) ? "127.0.0.1:" . ($OPT['ssl'] ? $VAR->server->webserver->httpsPort : $VAR->server->webserver->httpPort) : ''; ?>>
    ServerName "<?php echo $VAR->domain->asciiName ?>"
    <?php if ($VAR->domain->isWildcard): ?>
    ServerAlias  "<?php echo $VAR->domain->wildcardName ?>"
    <?php else: ?>
    ServerAlias "www.<?php echo $VAR->domain->asciiName ?>"
    <?php if ($OPT['ipAddress']->isIpV6()): ?>
    ServerAlias  "ipv6.<?php echo $VAR->domain->asciiName ?>"
    <?php else: ?>
    ServerAlias  "ipv4.<?php echo $VAR->domain->asciiName ?>"
    <?php endif; ?>
    <?php endif; ?>
<?php foreach ($VAR->domain->webAliases as $alias): ?>
    ServerAlias "<?php echo  $alias->asciiName ?>"
    ServerAlias "www.<?php echo $alias->asciiName ?>"
    <?php if ($OPT['ipAddress']->isIpV6()): ?>
    ServerAlias  "ipv6.<?php echo $alias->asciiName ?>"
    <?php else: ?>
    ServerAlias  "ipv4.<?php echo $alias->asciiName ?>"
    <?php endif; ?>
<?php endforeach; ?>

<?php echo $VAR->includeTemplate('domain/PCI_compliance.php') ?>

<?php if (array_key_exists('serverAdmin', $OPT) && $OPT['serverAdmin']): ?>
    ServerAdmin  "<?php echo $OPT['serverAdmin'] ?>"
<?php endif; ?>

    DocumentRoot "<?php echo $VAR->domain->forwarding->vhostDir ?>/httpdocs"
    <IfModule mod_ssl.c>
        SSLEngine off
    </IfModule>
</VirtualHost>
