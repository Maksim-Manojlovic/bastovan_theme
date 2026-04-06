/**
 * Admin — wp.media image picker
 * Handles: single image upload/remove buttons across all meta boxes
 */
(function ($) {
  "use strict";

  if (typeof wp === "undefined" || !wp.media) return;

  // ─── SINGLE IMAGE UPLOAD ────────────────────────────────
  $(document).on("click", ".bastovan-image-upload", function (e) {
    e.preventDefault();

    var btn     = $(this);
    var target  = btn.data("target");   // hidden input ID
    var preview = btn.data("preview");  // preview div ID

    var frame = wp.media({
      title:    "Izaberi sliku",
      button:   { text: "Koristi ovu sliku" },
      multiple: false,
      library:  { type: "image" },
    });

    frame.on("select", function () {
      var attachment = frame.state().get("selection").first().toJSON();

      $("#" + target).val(attachment.id);

      var previewDiv = $("#" + preview);
      previewDiv.html(
        '<img src="' + attachment.url + '" style="max-width:200px;border-radius:8px;display:block;margin-bottom:8px;">' +
        '<button type="button" class="button bastovan-image-remove" data-target="' + target + '" data-preview="' + preview + '">✕ Ukloni</button>'
      );
    });

    frame.open();
  });

  // ─── SINGLE IMAGE REMOVE ────────────────────────────────
  $(document).on("click", ".bastovan-image-remove", function (e) {
    e.preventDefault();

    var btn     = $(this);
    var target  = btn.data("target");
    var preview = btn.data("preview");

    $("#" + target).val("");
    $("#" + preview).html("");
  });

  // ─── GALERIJA (višestruki odabir) ───────────────────────
  var galerija_frame;

  $("#bastovan-galerija-btn").on("click", function (e) {
    e.preventDefault();

    if (galerija_frame) {
      galerija_frame.open();
      return;
    }

    galerija_frame = wp.media({
      title:    "Izaberi slike za galeriju",
      button:   { text: "Dodaj u galeriju" },
      multiple: true,
      library:  { type: "image" },
    });

    galerija_frame.on("select", function () {
      var selection = galerija_frame.state().get("selection");
      var ids       = [];
      var html      = "";

      selection.each(function (attachment) {
        attachment = attachment.toJSON();
        ids.push(attachment.id);
        html +=
          '<img src="' + (attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url) + '" ' +
          'style="width:80px;height:80px;object-fit:cover;border-radius:6px;">';
      });

      $("#bastovan_galerija").val(ids.join(","));
      $("#bastovan-galerija-preview").html(html);
    });

    galerija_frame.open();
  });

})(jQuery);
