<?php
require_once('init_client.php');

$resp =  $client->code->delete($_GET['id']);

?>
<pre>
    <?= json_encode($resp) ?>
</pre>