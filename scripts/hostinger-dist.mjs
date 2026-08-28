/**
 * Hostinger expects a root-level output folder (usually `dist`) after `npm run build`.
 * Laravel Vite writes to `public/build`, so we mirror that into `dist`.
 */
import fs from 'node:fs';
import path from 'node:path';

const root = process.cwd();
const source = path.join(root, 'public', 'build');
const target = path.join(root, 'dist');

function copyDir(from, to) {
    fs.mkdirSync(to, { recursive: true });
    for (const entry of fs.readdirSync(from, { withFileTypes: true })) {
        const src = path.join(from, entry.name);
        const dest = path.join(to, entry.name);
        if (entry.isDirectory()) {
            copyDir(src, dest);
        } else {
            fs.copyFileSync(src, dest);
        }
    }
}

if (!fs.existsSync(source)) {
    console.error('ERROR: public/build was not created by Vite.');
    process.exit(1);
}

fs.rmSync(target, { recursive: true, force: true });
copyDir(source, target);
console.log('Hostinger output ready: dist/ (mirrored from public/build)');
