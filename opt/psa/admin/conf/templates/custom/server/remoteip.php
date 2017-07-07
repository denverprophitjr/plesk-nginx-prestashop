<IfModule <?php echo $OPT['mod'] ?>>
    <?php for ($ipAddresses = $VAR->server->ipAddresses->all, $ipAddress = reset($ipAddresses); $ipAddress; $ipAddress = next($ipAddresses)): ?>
    RemoteIPInternalProxy <?php echo $ipAddress->address ?><?php for ($n = 1; $n < $VAR->server->webserver->apache->vhostIpCapacity && $ipAddress = next($ipAddresses); $n++) { echo " {$ipAddress->address}"; } ?>    
    <?php endfor; ?>
    RemoteIPHeader X-Forwarded-For
</IfModule>