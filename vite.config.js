import { defineConfig } from 'vite';
import path from 'path';

export default defineConfig({
    build: {
        outDir: path.resolve(import.meta.dirname, 'assets/out/build'),
        emptyOutDir: true,
        minify: true,
        sourcemap: true,
        rolldownOptions: {
            input: {
                main: path.resolve(import.meta.dirname, 'node_build/js/main.ts'),
                examplesmodule_style: path.resolve(import.meta.dirname, 'node_build/scss/examplesmodule.scss'),
            },
            output: {
                entryFileNames: 'js/[name].min.js',
                chunkFileNames: 'js/[name].min.js',
                assetFileNames: 'css/[name].min.[ext]',
            },
        },
    },
});
