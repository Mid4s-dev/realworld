import { defineConfig } from 'tailwindcss';
import tailwindcssVite from '@tailwindcss/vite';

export default defineConfig({
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {},
  },
  plugins: [],
});
