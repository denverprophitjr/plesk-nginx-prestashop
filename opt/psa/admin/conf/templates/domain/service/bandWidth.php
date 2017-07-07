<?php if ($VAR->domain->physicalHosting->trafficBandwidth < 0 && $VAR->domain->physicalHosting->maximumConnection < 0) return; ?>
<?php if ($VAR->server->webserver->proxyActive) return; ?>
<IfModule mod_bw.c>
    BandwidthModule On
    ForceBandWidthModule On
<?php if ($VAR->domain->physicalHosting->trafficBandwidth >= 0): ?>
    Bandwidth all "<?php echo $VAR->domain->physicalHosting->trafficBandwidth ?>"
<?php endif; ?>
<?php if ($VAR->domain->physicalHosting->maximumConnection >= 0): ?>
    MaxConnection all "<?php echo $VAR->domain->physicalHosting->maximumConnection ?>"
<?php endif; ?>
    BandWidthError 510
</IfModule>
