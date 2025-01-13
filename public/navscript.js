document.addEventListener("DOMContentLoaded", () => {
    // Mobile menu toggle
    const mobileMenuButton = document.getElementById("mobile-menu-button");
    const mobileMenu = document.getElementById("mobile-menu");

    if (mobileMenuButton && mobileMenu) {
        mobileMenuButton.addEventListener("click", () => {
            mobileMenu.style.display = mobileMenu.style.display === "block" ? "none" : "block";
        });
    }

    // Sign-in menu toggle
    const signinMenuButton = document.getElementById("signin");
    const signinMenu = document.getElementById("signinmenu");

    if (signinMenuButton && signinMenu) {
        signinMenuButton.addEventListener("click", (event) => {
            event.stopPropagation(); // Prevent event from bubbling up
            signinMenu.style.display = signinMenu.style.display === "block" ? "none" : "block";
        });

        // Hide the menu when clicking outside of it
        document.addEventListener("click", (event) => {
            if (!signinMenu.contains(event.target) && event.target !== signinMenuButton) {
                signinMenu.style.display = "none";
            }
        });
    }
});
