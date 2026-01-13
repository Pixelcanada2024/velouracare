// eslint.config.js
// import js from "@eslint/js";
import react from "eslint-plugin-react";

export default [
  // js.configs.recommended,
  {
    files: ["**/*.js", "**/*.jsx"],
    languageOptions: {
      ecmaVersion: "latest",
      sourceType: "module",
      globals: {
        browser: true,
        node: true,
      },
      parserOptions: {
        ecmaFeatures: {
          jsx: true,
        },
      },
    },
    plugins: {
      react,
    },
    rules: {
      "no-dupe-keys": "warn", 
      "quotes": ["warn", "double"],
    },
  },
];
