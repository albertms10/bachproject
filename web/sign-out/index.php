<?php
session_start();
session_destroy();
define("HTML_PATH", "/bachproject/");
header("location:" . HTML_PATH);
