<?php

function init_site(site $site)
{
    $site->addHeader("../includes/header.php");
    $site->addFooter("../includes/footer.php");
}

function logMessage($message)
{
    // echo $message;
}
