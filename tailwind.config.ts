/** @type {import('tailwindcss').Config} */
const warm = {
  background: "#FAF8F5",
  surface: "#FFFFFF",
  primary: "#3B82F6",
  accent: "#D97706",
  text: "#1F2937",
  muted: "#6B7280",
  border: "#E5E1DA",
}

export default {
  darkMode: "class",
  content: [
    "./src/app/**/*.{js,ts,jsx,tsx}",
    "./src/components/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {
      colors: {
        border: warm.border,
        input: warm.border,
        ring: warm.primary,
        background: warm.background,
        foreground: warm.text,
        primary: { DEFAULT: warm.primary, foreground: "#FFFFFF" },
        secondary: { DEFAULT: warm.surface, foreground: warm.text },
        destructive: { DEFAULT: "#EF4444", foreground: "#FFFFFF" },
        muted: { DEFAULT: warm.muted, foreground: warm.text },
        accent: { DEFAULT: warm.accent, foreground: "#FFFFFF" },
        popover: { DEFAULT: warm.surface, foreground: warm.text },
        card: { DEFAULT: warm.surface, foreground: warm.text },
      },
      fontFamily: {
        sans: ["var(--font-inter)"],
        serif: ["var(--font-lora)"],
      },
    },
  },
  plugins: [],
}
