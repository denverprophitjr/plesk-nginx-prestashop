<?php
/** @var Template_Variable_Abstract $VAR  */
?>
<?php if ($VAR->server->tomcat->totalDomainsWithService > 0): ?>
    <IfModule mod_jk.c>
       JkWorkersFile "<?php echo $VAR->server->tomcat->workersFile ?>"
       <?php
        $VAR->getServiceNode()->capability()->web()->apache()->write(
            $VAR->server->tomcat->workersFile,
            "worker.list={$VAR->server->tomcat->workerName}\n" .
            "worker.{$VAR->server->tomcat->workerName}.port={$VAR->server->tomcat->warpPort}\n" .
            "worker.{$VAR->server->tomcat->workerName}.host=127.0.0.1\n" .
            "worker.{$VAR->server->tomcat->workerName}.type=ajp13\n"
        );
       ?>
       JkLogFile <?php echo $VAR->server->webserver->httpLogsDir ?>/mod_jk.log
       JkLogLevel info
    </IfModule>
<?php endif; ?>