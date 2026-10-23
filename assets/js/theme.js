/* Light / dark theme for admin/ and tech/.
   Loaded at the top of <head> so the saved theme is applied before the page paints.
   Light is the default; the choice is remembered per browser.
   Any element with [data-theme-toggle] switches the theme, and a "themechange" event
   is fired on document so charts can re-colour themselves. */
(function () {
   var KEY = "tcea_theme";
   var root = document.documentElement;

   function stored() {
      try { return localStorage.getItem(KEY); } catch (e) { return null; }
   }

   function apply(theme) {
      root.setAttribute("data-theme", theme);
      var dark = theme === "dark";
      var label = dark ? "Switch to light mode" : "Switch to dark mode";
      document.querySelectorAll("[data-theme-toggle]").forEach(function (btn) {
         btn.setAttribute("aria-pressed", dark ? "true" : "false");
         btn.setAttribute("title", label);
         btn.setAttribute("aria-label", label);
      });
   }

   apply(stored() === "dark" ? "dark" : "light");

   window.setTheme = function (theme) {
      apply(theme);
      try { localStorage.setItem(KEY, theme); } catch (e) {}
      document.dispatchEvent(new CustomEvent("themechange", { detail: { theme: theme } }));
   };

   document.addEventListener("click", function (e) {
      var btn = e.target.closest && e.target.closest("[data-theme-toggle]");
      if (!btn) return;
      window.setTheme(root.getAttribute("data-theme") === "dark" ? "light" : "dark");
   });

   // toggle buttons don't exist yet while <head> runs; label them once the page is parsed
   document.addEventListener("DOMContentLoaded", function () {
      apply(root.getAttribute("data-theme"));
   });
})();
