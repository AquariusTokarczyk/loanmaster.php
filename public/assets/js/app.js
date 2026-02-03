document.addEventListener("DOMContentLoaded", () => {
  // Bekräfta borttagning i admin. / Potwierdzanie usunięcia w adminie.
  document.querySelectorAll("[data-bekrafta-tabort]").forEach((btn) => {
    btn.addEventListener("click", (e) => {
      const ok = confirm("Ta bort item?");
      if (!ok) {
        e.preventDefault();
      }
    });
  });
});
