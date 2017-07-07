<Directory <?php echo $OPT['ssl'] ? $VAR->domain->physicalHosting->httpsDir : $VAR->domain->physicalHosting->httpDir ?>/_vti_bin/_vti_adm>
    AddType text/html .exe
</Directory>

<Directory <?php echo $OPT['ssl'] ? $VAR->domain->physicalHosting->httpsDir : $VAR->domain->physicalHosting->httpDir ?>/*/_vti_bin/_vti_adm>
    AddType text/html .exe
</Directory>