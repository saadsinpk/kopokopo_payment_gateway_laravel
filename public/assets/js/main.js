$(document).ready(function(e) {
    const form = document.getElementById('payment-form');
    const fv = FormValidation.formValidation(form, {
        fields: {
            name: {
                validators: {
                    notEmpty: {
                        message: 'The name is required',
                    },
                },
            },
        },
        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            // submitButton: new FormValidation.plugins.SubmitButton(),
            bootstrap5: new FormValidation.plugins.Bootstrap5(),
            icon: new FormValidation.plugins.Icon({
                valid: 'fa fa-check',
                invalid: 'fa fa-times',
                validating: 'fa fa-refresh',
            }),
            internationalTelephoneInput: new FormValidation.plugins.InternationalTelephoneInput({
                field: 'number',
                separateDialCode: true,
                initialCountry: "us",
                preferredCountries: ["us", "br", "pt"],
                customPlaceholder: function(selectedCountryPlaceholder) {
                    return selectedCountryPlaceholder;
                },
                message: 'The number is not valid',
                utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.3/js/utils.js',
            }),
        },
    });
});
  
$(document).ready(function() {
    var valas = $(".iti__selected-dial-code");
    var valass = $(".iti__selected-dial-code").html();
    var number = $("#phone_number").val();
    var phonenumber = valass+ +number;
    $('.number').val(phonenumber);
    // alert(phonenumber);
    console.log(valass);
    if ($(valas).parent().parent().parent().siblings(".code").val() === "") {
        $(valas).parent().parent().parent().siblings(".code").val(valass);

    }
})
$(document).on("click",".iti__flag-container",function(){
   var valass = $('.iti__selected-dial-code').html(); 
   var number = $("#phone_number").val();
    var phonenumber = valass+ +number;
    $('.number').val(phonenumber);
});
$(".abc").click(function() {
    const wrapper = document.createElement('div');
    wrapper.innerHTML = "<ul class='error-list'><% @user.errors.full_messages.each do |message| %><li><%= message %></li><% end %></ul>";
    swal({
      title: "Error!",
      content: wrapper, 
      icon: "error"
    });
})
