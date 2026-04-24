import forms from "@tailwindcss/forms";
import defaultTheme from "tailwindcss/defaultTheme";

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: "class",
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/**/*.js",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Definisikan palet warna Anda di sini.
                // Mari kita standarisasi warna utama menjadi nuansa 'Navy/Blue' yang profesional.
                primary: {
                    50: "#eff6ff",
                    100: "#dbeafe",
                    500: "#3b82f6", // Biru standar
                    600: "#2563eb", // Biru tombol normal
                    700: "#1d4ed8", // Biru tombol saat di-hover
                    800: "#1e40af", // Navy tua (Active/Border)
                    900: "#1e3a8a",
                },
                secondary: {
                    500: "#64748b",
                    600: "#475569",
                    700: "#334155",
                },
            },
        },
    },

    plugins: [require("@tailwindcss/forms")],
};
