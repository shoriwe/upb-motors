<?php

require 'middleware/ensure_login.php';

header('Location: /dashboard.php', true, 307);
exit;