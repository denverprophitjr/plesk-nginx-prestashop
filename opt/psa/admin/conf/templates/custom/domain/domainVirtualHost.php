<?php
/**
 * @var Template_VariableAccessor $VAR
 * @var array $OPT
 */
?>
<?php if ($OPT['ssl']): ?>
<IfModule mod_ssl.c>
<?php endif; ?>
SSLStaplingCache shmcb:/tmp/stapling_cache(128000)
<VirtualHost <?php echo $OPT['ipAddress']->escapedAddress ?>:<?php echo $OPT['ssl'] ? $VAR->server->webserver->httpsPort : $VAR->server->webserver->httpPort ?> <?php echo ($VAR->server->webserver->proxyActive && $OPT['ipAddress']->isIpV6()) ? "127.0.0.1:" . ($OPT['ssl'] ? $VAR->server->webserver->httpsPort : $VAR->server->webserver->httpPort) : ''; ?>>
    ServerName "<?php echo $VAR->domain->asciiName ?>:<?php echo $OPT['ssl'] ? $VAR->server->webserver->frontendHttpsPort : $VAR->server->webserver->frontendHttpPort ?>"
    <?php if ($VAR->domain->isWildcard): ?>
    ServerAlias "<?php echo $VAR->domain->wildcardName ?>"
    <?php else: ?>
    ServerAlias "www.<?php echo $VAR->domain->asciiName ?>"
    <?php if ($OPT['ipAddress']->isIpV6()): ?>
    ServerAlias "ipv6.<?php echo $VAR->domain->asciiName ?>"
    <?php else: ?>
    ServerAlias "ipv4.<?php echo $VAR->domain->asciiName ?>"
    <?php endif; ?>
    <?php endif; ?>
<?php foreach ($VAR->domain->webAliases AS $alias): ?>
    ServerAlias "<?php echo $alias->asciiName ?>"
    ServerAlias "www.<?php echo $alias->asciiName ?>"
    <?php if ($OPT['ipAddress']->isIpV6()): ?>
    ServerAlias "ipv6.<?php echo $alias->asciiName ?>"
    <?php else: ?>
    ServerAlias "ipv4.<?php echo $alias->asciiName ?>"
    <?php endif; ?>
<?php endforeach; ?>
<?php if ($VAR->domain->previewDomainName): ?>
    ServerAlias "<?php echo $VAR->domain->previewDomainName ?>"
<?php endif; ?>
<?php if($VAR->domain->email || $VAR->domain->client->email): ?>
    ServerAdmin "<?php echo $VAR->domain->email ? $VAR->domain->email : $VAR->domain->client->email ?>"
<?php endif; ?>
    UseCanonicalName Off

<?php if (!$OPT['ssl'] && $VAR->domain->physicalHosting->ssl && $VAR->domain->physicalHosting->sslRedirect): ?>

<?php echo $VAR->includeTemplate('domain/service/seoSafeRedirects.php', array('ssl' => true)); ?>

    <IfModule mod_rewrite.c>
        RewriteEngine On
        RewriteCond %{HTTPS} off
        RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [R=301,L,QSA]
    </IfModule>
</VirtualHost>
    <?php return; ?>
<?php endif; ?>

    DocumentRoot "<?php echo $OPT['ssl'] ? $VAR->domain->physicalHosting->httpsDir : $VAR->domain->physicalHosting->httpDir ?>"
<?php if (!$VAR->server->webserver->apache->pipelogEnabled): ?>
    CustomLog <?php echo $VAR->domain->physicalHosting->logsDir ?>/<?php echo $OPT['ssl'] ? 'access_ssl_log' : 'access_log' ?> plesklog
<?php endif; ?>
    ErrorLog "<?php echo $VAR->domain->physicalHosting->logsDir ?>/error_log"

    <?php if ($VAR->domain->physicalHosting->branding): ?>
        <IfModule mod_proxy.c>
            SSLProxyEngine on
        </IfModule>
    <?php endif; ?>

