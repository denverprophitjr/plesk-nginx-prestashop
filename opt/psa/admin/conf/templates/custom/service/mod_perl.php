<IfModule mod_perl.c>
    <Files ~ (\.pl$)>
        SetHandler perl-script
        PerlHandler ModPerl::Registry
        Options +ExecCGI
        allow from all
        PerlSendHeader On
    </Files>
</IfModule>
