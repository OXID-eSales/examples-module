import { defineConfig } from 'vitest/config';

export default defineConfig({
    test: {
        environment: 'jsdom', // Simulates a browser environment (document, window)
        include: ['tests/Js/**/*.test.ts'],
        coverage: {
            provider: 'v8',
            reporter: ['text', 'lcov'], // Text output and LCOV format (for CI)
            reportsDirectory: 'tests/Reports/CoverageJs',
            include: ['node_build/js/**/*.ts'],
            exclude: ['node_build/js/main.ts'], // Vite entry file, only wires the modules together
        },
    },
});
