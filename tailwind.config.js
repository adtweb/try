/** @type {import('tailwindcss').Config} */
export default {
    prefix: "tw-",
    content: ["./resources/**/*.blade.php", "./resources/**/*.js"],
    theme: {
        colors: {
            primary: "#e25553",
            "font-blue": "#1e4759",
            secondary: "#666666",
            blue: "#1e4759",
            danger: "#e25553",
            white: "#ffffff",
            success: "green",
            transparent: "transparent",
        },
        extend: {},
    },
    plugins: [],
};
