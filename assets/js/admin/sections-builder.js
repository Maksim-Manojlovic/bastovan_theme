document.addEventListener("DOMContentLoaded", function () {
  const select = document.getElementById("section-select");
  const add = document.getElementById("add-section");
  const list = document.getElementById("sections-list");

  if (!select) return;

  add.addEventListener("click", function () {
    const val = select.value;

    if (!val) return;

    const li = document.createElement("li");

    li.dataset.section = val;

    li.innerHTML =
      val +
      '<input type="hidden" name="bastovan_sections[]" value="' +
      val +
      '">' +
      '<button type="button" class="remove-section">×</button>';

    list.appendChild(li);
  });

  list.addEventListener("click", function (e) {
    if (e.target.classList.contains("remove-section")) {
      e.target.parentElement.remove();
    }
  });
});
