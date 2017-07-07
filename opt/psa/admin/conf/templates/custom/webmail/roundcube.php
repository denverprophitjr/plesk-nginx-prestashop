<?php
    /** @var Template_VariableAccessor $VAR */
    $roundcubeDocroot = $VAR->server->webserver->roundcube->docroot;
    $roundcubeConfD = "/etc/psa-webmail/roundcube";
    $roundcubeSysUser = "roundcube_sysuser";
    $roundcubeSysGroup = "roundcube_sysgroup";
    $roundcubeHtaccess = $VAR->server->webserver->httpConfDir . "/plesk.conf.d/roundcube.htaccess.inc";
    $roundcubePhpIni = $roundcubeConfD . "/php.ini";
?>
    DocumentRoot "<?php echo $roundcubeDocroot ?>"
    Alias /roundcube/ "<?php echo $roundcubeDocroot ?>/"

    <IfModule mod_suexec.c>
        SuexecUserGroup <?php echo $roundcubeSysUser; ?> <?php echo $roundcubeSysGroup; ?>

    </IfModule>

    <IfModule mod_fcgid.c>
            FcgidInitialEnv PP_CUSTOM_PHP_CGI_INDEX fastcgi
            FcgidInitialEnv PP_CUSTOM_PHP_INI "<?php echo $roundcubePhpIni; ?>"
            FcgidMaxRequestLen 134217728
        <Directory "<?php echo $roundcubeDocroot ?>">
            Options -Indexes +FollowSymLinks
            AllowOverride FileInfo
        <?php if ($VAR->server->webserver->apache->useRequireOption): ?>
            Require all granted
        <?php else: ?>
            Order allow,deny
            Allow from all
        <?php endif; ?>
            Include "<?php echo $roundcubeHtaccess ?>"

            <Files ~ (\.php$)>
                SetHandler fcgid-script
                FCGIWrapper <?php echo $VAR->server->webserver->apache->phpCgiBin ?> .php
                Options +ExecCGI
            </Files>
        </Directory>
    </IfModule>
