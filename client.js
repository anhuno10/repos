function openBook(book, page) {
  var info = {};
  info["book"] = book;
  info["page"] = page;
  $.ajax({
    url: 'server_side.php',
    data: { action: "openStandard", info: info },
    type: 'post',
    success: function (output) {
      if(output == "No book"){
        alert ("Book selected not added yet");
      }
      //let's do something around here
      else{
        window.open (output);
      }
    }
  });
}