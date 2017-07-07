<?php
    /** @var Template_VariableAccessor $VAR */
    $atmailDocroot = $VAR->server->webserver->atmail->docroot;
    $atmailConfD = "/etc/psa-webmail/atmail";
    $modPHPAvailiable = $VAR->server->php->ModAvailable;
?>

    DocumentRoot "<?php echo $atmailDocroot ?>"
    Alias /atmail/ "<?php echo $atmailDocroot ?>/"
    CustomLog /var/log/atmail/access_log plesklog
    ErrorLog /var/log/atmail/error_log

    <Directory "<?php echo $atmailDocroot ?>">
    <?php if ($modPHPAvailiable): ?>
        <IfModule <?php echo $VAR->server->webserver->apache->php4ModuleName ?>>
            php_admin_flag engine on
            php_admin_flag safe_mode off
            php_admin_flag magic_quotes_gpc off
            php_admin_flag register_globals off

            php_admin_value open_basedir "<?php echo
                "$atmailDocroot/:/var/log/atmail/:$atmailConfD/:/tmp/:/var/tmp/" ?>"
            php_admin_value include_path "<?php echo
                "$atmailDocroot:$atmailDocroot/libs:$atmailDocroot/libs/Atmail:$atmailDocroot/libs/PEAR:$atmailDocroot/libs/File:." ?>"

            php_admin_value upload_max_filesize 16M
            php_admin_value post_max_size 16M
        </IfModule>

        <IfModule mod_php5.c>
            php_admin_flag engine on
            php_admin_flag safe_mode off
            php_admin_flag magic_quotes_gpc off
            php_admin_flag register_globals off

            php_admin_value open_basedir "<?php echo
                "$atmailDocroot/:/var/log/atmail/:$atmailConfD/:/tmp/:/var/tmp/" ?>"
            php_admin_value include_path "<?php echo
                "$atmailDocroot:$atmailDocroot/libs:$atmailDocroot/libs/Atmail:$atmailDocroot/libs/PEAR:$atmailDocroot/libs/File:." ?>"

            php_admin_value upload_max_filesize 16M
            php_admin_value post_max_size 16M
        </IfModule>
    <?php else: ?>

        SetHandler None
        AddHandler php-script .php
        Options +ExecCGI

    <?php endif; ?>

    <?php if ($VAR->server->webserver->apache->useRequireOption): ?>
        Require all granted
    <?php else: ?>
        Order allow,deny
        Allow from all
    <?php endif; ?>

    </Directory>