<IfModule mod_suexec.c>
    SuexecUserGroup "<?php echo $VAR->domain->physicalHosting->login ?>" "<?php echo $VAR->server->webserver->clientGroup ?>"
</IfModule>

<?php echo $VAR->includeTemplate('domain/PCI_compliance.php') ?>

<IfModule mod_userdir.c>
    <?php if (count($VAR->domain->physicalHosting->webusers)): ?>
    UserDir enabled <?php echo implode(" ", array_map(function($webuser) { return $webuser->login;}, $VAR->domain->physicalHosting->webusers)) ?>
    <?php endif; ?>

    UserDir "<?php echo $VAR->domain->physicalHosting->webUsersDir ?>/*"
</IfModule>

<?php if ($VAR->domain->physicalHosting->vhostId): ?>
    <IfModule mod_sysenv.c>
        SetSysEnv PP_VHOST_ID "<?php echo $VAR->domain->physicalHosting->vhostId ?>"
    </IfModule>
<?php endif; ?>


<?php if ($VAR->domain->physicalHosting->cgi && !$VAR->domain->physicalHosting->rootApplication): ?>
    ScriptAlias "/cgi-bin/" "<?php echo $VAR->domain->physicalHosting->cgiBinDir ?>/"
<?php endif; ?>

<?php if ($VAR->domain->physicalHosting->hasWebstat):?>

<?php if ($OPT['ssl'] || !$VAR->domain->physicalHosting->ssl): ?>
    Alias "/plesk-stat" "<?php echo $VAR->domain->physicalHosting->statisticsDir ?>"
    <Location  /plesk-stat/>
        Options +Indexes
    </Location>
    <Location  /plesk-stat/logs/>
        Require valid-user
    </Location>
    Alias /webstat <?php echo $VAR->domain->physicalHosting->statisticsDir ?>/webstat
    Alias /webstat-ssl <?php echo $VAR->domain->physicalHosting->statisticsDir ?>/webstat-ssl
    Alias /ftpstat <?php echo $VAR->webspace->statisticsDir ?>/ftpstat
    Alias /anon_ftpstat <?php echo $VAR->webspace->statisticsDir ?>/anon_ftpstat
    Alias /awstats-icon <?php echo $VAR->server->awstats->iconsDir ?>

<?php else: ?>
    Redirect permanent /plesk-stat https://<?php echo $VAR->domain->urlName ?>/plesk-stat
    Redirect permanent /webstat https://<?php echo $VAR->domain->urlName ?>/webstat
    Redirect permanent /webstat-ssl https://<?php echo $VAR->domain->urlName ?>/webstat-ssl
    Redirect permanent /ftpstat https://<?php echo $VAR->domain->urlName ?>/ftpstat
    Redirect permanent /anon_ftpstat https://<?php echo $VAR->domain->urlName ?>/anon_ftpstat
    Redirect permanent /awstats-icon https://<?php echo $VAR->domain->urlName ?>/awstats-icon
<?php endif; ?>

<?php endif; ?>

<?php if ($OPT['ssl']): ?>
<?php $sslCertificate = $VAR->server->sni && $VAR->domain->physicalHosting->sslCertificate ?
    $VAR->domain->physicalHosting->sslCertificate :
    $OPT['ipAddress']->sslCertificate; ?>
<?php if ($sslCertificate->ce): ?>
    SSLEngine on
    SSLCertificateFile "<?php echo $sslCertificate->ceFilePath ?>"
    SSLSessionCacheTimeout 600
<?php if ($sslCertificate->ca): ?>
    SSLCACertificateFile "<?php echo $sslCertificate->caFilePath ?>"
    SSLVerifyDepth 2
    SSLCompression          off
    SSLSessionTickets       off
    SSLUseStapling on
    SSLStaplingResponderTimeout 5
    SSLStaplingReturnResponderErrors off
<?php endif; ?>

