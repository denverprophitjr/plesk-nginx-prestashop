<?php foreach(($OPT['ssl'] ? $VAR->domain->protectedDirectories->sslDirectories : $VAR->domain->protectedDirectories->nonSslDirectories) as $directory): ?>
<Directory "<?php echo $directory['directory'] ?>">
    AuthType Basic
    AuthName "<?php echo 0 < strlen($directory['realm']) ? $directory['realm'] : ' '?>"
    AuthUserFile "<?php echo $directory['authFile'] ?>"
    require valid-user
</Directory>
<?php endforeach; ?>
