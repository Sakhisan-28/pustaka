export default [
  {
    files: ["**/*.{js,jsx,ts,tsx}"],
    languageOptions: {
      ecmaVersion: 2022,
      sourceType: "module,
      globals: {
        window: "readonly",
        document: "readonly",
        process: "readonly",
      },
    },
    rules: {
      "no-unused-vars": "off",
      "@typescript-eslint/no-unused-vars": "off",
      "no-undef": "off",
    },
  },
  {
    ignores: [".next/", "node_modules/", "drizzle/"],
  },
]
