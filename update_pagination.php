<?php
$directory = __DIR__ . '/resources/views/admin';
$files = glob($directory . '/*.blade.php');
$count = 0;

foreach ($files as $file) {
    $content = file_get_contents($file);
    
    // Pattern to match the renderPagination function block
    $pattern = '/([ \t]*)function renderPagination\(totalPages\) \{.*?(?=\n\1\})\n\1\}/s';
    
    $newContent = preg_replace_callback($pattern, function($matches) {
        $indent = $matches[1];
        
        // Find what the "bản ghi" / "đơn" text is by extracting it from the original match if possible
        $original = $matches[0];
        $infoText = 'bản ghi'; // default
        if (preg_match('/<span class="page-info">\$\{filteredData\.length\}\s+([^<]+)<\/span>/', $original, $m)) {
            $infoText = $m[1];
        }

        $newFunc = $indent . 'function renderPagination(totalPages) {' . "\n";
        $newFunc .= $indent . '    const pg = document.getElementById(\'pagination\');' . "\n";
        $newFunc .= $indent . '    if (totalPages <= 1) {' . "\n";
        $newFunc .= $indent . '        pg.innerHTML = \'\';' . "\n";
        $newFunc .= $indent . '        return;' . "\n";
        $newFunc .= $indent . '    }' . "\n";
        $newFunc .= $indent . '    let html = `<button onclick="goPage(1)" ${currentPage===1?\'disabled\':\'\'}>«</button>`;' . "\n";
        $newFunc .= $indent . '    html += `<button onclick="goPage(${currentPage-1})" ${currentPage===1?\'disabled\':\'\'}>‹</button>`;' . "\n\n";
        
        $newFunc .= $indent . '    let startPage = Math.max(1, currentPage - 2);' . "\n";
        $newFunc .= $indent . '    let endPage = Math.min(totalPages, currentPage + 2);' . "\n";
        $newFunc .= $indent . '    if (endPage - startPage < 4) {' . "\n";
        $newFunc .= $indent . '        if (startPage === 1) endPage = Math.min(totalPages, 5);' . "\n";
        $newFunc .= $indent . '        else if (endPage === totalPages) startPage = Math.max(1, totalPages - 4);' . "\n";
        $newFunc .= $indent . '    }' . "\n\n";
        
        $newFunc .= $indent . '    for (let i = startPage; i <= endPage; i++) {' . "\n";
        $newFunc .= $indent . '        html += `<button class="${i===currentPage?\'active\':\'\'}" onclick="goPage(${i})">${i}</button>`;' . "\n";
        $newFunc .= $indent . '    }' . "\n\n";
        
        $newFunc .= $indent . '    html += `<span class="page-info">${filteredData.length} ' . $infoText . '</span>`;' . "\n";
        $newFunc .= $indent . '    html += `<button onclick="goPage(${currentPage+1})" ${currentPage===totalPages?\'disabled\':\'\'}>›</button>`;' . "\n";
        $newFunc .= $indent . '    html += `<button onclick="goPage(${totalPages})" ${currentPage===totalPages?\'disabled\':\'\'}>»</button>`;' . "\n";
        $newFunc .= $indent . '    pg.innerHTML = html;' . "\n";
        $newFunc .= $indent . '}';
        
        return $newFunc;
    }, $content, -1, $countReplaced);
    
    if ($countReplaced > 0) {
        file_put_contents($file, $newContent);
        $count++;
    }
}

echo "Da cap nhat thanh cong $count file admin.\n";
