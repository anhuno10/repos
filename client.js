function openBook(book, page) {
  var info = {};
  info["book"] = book;
  info["page"] = page;
  $.ajax({
    url: 'server_side.php',
    data: { action: "openStandard", info: info },
    type: 'post',
    success: function (output) {
      if (output == "No book") {
        alert("Book selected not added yet");
      }
      //let's do something around here
      else {
        window.open(output);
      }
    }
  });
}

function showAllStoredImages() {
  $.ajax({
    url: 'server_side.php',
    data: { action: "showAllStoredImages" },
    type: 'post',
    success: function (output) {
      //if the div is empty, load it for the first time
      if (!$.trim($('#result_table').html()).length) {
        $('#result_table').append(output);
      }
      //if it's not empty, refresh it
      else {
        $('#result_table').html('');
        $('#result_table').append(output);
      }
    }
  });
}

function deleteImage(id, image_path) {
  var info = {};
  info["id"] = id;
  info["image_path"] = image_path;
  $.ajax({
    url: 'server_side.php',
    data: { action: "deleteStoredImage", info: info },
    type: 'post',
    success: function (output) {
      alert("Image has been deleted");
      showAllStoredImages();
    }
  });
}

function openImage(imagePath) {
  window.open(imagePath);
}