<?php

function isSetCookieLanguage()
{
    if(!isset($_COOKIE['language']))
        setcookie("language", "0", time() + 2592000, "/"); // set for one month
}

function loadHeaderText()
{
    $textLoad = yaml_parse_file("./../yml/header.yml");

    return $textLoad;
}

function loadFooterText()
{
    $textLoad = yaml_parse_file("./../yml/footer.yml");

    return $textLoad;
}

function loadConnectionText()
{
    $textLoad = yaml_parse_file("./../yml/connection.yml");

    return $textLoad;
}

function loadCustomerHistoryText()
{
    $textLoad = yaml_parse_file("./../yml/customerHistory.yml");

    return $textLoad;
}

function loadProfileText()
{
    $textLoad = yaml_parse_file("./../yml/profile.yml");

    return $textLoad;
}

function loadBillText()
{
    $textLoad = yaml_parse_file("./../yml/bill.yml");

    return $textLoad;
}


function loadDashboardText()
{
    $textLoad = yaml_parse_file("./../yml/dashboard.yml");

    return $textLoad;
}
