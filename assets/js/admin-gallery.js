jQuery(function ($) {
  /* ===================================================
     MEDIA UPLOADER — Galerija
  =================================================== */

  var frame;

  $("#bastovan-galerija-btn").on("click", function (e) {
    e.preventDefault();
    if (frame) {
      frame.open();
      return;
    }
    frame = wp.media({
      title: "Izaberi slike projekta",
      button: { text: "Dodaj slike" },
      multiple: true,
    });
    frame.on("select", function () {
      var ids = [],
        previews = "";
      frame
        .state()
        .get("selection")
        .each(function (att) {
          ids.push(att.id);
          previews +=
            '<img src="' +
            att.attributes.url +
            '" style="width:80px;height:80px;object-fit:cover;border-radius:6px;">';
        });
      $("#bastovan_galerija").val(ids.join(","));
      $("#bastovan-galerija-preview").html(previews);
    });
    frame.open();
  });

  /* ===================================================
     SINGLE IMAGE UPLOAD — Pre / Posle
  =================================================== */

  $(document).on("click", ".bastovan-image-upload", function (e) {
    e.preventDefault();
    var btn = $(this);
    var targetId = btn.data("target");
    var previewId = btn.data("preview");

    var singleFrame = wp.media({
      title: "Izaberi sliku",
      button: { text: "Koristi ovu sliku" },
      multiple: false,
    });

    singleFrame.on("select", function () {
      var att = singleFrame.state().get("selection").first().toJSON();
      var preview = $("#" + previewId);

      $("#" + targetId).val(att.id);
      preview.html(
        '<img src="' +
          att.url +
          '" style="max-width:200px;border-radius:8px;display:block;margin-bottom:8px;">' +
          '<button type="button" class="button bastovan-image-remove" ' +
          'data-target="' +
          targetId +
          '" data-preview="' +
          previewId +
          '">✕ Ukloni</button>',
      );
    });

    singleFrame.open();
  });

  $(document).on("click", ".bastovan-image-remove", function () {
    var targetId = $(this).data("target");
    var previewId = $(this).data("preview");
    $("#" + targetId).val("");
    $("#" + previewId).html("");
  });

  /* ===================================================
     TIP USLUGE — Dinamični select
  =================================================== */

  $(".bastovan-tip-checkbox").on("change", function () {
    var tip = $(this).data("tip");
    var select = $('.bastovan-usluge-select[data-tip="' + tip + '"]');

    if ($(this).is(":checked")) {
      select.show();
    } else {
      select.hide();
      select.find("select").val("");
    }

    updateHidden();
  });

  $(document).on("change", ".bastovan-usluge-select select", function () {
    updateHidden();
  });

  function updateHidden() {
    var ids = [];
    $(".bastovan-usluge-select select").each(function () {
      if ($(this).val()) ids.push($(this).val());
    });
    $("#bastovan_usluge_hidden").val(ids.join(","));
  }

  /* ===================================================
     REPEATER — Stavke
  =================================================== */

  $("#bastovan-stavka-add").on("click", function () {
    var row = $(
      '<div class="bastovan-stavka">' +
        "<span>⠿</span>" +
        '<input type="text" name="bastovan_stavke_naziv[]" placeholder="Naziv (npr. Trimer)" class="regular-text">' +
        '<input type="text" name="bastovan_stavke_cena[]" placeholder="Cena (npr. 9-15 din/m²)" class="regular-text">' +
        '<button type="button" class="button bastovan-stavka-remove">✕</button>' +
        "</div>",
    );
    $("#bastovan-stavke-wrap").append(row);
  });

  $(document).on("click", ".bastovan-stavka-remove", function () {
    $(this).closest(".bastovan-stavka").remove();
  });

  if ($.fn.sortable) {
    $("#bastovan-stavke-wrap").sortable({
      handle: "span",
      axis: "y",
      cursor: "grab",
    });
  }
});
