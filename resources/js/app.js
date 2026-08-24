import "./bootstrap";

import Alpine from "alpinejs";

import flowbite from "flowbite/plugin";

window.Alpine = Alpine;

Alpine.start();

export default {
    content: [
        "./index.html",
        "./src/**/*.{vue,js,ts,jsx,tsx}",
        "./node_modules/flowbite/**/*.js",
    ],
    theme: {
        extend: {},
    },
    plugins: [flowbite],
};
