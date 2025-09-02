<?php
session_start();
session_unset();
session_destroy();

// JS redirect to landing page
echo "<script>window.location.href='index.php';</script>";
exit;
?>
