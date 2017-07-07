<?php
/**
 * @var Template_VariableAccessor $VAR
 * @var array $OPT
 */
?>
server {
    listen <?php echo $OPT['ipAddress']->escapedAddress . ':' . $OPT['frontendPort'] .
        ($OPT['default'] ? ' default_server' : '') ?>;

    server_name <?php echo $VAR->domain->asciiName ?>;
<?php if ($VAR->domain->isWildcard): ?>
    server_name <?php echo $VAR->domain->wildcardName ?>;
<?php else: ?>
    server_name www.<?php echo $VAR->domain->asciiName ?>;
<?php endif ?>
<?php if (!$VAR->domain->isWildcard): ?>
<?php   if ($OPT['ipAddress']->isIpV6()): ?>
    server_name ipv6.<?php echo $VAR->domain->asciiName ?>;
<?php   else: ?>
    server_name ipv4.<?php echo $VAR->domain->asciiName ?>;
<?php   endif ?>
<?php endif ?>

<?php foreach ($VAR->domain->webAliases as $alias): ?>
    server_name <?php echo  $alias->asciiName ?>;
    server_name www.<?php echo $alias->asciiName ?>;
    <?php if ($OPT['ipAddress']->isIpV6()): ?>
    server_name ipv6.<?php echo $alias->asciiName ?>;
    <?php else: ?>
    server_name ipv4.<?php echo $alias->asciiName ?>;
    <?php endif ?>
<?php endforeach ?>

<?php if ($OPT['default']): ?>
<?php echo $VAR->includeTemplate('service/nginxSitePreview.php') ?>
<?php endif ?>

    location / {
        proxy_pass http://127.0.0.1:<?php echo $OPT['backendPort'] ?>;
        proxy_set_header Host 			 $host;
        proxy_set_header X-Real-IP 		 $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        access_log off;
    }

    add_header X-Powered-By PleskLin;
}
