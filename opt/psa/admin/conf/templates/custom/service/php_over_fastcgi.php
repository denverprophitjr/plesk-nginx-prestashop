<IfModule mod_fcgid.c>
    <Files ~ (\.php$)>
        SetHandler fcgid-script
        FCGIWrapper <?php echo $VAR->server->webserver->apache->phpCgiBin ?> .php
        Options +ExecCGI
    </Files>
</IfModule>
