
$(document).ready(function(){
    $('#togglePassword').on('click', function () {
        if ($('#senha').attr('type') == "password") {
            $('#eye_open').addClass('hide');
            $('#eye_close').removeClass('hide');
            $('#senha').attr('type', 'text');
        } else {
            $('#eye_open').removeClass('hide');
            $('#eye_close').addClass('hide');
            $('#senha').attr('type', 'password');
        }
    });
    $("#form-login").on("submit", async function ($event) {
        $event.preventDefault();
    
        this.classList.add("was-validated");
        
        if (!this.checkValidity()) {
          return false;
        }
    
        $.ajax({
          url: "/login",
          type: "POST",
          data: $(this).serialize(),
          success: () => {
            window.location.href='home'
          },
          error: function (xhr) {
            var response = JSON.parse(xhr.responseText);
            callToast("error", response.mensagem);
          },
        });
      });
  });
