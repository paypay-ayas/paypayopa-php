<?php
require_once('init_client.php');

$resp =  $client->code->deleteCode($_GET['id']);
$resp = json_decode($resp, true);
?>
<pre>
    <?= json_encode($resp) ?>
</pre>