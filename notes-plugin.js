jQuery(document).ready(function ($) {
  $("#notes_plugin_save_button").click(function () {
    var note = $("#notes_plugin_new_note").val();
    var postID = $("#post_ID").val();

    $.ajax({
      url: notesPluginAjax.ajax_url,
      type: "post",
      data: {
        action: "notes_plugin_save_note",
        note: note,
        post_id: postID,
        security: notesPluginAjax.security
      },
      success: function (response) {
        if (response.success) {
          $("#notes_plugin_notes").append(
            '<div class="note"><strong>' +
              response.data.note.username +
              ":</strong> " +
              response.data.note.note +
              " <em>" +
              response.data.note.date +
              "</em></div>"
          );
          $("#notes_plugin_new_note").val("");
        }
      }
    });
  });
});
