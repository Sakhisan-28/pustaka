const nextPlugin = require("@next/eslint-plugin-next");

module.exports = {
  extends: ["next/core-web-vitals"],
  plugins: ["@next/next"],
  rules: {
    "@next/next/google-font-display": "warn",
  },
};
