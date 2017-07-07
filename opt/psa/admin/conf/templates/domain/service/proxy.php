<?php
/**
 * @var Template_VariableAccessor $VAR
 * @var array $OPT
 */
?>
<?php if ($OPT['ssl']): ?>
        proxy_pass https://<?php echo $OPT['ipAddress']->proxyEscapedAddress . ':' . $OPT['backendPort'] ?>;
<?php else: ?>
        proxy_pass http://<?php echo $OPT['ipAddress']->proxyEscapedAddress . ':' . $OPT['backendPort'] ?>;
<?php endif ?>
        proxy_set_header Host             $host;
        proxy_set_header X-Real-IP        $remote_addr;
        proxy_set_header X-Forwarded-For  $proxy_add_x_forwarded_for;
<?php if (empty($OPT['nginxTransparentMode']) && !$VAR->domain->physicalHosting->proxySettings['nginxTransparentMode'] && !$VAR->domain->physicalHosting->proxySettings['nginxServeStatic']): ?>
        proxy_set_header X-Accel-Internal /internal-nginx-static-location;
<?php endif ?>
        access_log off;
