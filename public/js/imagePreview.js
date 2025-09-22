function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      $("#preview-img").attr("src", e.target.result);
      $("#image-upload-input-label").text(input.files[0].name);
    };

    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}

$(document).ready(function() {
  $("#image-upload-input").change(function() {
    readURL(this);
  });

  $("#news-image-clear-input").click(() => {
    $("#preview-img").attr(
      "src",
      "https://user-images.githubusercontent.com/194400/49531010-48dad180-f8b1-11e8-8d89-1e61320e1d82.png"
    );
    $("#image-upload-input").val(null);
  });

  $("#profile-image-clear-input").click(() => {
    $("#preview-img").attr(
      "src",
      "https://fusionhealthstudio.com/images/fusion/profile-icon-9.png"
    );
    $("#image-upload-input").val(null);
  });
});
