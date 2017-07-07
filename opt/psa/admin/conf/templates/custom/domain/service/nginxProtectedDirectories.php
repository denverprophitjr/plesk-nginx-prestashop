<?php
/**
 * @var Template_VariableAccessor $VAR
 * @var array $OPT
 */
?>
<?php foreach(($OPT['ssl'] ? $VAR->domain->protectedDirectories->sslDirectories : $VAR->domain->protectedDirectories->nonSslDirectories) as $directory): ?>
    <?php if ('plesk-stat' == $directory['relativePath']): ?>
        <?php continue ?>
    <?php endif ?>

    location ~ ^/<?php echo ltrim($directory['relativePath'] . '/', '/') ?> {
        auth_basic "<?php echo strlen($directory['realm']) > 0 ? $directory['realm'] : ' ' ?>";
        auth_basic_user_file "<?php echo $directory['authFile'] ?>";

    <?php if ($VAR->domain->physicalHosting->php && $VAR->domain->physicalHosting->proxySettings['nginxServePhp']): ?>

        location ~ \.php(/.*)?$ {
        <?php echo $VAR->includeTemplate('domain/service/fpm.php') ?>
        }

        <?php if ($VAR->domain->physicalHosting->directoryIndex): ?>
        location ~ /$ {
            index <?=$VAR->quote($VAR->domain->physicalHosting->directoryIndex)?>;
        }
        <?php endif ?>

    <?php endif ?>
    }
<?php endforeach ?>
