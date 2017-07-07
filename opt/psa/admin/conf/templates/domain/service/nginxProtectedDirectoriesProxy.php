<?php foreach(($OPT['ssl'] ? $VAR->domain->protectedDirectories->sslDirectories : $VAR->domain->protectedDirectories->nonSslDirectories) as $directory): ?>
    <?php if ('plesk-stat' == $directory['relativePath']): ?>
        <?php continue ?>
    <?php endif ?>

    location ~ ^/<?php echo $dir = ltrim($directory['relativePath'] . '/', '/'); ?> {
    <?php echo $VAR->includeTemplate('domain/service/proxy.php', $OPT + (!$dir ? ['nginxTransparentMode' => true] : [])); ?>
    }
<?php endforeach; ?>
