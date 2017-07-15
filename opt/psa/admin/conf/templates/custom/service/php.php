<IfModule mod_php7.c>
<?php
if (array_key_exists('enabled', $OPT) && $OPT['enabled']) {
    echo "php_admin_flag engine on\n";

    if (isset($OPT['settings'])) {
        echo $OPT['settings'];
    }

} else {
    echo "php_admin_flag engine off\n";
}
?>
</IfModule>
