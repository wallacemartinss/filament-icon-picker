import * as esbuild from 'esbuild';
import * as fs from 'fs';
import * as path from 'path';

const isDev = process.argv.includes('--dev');

// Ensure dist directories exist
const dirs = [
    './resources/dist/js',
    './resources/dist/css'
];

dirs.forEach(dir => {
    if (!fs.existsSync(dir)) {
        fs.mkdirSync(dir, { recursive: true });
    }
});

// Build Alpine Component
esbuild.build({
    entryPoints: ['./resources/js/icon-picker.js'],
    outfile: './resources/dist/js/icon-picker.js',
    bundle: true,
    minify: !isDev,
    platform: 'neutral',
    target: ['es2020'],
    format: 'esm',
    sourcemap: isDev,
}).then(() => {
    console.log('Alpine component built successfully');
}).catch((error) => {
    console.error('Failed to build Alpine component:', error);
    process.exit(1);
});

// Copy CSS (in production you might want to process with PostCSS/Tailwind)
const cssSource = './resources/css/icon-picker.css';
const cssDest = './resources/dist/css/icon-picker.css';

fs.copyFileSync(cssSource, cssDest);
console.log('CSS copied successfully');
