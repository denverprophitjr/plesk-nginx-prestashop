<?php
/**
 * @var Template_VariableAccessor $VAR
 */
?>
        fastcgi_split_path_info ^((?U).+\.php)(/?.+)$;
        fastcgi_param PATH_INFO $fastcgi_path_info;
        fastcgi_pass "<?php echo $VAR->domain->physicalHosting->fpmSocket ?>";
        include /etc/nginx/fastcgi.conf;
