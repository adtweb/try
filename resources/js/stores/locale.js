import Alpine from "alpinejs";

Alpine.store(
    "locale",
    document.querySelector('meta[name="locale"]').getAttribute("content")
);
