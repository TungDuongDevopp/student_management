const fs = require('fs');
const path = require('path');

const viewsDir = 'd:/Quanlisinhvienview/student_management/resources/views/admin/';
const files = fs.readdirSync(viewsDir).filter(f => f.endsWith('.blade.php'));

for (const file of files) {
    const filePath = path.join(viewsDir, file);
    let content = fs.readFileSync(filePath, 'utf8');
    
    let changed = false;
    const infoRegex1 = /html \+= `<span class="page-info">.*?<\/span>`;\s*/g;
    const infoRegex2 = /<span class="page-info">.*?<\/span>/g;
    
    if (infoRegex1.test(content) || infoRegex2.test(content)) {
        content = content.replace(infoRegex1, '');
        content = content.replace(infoRegex2, '');
        fs.writeFileSync(filePath, content);
        console.log('Updated ' + file);
    }
}