<?php endif; ?>
<?php else: ?>
    <IfModule mod_ssl.c>
        SSLEngine off
    </IfModule>
<?php endif; ?>

<?php if ($VAR->domain->physicalHosting->php && $VAR->domain->physicalHosting->phpHandlerType == 'cgi'): ?>
SetEnv PP_CUSTOM_PHP_INI <?php echo $VAR->domain->physicalHosting->vhostSystemDir ?>/etc/php.ini
SetEnv PP_CUSTOM_PHP_CGI_INDEX <?php echo $VAR->domain->physicalHosting->phpHandlerId ?>

<?php endif; ?>

<?php if ($VAR->domain->physicalHosting->php && $VAR->domain->physicalHosting->phpHandlerType == 'fastcgi'): ?>
<IfModule mod_fcgid.c>
    FcgidInitialEnv PP_CUSTOM_PHP_INI <?php echo $VAR->domain->physicalHosting->vhostSystemDir ?>/etc/php.ini
    FcgidInitialEnv PP_CUSTOM_PHP_CGI_INDEX <?php echo $VAR->domain->physicalHosting->phpHandlerId ?>

    FcgidMaxRequestLen 134217728
<?php if ($VAR->domain->physicalHosting->scriptTimeout): ?>
    FcgidIOTimeout <?php echo $VAR->domain->physicalHosting->scriptTimeout; ?>

<?php endif; ?>
</IfModule>
<?php endif; ?>

<?php if ($VAR->domain->physicalHosting->scriptTimeout): ?>
TimeOut <?php echo $VAR->domain->physicalHosting->scriptTimeout; ?>
<?php endif; ?>

    <Directory <?php echo $OPT['ssl'] ? $VAR->domain->physicalHosting->httpsDir : $VAR->domain->physicalHosting->httpDir ?>>

<?php
if ($VAR->domain->physicalHosting->perl) {
    echo $VAR->includeTemplate('service/mod_perl.php');
}

if ($VAR->domain->physicalHosting->asp) {
    echo $VAR->includeTemplate('service/asp.php');
}

if (
    !$VAR->domain->physicalHosting->php ||
    !in_array($VAR->domain->physicalHosting->phpHandlerType, array('cgi', 'fastcgi', 'fpm'))
) {
    echo $VAR->includeTemplate('service/php.php', array(
        'enabled' => $VAR->domain->physicalHosting->php,
        'safe_mode' => $VAR->domain->physicalHosting->phpSafeMode,
        'dir' => $OPT['ssl'] ? $VAR->domain->physicalHosting->httpsDir : $VAR->domain->physicalHosting->httpDir,
        'settings' => $VAR->domain->physicalHosting->phpSettings,
    ));
}

if ($VAR->domain->physicalHosting->python) {
    echo $VAR->includeTemplate('service/mod_python.php');
}

if ($VAR->domain->physicalHosting->fastcgi) {
    echo $VAR->includeTemplate('service/mod_fastcgi.php');
}

if ($VAR->domain->physicalHosting->php && 'cgi' == $VAR->domain->physicalHosting->phpHandlerType) {
    echo $VAR->includeTemplate('service/php_over_cgi.php');
}

if ($VAR->domain->physicalHosting->php && 'fastcgi' == $VAR->domain->physicalHosting->phpHandlerType) {
    echo $VAR->includeTemplate('service/php_over_fastcgi.php');
}

if ($VAR->domain->physicalHosting->php && 'fpm' == $VAR->domain->physicalHosting->phpHandlerType) {
    echo $VAR->includeTemplate('service/php_over_fpm.php');
}

?>

<?php if ($OPT['ssl']): ?>
        SSLRequireSSL
<?php endif; ?>

        Options <?php echo $VAR->domain->physicalHosting->ssi ? '+' : '-' ?>Includes <?php echo $VAR->domain->physicalHosting->cgi ? '+' : '-' ?>ExecCGI

    </Directory>

