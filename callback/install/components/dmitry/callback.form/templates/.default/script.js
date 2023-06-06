document.addEventListener("DOMContentLoaded", () => {
    /** phone mask **/
    let phoneInput = document.querySelector('#callback-form-wrapper input[name="phone"]');
    if(phoneInput){
        let phoneInputVal = new BX.MaskedInput({
            mask: '+7 999 999 99 99',
            input: phoneInput,
            placeholder: '_'
        });
        phoneInputVal.setValue(' ');
    }
});