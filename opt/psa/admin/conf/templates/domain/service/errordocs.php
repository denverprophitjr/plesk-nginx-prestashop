Alias /error_docs <?php echo $VAR->webspace->vhostDir . '/' . $VAR->domain->physicalHosting->errorDocsDir ?>

<?php foreach(array(
    '400' => 'bad_request.html',
    '401' => 'unauthorized.html',
    '403' => 'forbidden.html',
    '404' => 'not_found.html',
    '500' => 'internal_server_error.html',

    '405' => 'method_not_allowed.html',
    '406' => 'not_acceptable.html',
    '407' => 'proxy_authentication_required.html',
    '412' => 'precondition_failed.html',
    '414' => 'request_uri_too_long.html',
    '415' => 'unsupported_media_type.html',
    '501' => 'not_implemented.html',
    '502' => 'bad_gateway.html',
    '503' => 'maintenance.html',
) as $errCode => $errFile): ?>
ErrorDocument <?php echo $errCode?> /<?php echo $VAR->domain->physicalHosting->errorDocsDir . '/' . $errFile ?>

<?php endforeach; ?>
