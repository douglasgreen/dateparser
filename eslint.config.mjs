// version: 1.1.0
// modified: 2026-02-22

import js from '@eslint/js';
import tseslint from 'typescript-eslint';
import pluginVue from 'eslint-plugin-vue';
import pluginPlaywright from 'eslint-plugin-playwright';
import pluginSecurity from 'eslint-plugin-security';
import pluginUnicorn from 'eslint-plugin-unicorn';
import pluginJsxA11y from 'eslint-plugin-jsx-a11y';
import globals from 'globals';
import vueParser from 'vue-eslint-parser';
import eslintConfigPrettier from 'eslint-config-prettier';
import pluginYaml from 'eslint-plugin-yaml';
import yamlParser from 'yaml-eslint-parser';

export default tseslint.config(
    // Base JavaScript
    js.configs.recommended,

    // TypeScript & Vue Configuration
    ...tseslint.configs.recommendedTypeChecked,
    ...tseslint.configs.strictTypeChecked,
    ...pluginVue.configs['flat/recommended'],

    // Global settings & Parsers
    {
        languageOptions: {
            globals: {
                ...globals.browser,
                ...globals.node,
                ...globals.es2023,
            },
            parser: vueParser, // Must use vue-parser for .vue files
            parserOptions: {
                parser: tseslint.parser, // Use TS parser inside .vue
                project: true,
                tsconfigRootDir: import.meta.dirname,
                extraFileExtensions: ['.vue'],
            },
        },
    },

    // YAML Configuration
    ...pluginYaml.configs['flat/recommended'],
    {
        files: ['**/*.{yml,yaml}'],
        languageOptions: {
            parser: yamlParser,
        },
        rules: {
            'yml/quotes': ['error', { prefer: 'single', avoidEscape: true }],
            'yml/no-empty-document': 'error',
            'yml/indent': ['error', 2],
            'yml/block-mapping-question-indicator-newline': 'error',
        },
    },

    // Security baseline
    {
        plugins: { security: pluginSecurity },
        rules: {
            ...pluginSecurity.configs.recommended.rules, // Use recommended as base
            'security/detect-object-injection': 'off', // Often too noisy
        },
    },

    // Code quality
    {
        plugins: { unicorn: pluginUnicorn },
        rules: {
            'unicorn/consistent-function-scoping': 'warn',
            'unicorn/no-abusive-eslint-disable': 'error',
        },
    },

    // File-specific overrides for tests
    {
        files: [
            'tests/**/*.ts',
            'tests/**/*.js',
            '**/*.spec.ts',
            '**/*.test.ts',
        ],
        ...pluginPlaywright.configs['flat/recommended'],
    },

    // Accessibility for Vue/JSX
    {
        files: ['**/*.{vue,jsx,tsx}'],
        plugins: { 'jsx-a11y': pluginJsxA11y },
        rules: {
            ...pluginJsxA11y.configs.recommended.rules,
        },
    },

    // Custom TypeScript/General Rules
    {
        rules: {
            '@typescript-eslint/no-explicit-any': 'error',
            '@typescript-eslint/consistent-type-imports': 'error',
            'no-console': ['warn', { allow: ['warn', 'error'] }],
        },
    },

    // Ignores
    {
        ignores: [
            'dist/**',
            'node_modules/**',
            'coverage/**',
            '*.config.*',
            'playwright-report/**',
            'test-results/**',
        ],
    },

    // PRETTIER - This MUST be last to override conflicting rules
    eslintConfigPrettier,
);