<?php if ($VAR->domain->physicalHosting->webusersScriptingEnabled): ?>
<?php foreach ($VAR->domain->physicalHosting->webusers as $webuser): ?>
    <Directory <?php echo $webuser->dir ?>>
        Options <?php echo $VAR->domain->physicalHosting->ssi && $webuser->ssi ? '+' : '-' ?>Includes <?php echo $VAR->domain->physicalHosting->cgi && $webuser->cgi ? '+' : '-' ?>ExecCGI

<?php if ($VAR->domain->physicalHosting->cgi && $webuser->cgi): ?>
        AddHandler cgi-script .cgi
<?php endif; ?>

<?php
if ($VAR->domain->physicalHosting->perl && $webuser->perl) {
    echo $VAR->includeTemplate('service/mod_perl.php');
}

if ($VAR->domain->physicalHosting->asp && $webuser->asp) {
    echo $VAR->includeTemplate('service/asp.php');
}

if (
    !$VAR->domain->physicalHosting->php ||
    !in_array($VAR->domain->physicalHosting->phpHandlerType, array('cgi', 'fastcgi', 'fpm'))
) {
    echo $VAR->includeTemplate('service/php.php', array(
        'enabled' => $VAR->domain->physicalHosting->php && $webuser->php,
        'safe_mode' => $VAR->domain->physicalHosting->phpSafeMode,
        'dir' => $webuser->dir,
        'settings' => $webuser->phpSettings,
    ));
}

if ($VAR->domain->physicalHosting->php && $webuser->php && 'cgi' == $VAR->domain->physicalHosting->phpHandlerType) {
    echo $VAR->includeTemplate('service/php_over_cgi.php');
}

if ($VAR->domain->physicalHosting->php && $webuser->php && 'fastcgi' == $VAR->domain->physicalHosting->phpHandlerType) {
    echo $VAR->includeTemplate('service/php_over_fastcgi.php');
}

if ($VAR->domain->physicalHosting->php && $webuser->php && 'fpm' == $VAR->domain->physicalHosting->phpHandlerType) {
    echo $VAR->includeTemplate('service/php_over_fpm.php');
}

if ($VAR->domain->physicalHosting->python && $webuser->python) {
    echo $VAR->includeTemplate('service/mod_python.php');
}

if ($VAR->domain->physicalHosting->fastcgi && $webuser->fastcgi) {
    echo $VAR->includeTemplate('service/mod_fastcgi.php');
}
?>

    </Directory>
<?php endforeach; ?>

<?php else: ?>

    <Directory <?php echo $VAR->domain->physicalHosting->webUsersDir ?>>

<?php echo $VAR->includeTemplate('service/php.php', array(
    'enabled' => false,
    'safe_mode' => true,
    'dir' => $VAR->domain->physicalHosting->webUsersDir,
    'settings' => $VAR->domain->physicalHosting->phpSettings,
)); ?>

    </Directory>

<?php endif; ?>

<?php if (!$VAR->domain->physicalHosting->isMainDomain): ?>
    <Directory <?php echo $VAR->domain->physicalHosting->vhostDir ?>>
        Options +FollowSymLinks
    </Directory>
<?php endif ?>

<?php
echo $VAR->includeTemplate('domain/service/protectedDirectories.php', $OPT) . "\n";

if ($VAR->domain->physicalHosting->errordocs) {
    echo $VAR->includeTemplate('domain/service/errordocs.php') . "\n";
}

if ($VAR->domain->tomcat->enabled) {
    echo $VAR->includeTemplate('domain/service/tomcat.php') . "\n";
}

if ($OPT['ssl'] ? $VAR->domain->physicalHosting->frontpageSsl : $VAR->domain->physicalHosting->frontpage) {
    echo $VAR->includeTemplate('domain/service/frontpageWorkaround.php', $OPT) . "\n";
}

echo $VAR->includeTemplate('domain/service/bandWidth.php') . "\n";
?>

