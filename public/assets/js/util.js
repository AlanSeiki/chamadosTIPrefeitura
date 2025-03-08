
const error = document.getElementById('errorToast')
const success = document.getElementById('successToast')
const warning = document.getElementById('warningToast')
const errorToastBootstrap = bootstrap.Toast.getOrCreateInstance(error)
const successToastBootstrap = bootstrap.Toast.getOrCreateInstance(success)
const warningToastBootstrap = bootstrap.Toast.getOrCreateInstance(warning)


$(document).ready(() => {
    $('#logout').click(() => {
        $.ajax({
            url: '/logout',
            type: "POST",
            success: () => {
                window.location.href = "/";
            }
          });
        
    })
})

function callToast(tipo, message) {
 switch (tipo) {
    case 'error':
        $("#messageError").text(message);
        errorToastBootstrap.show();
    break;
    case 'success':
        $("#messageSucesso").text(message);
        successToastBootstrap.show();
    break;
    case 'warning':
        $("#messageAlerta").text(message);
        warningToastBootstrap.show();
    break;
    default:
        break;
 }
}

function formatarDataBr(dataStr) {
    let data = new Date(dataStr);
    let dia = String(data.getDate()).padStart(2, '0');
    let mes = String(data.getMonth() + 1).padStart(2, '0');
    let ano = data.getFullYear();
    let horas = String(data.getHours()).padStart(2, '0');
    let minutos = String(data.getMinutes()).padStart(2, '0');
    let segundos = String(data.getSeconds()).padStart(2, '0');

    return `${dia}/${mes}/${ano} ${horas}:${minutos}:${segundos}`;
}

