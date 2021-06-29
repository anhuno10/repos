//js something else only for testing branch, 
//something specific for this iss50 branch
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
      else{
        window.open (output);
      }
    }
  });
}