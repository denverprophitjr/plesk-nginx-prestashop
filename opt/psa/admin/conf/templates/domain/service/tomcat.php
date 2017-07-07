<?php foreach ($VAR->domain->tomcat->all as $application): ?>
    <IfModule mod_jk.c>
        JkMount /<?php echo $application->name ?> <?php echo $VAR->server->tomcat->workerName ?>

        JkMount /<?php echo $application->name ?>/* <?php echo $VAR->server->tomcat->workerName ?>

    </IfModule>
<?php endforeach; ?>
