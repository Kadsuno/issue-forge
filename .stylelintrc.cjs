/* eslint-env node */
module.exports = {
    extends: ['stylelint-config-standard', 'stylelint-config-tailwindcss'],
    plugins: ['stylelint-order'],
    rules: {
        // Keep rules pragmatic for existing codebase & Tailwind utilities
        'color-hex-length': 'short',
        'order/properties-alphabetical-order': null,
        'property-no-vendor-prefix': null,
        'length-zero-no-unit': null,
        'alpha-value-notation': null,
        'color-function-notation': null,
        'media-feature-range-notation': null,
        'keyframes-name-pattern': null,
        'declaration-empty-line-before': null,
        'at-rule-empty-line-before': null,
        'rule-empty-line-before': null,
    },
    ignoreFiles: ['**/vendor/**', '**/public/build/**', '**/dist/**', '**/node_modules/**'],
};
