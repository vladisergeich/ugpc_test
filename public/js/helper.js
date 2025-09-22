const submitForm = id => {
  const form = $(id)[0];
  if (form.checkValidity()) {
    form.submit();
  } else {
    form.reportValidity();
  }
};

$(".rotate").click(function() {
  $(this).toggleClass("down");
});
