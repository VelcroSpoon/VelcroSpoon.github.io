"use strict";

// This file handles all the interactive behaviour of my navigation bar.
// It mainly takes care of: opening/closing the hamburger menu on mobile
// and making the dropdown menu clickable instead of hover-only on small screens.

// I wrap everything in an IIFE so I don’t leak variables into the global scope
// and also make sure the script runs as soon as it’s loaded.
(function () {
  console.log("nav.js loaded");

  // --- Mobile hamburger menu ---
  // Here I grab the hamburger button and the container that holds all the nav links.
  // On small screens, clicking the hamburger will toggle a CSS class that shows/hides
  // the menu and updates the aria-expanded attribute for accessibility.
  const hamburger = document.getElementById("hamburger");
  const navLinks = document.querySelector(".nav-links");

  if (hamburger && navLinks) {
    hamburger.addEventListener("click", function () {
      const open = navLinks.classList.toggle("open");
      hamburger.setAttribute("aria-expanded", open ? "true" : "false");
    });
  }

  // --- Dropdown support on small screens (where hover doesn't work) ---
  // On desktop, the dropdown is opened with a CSS :hover rule.
  // On mobile, there is no “hover”, so I listen for clicks on the dropdown button
  // and manually toggle a class that shows or hides the submenu.
  document.querySelectorAll(".dropdown .dropbtn").forEach(function (button) {
    button.addEventListener("click", function (event) {
      const menu = button.parentElement.querySelector(".dropdown-content");
      if (!menu) return;

      // I only treat it as a toggle when we're in the mobile layout.
      // In my CSS, the mobile version sets position: static for the dropdown,
      // so I check that here to avoid fighting with the desktop hover behaviour.
      if (getComputedStyle(menu).position === "static") {
        event.preventDefault();
        menu.classList.toggle("open");
      }
    });
  });

  // --- Optional: clean up when resizing back to desktop ---
  // If the user rotates their device or resizes the window, I reset any
  // mobile-only state (like open menus) when we go back to desktop width.
  window.addEventListener("resize", function () {
    // On desktop width, ensure mobile-only classes are cleared so the nav doesn’t
    // look “stuck open” from a previous mobile state.
    if (window.innerWidth >= 900) {
      // Close nav menu if it was opened via hamburger.
      if (navLinks && navLinks.classList.contains("open")) {
        navLinks.classList.remove("open");
      }
      if (hamburger) {
        hamburger.setAttribute("aria-expanded", "false");
      }

      // Close any open dropdown-content menus that were toggled on mobile.
      document
        .querySelectorAll(".dropdown-content.open")
        .forEach(function (menu) {
          menu.classList.remove("open");
        });
    }
  });
})();
