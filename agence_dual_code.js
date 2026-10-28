"use strict";

const menuToggle = document.querySelector("#menu-toggle");
const navigation = document.querySelector("#primary-navigation");
const contactForm = document.querySelector("#contact-form");
const contactStatus = document.querySelector("#contact-status");
const currentYear = document.querySelector("#current-year");

if (menuToggle && navigation) {
    const setMenuOpen = (isOpen) => {
        navigation.hidden = !isOpen;
        menuToggle.setAttribute("aria-expanded", String(isOpen));
        menuToggle.setAttribute("aria-label", isOpen ? "Fermer le menu" : "Ouvrir le menu");
    };

    menuToggle.addEventListener("click", () => {
        setMenuOpen(menuToggle.getAttribute("aria-expanded") !== "true");
    });

    navigation.addEventListener("click", (event) => {
        if (event.target instanceof Element && event.target.closest("a")) {
            setMenuOpen(false);
        }
    });

    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape") {
            setMenuOpen(false);
        }
    });

    window.matchMedia("(min-width: 761px)").addEventListener("change", () => {
        setMenuOpen(false);
    });
}

if (contactForm && contactStatus) {
    contactForm.addEventListener("submit", (event) => {
        event.preventDefault();
        contactStatus.hidden = false;
    });
}

if (currentYear) {
    currentYear.textContent = String(new Date().getFullYear());
}

const textScale = document.querySelector("#text-scale");
const textScaleValue = document.querySelector("#text-scale-value");
const contrastMode = document.querySelector("#contrast-mode");
const readingMode = document.querySelector("#reading-mode");
const resetAccessibility = document.querySelector("#reset-accessibility");

if (textScale && textScaleValue && contrastMode && readingMode && resetAccessibility) {
    const storageKey = "dual-code-accessibility";
    const defaultPreferences = { scale: 100, contrast: false, reading: false };
    const textElements = Array.from(document.querySelectorAll(
        "h1, h2, h3, p, a, button, label, input, textarea, li, summary, span, output"
    )).filter((element) => !element.closest('[aria-hidden="true"]'));
    const baseFontSizes = new Map(textElements.map((element) => [
        element,
        Number.parseFloat(getComputedStyle(element).fontSize)
    ]));
    let preferences = { ...defaultPreferences };

    try {
        const savedPreferences = JSON.parse(localStorage.getItem(storageKey) || "{}");
        const savedScale = Number(savedPreferences.scale);

        if (Number.isFinite(savedScale) && savedScale >= 100 && savedScale <= 150) {
            preferences.scale = savedScale;
        }
        preferences.contrast = savedPreferences.contrast === true;
        preferences.reading = savedPreferences.reading === true;
    } catch (error) {
        preferences = { ...defaultPreferences };
    }

    const applyAccessibilityPreferences = () => {
        const scale = Number(textScale.value);
        textScaleValue.value = `${scale} %`;
        textScaleValue.textContent = `${scale} %`;
        document.documentElement.dataset.textScale = String(scale);

        for (const element of textElements) {
            const baseSize = baseFontSizes.get(element);
            element.style.fontSize = scale === 100 ? "" : `${baseSize * scale / 100}px`;
        }

        document.documentElement.dataset.contrast = contrastMode.checked ? "high" : "normal";
        document.documentElement.dataset.reading = readingMode.checked ? "comfortable" : "default";

        try {
            localStorage.setItem(storageKey, JSON.stringify({
                scale,
                contrast: contrastMode.checked,
                reading: readingMode.checked
            }));
        } catch (error) {
            return;
        }
    };

    textScale.value = String(preferences.scale);
    contrastMode.checked = preferences.contrast;
    readingMode.checked = preferences.reading;
    applyAccessibilityPreferences();

    textScale.addEventListener("input", applyAccessibilityPreferences);
    contrastMode.addEventListener("change", applyAccessibilityPreferences);
    readingMode.addEventListener("change", applyAccessibilityPreferences);
    resetAccessibility.addEventListener("click", () => {
        textScale.value = String(defaultPreferences.scale);
        contrastMode.checked = defaultPreferences.contrast;
        readingMode.checked = defaultPreferences.reading;
        applyAccessibilityPreferences();
    });

    let resizeTimer;
    window.addEventListener("resize", () => {
        window.clearTimeout(resizeTimer);
        resizeTimer = window.setTimeout(() => {
            for (const element of textElements) {
                element.style.removeProperty("font-size");
                baseFontSizes.set(element, Number.parseFloat(getComputedStyle(element).fontSize));
            }
            applyAccessibilityPreferences();
        }, 120);
    });
}
