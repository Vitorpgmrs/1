<?php
session_start();
session_destroy();
header("Location: login.php?sucesso=Você saiu com sucesso");
exit();
