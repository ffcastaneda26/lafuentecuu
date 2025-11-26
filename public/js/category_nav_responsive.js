document
    .getElementById("mobile-menu-button")
    .addEventListener("click", function () {
        const menu = document.getElementById("mobile-menu");
        const icon = this.querySelector("i");

        menu.classList.toggle("hidden");
        icon.classList.toggle("fa-bars");
        icon.classList.toggle("fa-times");
    });
