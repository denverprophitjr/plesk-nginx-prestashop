<IfModule mod_perl.c>
    <Files ~ (\.asp$)>
        SetHandler perl-script
        PerlHandler Apache::ASP
        PerlSetVar Global /tmp
    </Files>
</IfModule>
