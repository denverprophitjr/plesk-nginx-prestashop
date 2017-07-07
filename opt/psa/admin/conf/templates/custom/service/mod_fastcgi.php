<IfModule mod_fcgid.c>
    <Files ~ (\.fcgi$)>
        SetHandler fcgid-script
        Options +ExecCGI
    </Files>
</IfModule>
