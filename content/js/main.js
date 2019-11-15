$(document).ready(function(params) {
  alert(params);
  $("#passport").change(function(params) {
    var fileExtension = ["jpeg", "jpg", "png", "gif", "bmp"];

    if (
      $.inArray(
        $(this)
          .val()
          .split(".")
          .pop()
          .toLowerCase(),
        fileExtension
      ) == -1
    ) {
      this.files = null;
      $(this).val("");
      $("#passportErr").removeClass("d-none");
      $("#passportErr").html(
        "Only formats are allowed : " + fileExtension.join(", ") + "<br/>"
      );
    } else if (this.files[0].size > 2097152) {
      $("#passportErr").removeClass("d-none");
      $("#passportErr").html(
        $("#passportErr").text() +
          "" +
          "Try to upload file less than 2MB! (Your file size is" +
          this.files[0].size +
          "Byte)!"
      );
      this.files = null;
      $(this).val("");
    } else {
      $("#passportErr").addClass("d-none");
    }
  });

  var name = ["#resume", "#cover_letter"];
  name.forEach(element => {
    checkFile(element);
  });
  function checkFile(el) {
    $(el).change(function(params) {
      var Err = el + "Err";
      var fileExtension = ["pdf", "doc", "docx"];
      if (
        $.inArray(
          $(this)
            .val()
            .split(".")
            .pop()
            .toLowerCase(),
          fileExtension
        ) == -1
      ) {
        this.files = null;
        $(el).val("");

        $(Err).removeClass("d-none");
        $(Err).html(
          $(Err).text() +
            "<br/>" +
            "Only formats are allowed : " +
            fileExtension.join(", ")
        );
      } else if (this.files[0].size > 2097152) {
        $(Err).removeClass("d-none");
        $(Err).html(
          $(Err).text() +
            "" +
            "Try to upload file less than 2MB! (Your file size is" +
            this.files[0].size +
            "Byte)!"
        );
        this.files = null;
        $(this).val("");
      } else {
        $(Err).addClass("d-none");
      }
    });
  }

  var emailRegex = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+.[A-Z]{2, 4}$/;
  var nameRegex = /^[A-Z]+$/i; //new RegExp("/^[A-z]{3, 30}$/i");
  var mobileRegex = /[0-9-()+]{3, 20}/;

  function validityCheck(el, regExp, message) {
    console.log($(el).val());
    $(el).change(function(params) {
      //match email address
      //console.log(regExp.test($(this).val()));
      var err = el + "Err";
      if (regExp.test($(this).val())) {
        $(err).addClass("d-none");
      } else {
        $(err).removeClass("d-none");
        $(err).text(message);
        $(this).val("");
      }
    });
  }
  validityCheck("#first_name", nameRegex, "First Name is invalid");
  validityCheck("#last_name", nameRegex, "Last Name is invalid");
  validityCheck("#phone_no", mobileRegex, "Number is invalid");
  validityCheck("#country", nameRegex, "Country is invalid");
  validityCheck("#city", nameRegex, "City is invalid");
  validityCheck("#country", nameRegex, "Country is invalid");
  validityCheck("#state", nameRegex, "State is invalid");
});
