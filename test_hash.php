<?php
$hash = '$2y$12$LJ3m4ys3VEFgcXFtOHBPMem3K4GIlher1xo/GVOyOqeBkF.8H2RSu';
$passwords = ['123456', 'admin', 'password', '12345678', '123456789'];
foreach ($passwords as $p) {
    if (password_verify($p, $hash)) {
        echo "MATCH: $p\n";
        exit;
    }
}
echo "NO MATCH FOUND\n";