<?php if (is_file($VAR->domain->physicalHosting->fileSharingConfigFile)): ?>
    <?php echo $VAR->server->webserver->includeOptionalConfig($VAR->domain->physicalHosting->fileSharingConfigFile . '*') ?>
<?php endif; ?>

<?php if (is_dir($OPT['ssl'] ? $VAR->domain->physicalHosting->siteAppsSslConfigDir : $VAR->domain->physicalHosting->siteAppsConfigDir)): ?>
    <?php echo $VAR->server->webserver->includeOptionalConfig(($OPT['ssl'] ? $VAR->domain->physicalHosting->siteAppsSslConfigDir : $VAR->domain->physicalHosting->siteAppsConfigDir) . '/*.conf') ?>
<?php endif; ?>

<?php echo $VAR->domain->physicalHosting->apacheSettings ?>
<?php if ($VAR->domain->physicalHosting->directoryIndex): ?>
    DirectoryIndex <?=$VAR->quote($VAR->domain->physicalHosting->directoryIndex)?>
<?php endif ?>

<?php echo $VAR->includeTemplate('domain/service/seoSafeRedirects.php', array('ssl' => $OPT['ssl'])); ?>

<?php if ($VAR->domain->suspended): ?>
    <?php echo $VAR->includeTemplate('domain/service/suspend.php'); ?>
<?php endif; ?>

<?php if (is_file($OPT['ssl'] ? $VAR->domain->physicalHosting->customSslConfigFile : $VAR->domain->physicalHosting->customConfigFile)): ?>
    Include "<?php echo $OPT['ssl'] ? $VAR->domain->physicalHosting->customSslConfigFile : $VAR->domain->physicalHosting->customConfigFile ?>"
<?php endif; ?>

<?php if ($VAR->domain->physicalHosting->webAppFirewallSettings): ?>
    <IfModule mod_security2.c>
<?php echo $VAR->domain->physicalHosting->webAppFirewallSettings ?>
    </IfModule>
<?php endif ?>

    <Directory <?php echo $VAR->webspace->vhostDir ?>>
        <?php if ($VAR->domain->physicalHosting->restrictFollowSymLinks): ?>
        Options -FollowSymLinks
        <?php endif ?>
        <?php
        $allowOverrideOptions = ['Indexes', 'SymLinksIfOwnerMatch', 'MultiViews'];
        if (!$VAR->domain->physicalHosting->restrictFollowSymLinks) {
            $allowOverrideOptions[] = 'FollowSymLinks';
        }
        if ($VAR->domain->physicalHosting->cgi || $VAR->server->webserver->apache->allowOverrideExecCGI) {
            $allowOverrideOptions[] = 'ExecCGI';
        }
        if ($VAR->domain->physicalHosting->ssi || $VAR->server->webserver->apache->allowOverrideIncludes) {
            $allowOverrideOptions[] = 'Includes';
            $allowOverrideOptions[] = 'IncludesNOEXEC';
        }
        ?>
        AllowOverride AuthConfig FileInfo Indexes Limit Options=<?=implode(',', $allowOverrideOptions)?>

    </Directory>

<?php if (!$VAR->server->webserver->proxyActive): ?>
    <?php if ($VAR->domain->physicalHosting->expires): ?>
    <IfModule mod_expires.c>
        ExpiresActive On
        ExpiresDefault "access plus <?=$VAR->escape($VAR->domain->physicalHosting->expires)?> seconds"
    </IfModule>
    <?php endif ?>
    <IfModule mod_headers.c>
    <?php foreach ((array)$VAR->domain->physicalHosting->headers as list($name, $value)): ?>
        Header add <?=$VAR->quote([$name, $value])?>
        Header always set Strict-Transport-Security "max-age=63072000; includeSubdomains;"
    <?php endforeach ?>
    </IfModule>
<?php endif ?>

<?=$VAR->domain->physicalHosting->extensionsConfigs?>
</VirtualHost>

<?php if ($OPT['ssl']): ?>
</IfModule>
<?php endif ?>
