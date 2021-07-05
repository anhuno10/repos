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
  console.log("show all stored images");
  $.ajax({
    url: 'server_side.php',
    data: { action: "showAllStoredImages" },
    type: 'post',
    success: function (output) {
      console.log (output)
      $('#result_table').append(output);
    }
  });
}

function deleteImage() {

}

function openImage() {

}