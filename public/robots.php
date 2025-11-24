<?php
/**
 * Created by PhpStorm.
 * User: ondrejbohac
 * Date: 19.11.16
 * Time: 11:18
 */
$domain = substr($_GET['domain'], 1 + strrpos($_GET['domain'], '.'));
header('Content-Type: text/plain');
echo file_get_contents("sitemap_{$domain}.txt");