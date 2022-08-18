<?php

echo ('<pre>');
print_r($_POST);
echo ('<hr>');
print_r($_POST['dados']);
echo ('<hr>');
$post = json_decode($_POST['dados'], true);
print_r($post);
echo ('</pre>');
die;
