import { ClassicEditor } from "@ckeditor/ckeditor5-editor-classic";

import { Essentials } from "@ckeditor/ckeditor5-essentials";
import { Paragraph } from "@ckeditor/ckeditor5-paragraph";
import {
    Bold,
    Italic,
    Underline,
    Subscript,
    Superscript,
} from "@ckeditor/ckeditor5-basic-styles";
import {
    SpecialCharacters,
    SpecialCharactersMathematical,
    SpecialCharactersText,
} from "@ckeditor/ckeditor5-special-characters";
import { List } from "@ckeditor/ckeditor5-list";
import { WordCount } from "@ckeditor/ckeditor5-word-count";
import { PasteFromOffice } from "@ckeditor/ckeditor5-paste-from-office";
// import { Clipboard } from "@ckeditor/ckeditor5-clipboard";
import { RemoveFormat } from "@ckeditor/ckeditor5-remove-format";

function SpecialCharactersGreek(editor) {
    editor.plugins.get("SpecialCharacters").addItems(
        "Greek",
        [
            { title: "capital Alpha", character: "Α" },
            { title: "Alpha", character: "α" },
            { title: "capital Beta", character: "Β" },
            { title: "Beta", character: "β" },
            { title: "capital gamma", character: "Γ" },
            { title: "gamma", character: "γ" },
            { title: "capital delta", character: "Δ" },
            { title: "delta", character: "δ" },
            { title: "capital epsilon", character: "Ε" },
            { title: "epsilon", character: "ε" },
            { title: "capital zeta", character: "Ζ" },
            { title: "zeta", character: "ζ" },
            { title: "capital eta", character: "Η" },
            { title: "eta", character: "η" },
            { title: "capital theta", character: "Θ" },
            { title: "theta", character: "θ" },
            { title: "capital iota", character: "Ι" },
            { title: "iota", character: "ι" },
            { title: "capital kappa", character: "Κ" },
            { title: "kappa", character: "κ" },
            { title: "capital lambda", character: "Λ" },
            { title: "lambda", character: "λ" },
            { title: "capital mu", character: "Μ" },
            { title: "mu", character: "μ" },
            { title: "capital nu", character: "Ν" },
            { title: "nu", character: "ν" },
            { title: "capital xi", character: "Ξ" },
            { title: "xi", character: "ξ" },
            { title: "capital omicron", character: "Ο" },
            { title: "omicron", character: "ο" },
            { title: "capital pi", character: "Π" },
            { title: "pi", character: "π" },
            { title: "capital rho", character: "Ρ" },
            { title: "rho", character: "ρ" },
            { title: "capital sigma", character: "Σ" },
            { title: "sigma", character: "σ" },
            { title: "capital tau", character: "Τ" },
            { title: "tau", character: "τ" },
            { title: "capital upsilon", character: "Υ" },
            { title: "upsilon", character: "υ" },
            { title: "capital phi", character: "Φ" },
            { title: "phi", character: "φ" },
            { title: "capital chi", character: "Χ" },
            { title: "chi", character: "χ" },
            { title: "capital psi", character: "Ψ" },
            { title: "psi", character: "ψ" },
            { title: "capital omega", character: "Ω" },
            { title: "omega", character: "ω" },
        ],
        { label: "Greek" }
    );
}

window.TextEditorSettings = {
    plugins: [
        Essentials,
        Paragraph,
        Bold,
        Italic,
        Underline,
        SpecialCharacters,
        SpecialCharactersGreek,
        SpecialCharactersMathematical,
        SpecialCharactersText,
        List,
        WordCount,
        Subscript,
        Superscript,
        PasteFromOffice,
        // Clipboard,
        RemoveFormat,
    ],
    toolbar: [
        "undo",
        "redo",
        "|",
        "bold",
        "italic",
        "underline",
        "|",
        "subscript",
        "superscript",
        "|",
        "specialCharacters",
        "|",
        "removeFormat",
    ],
    wordCount: {
        onUpdate: (stats) => {
            document.dispatchEvent(
                new CustomEvent("text-editor-update", {
                    detail: {
                        characters: stats.characters,
                        words: stats.words,
                    },
                })
            );
        },
    },
};

window.TitleEditorSettings = {
    plugins: [
        Essentials,
        Paragraph,
        Bold,
        Italic,
        Underline,
        SpecialCharacters,
        SpecialCharactersGreek,
        SpecialCharactersMathematical,
        SpecialCharactersText,
        List,
        WordCount,
        Subscript,
        Superscript,
        RemoveFormat,
        // Clipboard,
    ],
    toolbar: [
        "undo",
        "redo",
        "|",
        "italic",
        "underline",
        "|",
        "subscript",
        "superscript",
        "|",
        "specialCharacters",
        "|",
        "removeFormat",
    ],
    autoParagraph: false,
};

window.ClassicEditor = ClassicEditor;
