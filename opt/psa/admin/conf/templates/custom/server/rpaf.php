<IfModule <?php echo $OPT['mod'] ?>>
<?php for ($ipAddresses = $VAR->server->ipAddresses->all, $ipAddress = reset($ipAddresses); $ipAddress; $ipAddress = next($ipAddresses)): ?>
    RPAFproxy_ips <?php echo $ipAddress->escapedAddress ?><?php for ($n = 1; $n < $VAR->server->webserver->apache->vhostIpCapacity && $ipAddress = next($ipAddresses); $n++) { echo " {$ipAddress->escapedAddress}"; } ?>

<?php endfor; ?>

</IfModule>