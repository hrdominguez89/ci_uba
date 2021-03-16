const submitForm = async (e)=>{
    e.preventDefault();
    grecaptcha.ready( async function() {
        let siteKey = $('#g-recaptcha').data('siteKey');
        const promiseCaptcha = await grecaptcha.execute(siteKey, {action: 'login'});
        const data = new FormData();
        data.append('recaptcha_response',promiseCaptcha);
        const init = {
            method: 'POST',
            body: data
        };
        const consulta = await fetch("valid_captcha", init);
        const consulta_json = await consulta.json();
        if(consulta_json.success){
            document.getElementById('loginForm').submit();
        }else{
            const divCaptchaError = document.getElementById('captchaError');
            divCaptchaError.innerHTML = '';
            const parrafoError = document.createElement('p');
            parrafoError.innerText='Error al validar el captcha, actualice la página e intente nuevamente';
            divCaptchaError.append(parrafoError);
            divCaptchaError.setAttribute('class','mt-3 alert alert-danger');
        }
    });
}