<?php
    /** @var Template_VariableAccessor $VAR */
    $hordeDocroot = $VAR->server->webserver->horde->docroot;
    $hordeSysUser = "horde_sysuser";
    $hordeSysGroup = "horde_sysgroup";
    $hordePhpIni = $VAR->server->webserver->horde->confD . "/horde/php.ini";
?>
    DocumentRoot "<?php echo $hordeDocroot ?>"
    Alias /horde/ "<?php echo $hordeDocroot ?>/"

    <IfModule mod_suexec.c>
        SuexecUserGroup "<?php echo $hordeSysUser; ?>" "<?php echo $hordeSysGroup; ?>"
    </IfModule>

    <IfModule mod_fcgid.c>
        FcgidInitialEnv PP_CUSTOM_PHP_CGI_INDEX fastcgi
        FcgidInitialEnv PP_CUSTOM_PHP_INI "<?php echo $hordePhpIni; ?>"
        FcgidMaxRequestLen 134217728
        <Directory "<?php echo $hordeDocroot ?>">
            <Files ~ (\.php$)>
                SetHandler fcgid-script
                FCGIWrapper <?php echo $VAR->server->webserver->apache->phpCgiBin ?> .php
                Options +ExecCGI
            </Files>

        <?php if ($VAR->server->webserver->apache->useRequireOption): ?>
            Require all granted
        <?php else: ?>
            Order allow,deny
            Allow from all
        <?php endif; ?>
        </Directory>
    </IfModule>
