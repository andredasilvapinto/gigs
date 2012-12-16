<?php

$relative_url = 'index.php?r=token/cronRefreshSoundcloud';

$absolute_url = "http://".$_SERVER['HTTP_HOST']
    .rtrim(dirname($_SERVER['PHP_SELF']), '/\\')
    ."/".$relative_url;

header("Location: $absolute_url");
