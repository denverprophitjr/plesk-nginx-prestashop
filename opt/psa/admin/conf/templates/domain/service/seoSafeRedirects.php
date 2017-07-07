<?php
/**
 * @var Template_VariableAccessor $VAR
 * @var array $OPT
 */
?>
<?php if ($VAR->domain->isSeoRedirectToLanding) : ?>
    <IfModule mod_rewrite.c>
        RewriteEngine On
        RewriteCond %{HTTP_HOST} ^www\.<?php echo str_replace('.', '\\.', $VAR->domain->asciiName) ?>$ [NC]
        RewriteRule ^(.*)$ <?php echo $OPT['ssl'] ? 'https' : 'http'; ?>://<?php echo $VAR->domain->asciiName ?>$1 [L,R=301]
    </IfModule>
<?php elseif ($VAR->domain->isSeoRedirectToWww): ?>
    <IfModule mod_rewrite.c>
        RewriteEngine On
        RewriteCond %{HTTP_HOST} ^<?php echo str_replace('.', '\\.', $VAR->domain->asciiName) ?>$ [NC]
        RewriteRule ^(.*)$ <?php echo $OPT['ssl'] ? 'https' : 'http'; ?>://www.<?php echo $VAR->domain->asciiName ?>$1 [L,R=301]
    </IfModule>
<?php endif; ?>
<?php if ($VAR->domain->isAliasRedirected): ?>
    <IfModule mod_rewrite.c>
        RewriteEngine On
        <?php foreach ($VAR->domain->webAliases AS $alias): ?>
            <?php if ($alias->isSeoRedirect) : ?>
                RewriteCond %{HTTP_HOST} ^<?php echo str_replace('.', '\\.', $alias->asciiName) ?>$ [NC,OR]
                RewriteCond %{HTTP_HOST} ^www\.<?php echo str_replace('.', '\\.', $alias->asciiName) ?>$ [NC]
                RewriteRule ^(.*)$ <?php echo $OPT['ssl'] ? 'https' : 'http'; ?>://<?php echo $VAR->domain->targetName; ?>$1 [L,R=301]
            <?php endif; ?>
        <?php endforeach; ?>
    </IfModule>
<?php endif; ?>
