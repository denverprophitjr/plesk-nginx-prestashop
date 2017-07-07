<?php
/**
 * @var Template_VariableAccessor $VAR
 * @var array $OPT
 */
?>
    location ~ ^/(plesk-stat|awstats-icon|webstat|webstat-ssl|ftpstat|anon_ftpstat) {
<?php if ($VAR->domain->physicalHosting->proxySettings['nginxProxyMode']): ?>
    <?php echo $VAR->includeTemplate('domain/service/proxy.php', $OPT) ?>
<?php else: ?>
    <?php if ($OPT['ssl']): ?>
        <?php
        $directories = array_filter($VAR->domain->protectedDirectories->sslDirectories, function($directory) {
            return 'plesk-stat' == $directory['relativePath'];
        });
        $directory = reset($directories);
        ?>
        <?php if ($directory): ?>
        auth_basic "<?php echo strlen($directory['realm']) > 0 ? $directory['realm'] : ' ' ?>";
        auth_basic_user_file "<?php echo $directory['authFile'] ?>";
        <?php endif ?>
        autoindex on;

        location ~ ^/plesk-stat(.*) {
            alias <?php echo $VAR->domain->physicalHosting->statisticsDir ?>/$1;
        }

        location ~ ^/awstats-icon(.*) {
            alias <?php echo $VAR->server->awstats->iconsDir ?>/$1;
        }

        location ~ ^/(.*)/(.*) {
            alias <?php echo $VAR->domain->physicalHosting->statisticsDir ?>/$1/$2;
        }
    <?php else: ?>
        return 301 https://$host$request_uri;
    <?php endif ?>
<?php endif ?>
    }
