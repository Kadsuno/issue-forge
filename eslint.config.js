import js from '@eslint/js';
import eslintPluginImport from 'eslint-plugin-import';

export default [
    {
        ignores: ['dist/**', 'public/build/**', 'vendor/**', 'node_modules/**'],
    },
    js.configs.recommended,
    {
        languageOptions: {
            ecmaVersion: 'latest',
            sourceType: 'module',
            globals: {
                window: 'readonly',
                document: 'readonly',
                navigator: 'readonly',
                Alpine: 'readonly',
                module: 'readonly',
                console: 'readonly',
                setTimeout: 'readonly',
                setInterval: 'readonly',
                clearTimeout: 'readonly',
                clearInterval: 'readonly',
                requestAnimationFrame: 'readonly',
                cancelAnimationFrame: 'readonly',
                IntersectionObserver: 'readonly',
                MutationObserver: 'readonly',
            },
        },
        plugins: {
            import: eslintPluginImport,
        },
        rules: {
            'no-unused-vars': ['error', { argsIgnorePattern: '^_', varsIgnorePattern: '^_' }],
            'no-undef': 'error',
            'import/order': [
                'warn',
                {
                    groups: [['builtin', 'external'], 'internal', ['parent', 'sibling', 'index']],
                    'newlines-between': 'always',
                },
            ],
        },
    },
];
