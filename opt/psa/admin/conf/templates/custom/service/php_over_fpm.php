<IfModule mod_proxy_fcgi.c>
    <Files ~ (\.php$)>
        SetHandler proxy:<?php echo $VAR->domain->physicalHosting->fpmSocket ?>|fcgi://127.0.0.1:9000
    </Files>
</IfModule>
