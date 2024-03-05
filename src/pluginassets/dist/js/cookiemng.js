var cm_main = document.querySelector(".__cookiemng__");
var cm_triggerGoogleConsentConsent = cm_main.getAttribute('data-google-consent');
var cm_acc = document.getElementsByClassName("cm__acc-trigger");

for (let i = 0; i < cm_acc.length; i++) {
  cm_acc[i].addEventListener("click", function() {

    let acc = this.closest(".cm__accordion");
    
    if(!acc.classList.contains("cm__active")){
        let accCurrent = document.querySelector(".cm__accordion.cm__active");
        if(accCurrent){
            accCurrent.classList.remove("cm__active");
            let panelCurrent = accCurrent.querySelector('.cm__acc-panel');
            panelCurrent.style.maxHeight = "0px";
        }
    }
    acc.classList.toggle("cm__active");
    let panel = acc.querySelector('.cm__acc-panel');
    if(acc.classList.contains("cm__active")){
        panel.style.maxHeight = panel.scrollHeight + "px";
    }else{
        panel.style.maxHeight = "0px";
    }
  });
}
let checks = document.querySelectorAll('.cm__checkbox input[type="checkbox"]');
let cm_buttons = document.querySelectorAll('.cm__button[data-cm-action]');
let cm_defaultView = document.querySelector(".cm__container[data-cm-view='default']");
let cm_customizeView = document.querySelector(".cm__container[data-cm-view='customize']");

let cm_onAll = () => {
    checks.forEach((check,index)=>{
        check.checked = true;
    });
    cm_onSave();
}
let cm_onFunctional = () => {
    checks.forEach((check,index)=>{
        if(check.getAttribute('value') === 'functional'){
            check.checked = true;
        }else{
            check.checked = false;
        }
    });
    cm_onSave();
}
let cm_onSave = () => {
    let values = 'functional';
    let granted = [];
    let denied = [];
    checks.forEach((check,index)=>{
        if(check.checked){
            values += ','+check.value;
            granted.push(check.getAttribute('value'));
        }else{
            denied.push(check.getAttribute('value'));
        }
    });
    if(cm_triggerGoogleConsentConsent){
        cm_updateConsent(granted,denied);
    }
    fetch('/actions/cookiemng/permission/set',{
        method: 'POST',
        headers: {
          Accept: 'application/json',
          'Content-Type': 'application/json'
        },
        body: JSON.stringify({permissions:values})
    })
    .then(response => response.json())
    .then(data => {
        //console.log(data);
    })
    cm_onClose();
}
let cm_onClose = () => {
    cm_defaultView.classList.add('cm__active');
    cm_customizeView.classList.remove('cm__active');
    cm_main.classList.remove("cm__active");
}
cm_buttons.forEach((button,index)=>{
    button.addEventListener('click', function(){
        let action = this.getAttribute('data-cm-action');
        switch(action){
            case  'onCustomize':
                cm_defaultView.classList.remove('cm__active');
                cm_customizeView.classList.add('cm__active');
                break;
            case 'onFunctional':
                cm_onFunctional()
                break;
            case 'onAll':
                cm_onAll();
                break;
            case 'onSave':
                cm_onSave();
                break;
            case 'onClose':
                cm_onClose();
                break;
        }
    })
})
cm_displaySettings = () => {
    cm_defaultView.classList.add('cm__active');
    cm_customizeView.classList.remove('cm__active');
    cm_main.classList.add("cm__active");
    cm_main.classList.add("cm__dismissable");
}