document.addEventListener("DOMContentLoaded", function () {
  const select = document.getElementById("section-select");
  const addBtn = document.getElementById("add-section");
  const list = document.getElementById("sections-list");

  if (!select || !addBtn || !list) return;

  /* ADD SECTION */

  addBtn.addEventListener("click", function () {
    const section = select.value;

    if (!section) return;

    const li = document.createElement("li");
    li.dataset.section = section;

    li.innerHTML = `
            <span class="handle">⠿</span>
            ${section.charAt(0).toUpperCase() + section.slice(1)}
            <input type="hidden" name="bastovan_sections[]" value="${section}">
            <button type="button" class="remove-section">×</button>
        `;

    list.appendChild(li);

    select.value = "";
  });

  /* REMOVE SECTION */

  list.addEventListener("click", function (e) {
    if (e.target.classList.contains("remove-section")) {
      e.target.parentElement.remove();
    }
  });

  /* DRAG & DROP */

  if (typeof Sortable !== "undefined") {
    new Sortable(list, {
      animation: 150,
      handle: ".handle",
    });
  }
});
