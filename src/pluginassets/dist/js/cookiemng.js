var cm_main = document.querySelector(".__cookiemng__");
if(cm_main){
    var cm_blocked = document.querySelector(".cm__blocked");
    var cm_triggerGoogleConsentConsent = cm_main.getAttribute('data-google-consent');
    var cm_acc = document.getElementsByClassName("cm__acc-trigger");

    let cmEventSequence = 0;
    const nextConsentEventId = () => {
        cmEventSequence += 1;
        return 'cm-' + Date.now().toString(36) + '-' + cmEventSequence;
    };

    const ensureDataLayer = () => {
        if (typeof window.dataLayer === 'undefined') {
            window.dataLayer = [];
        }
        return window.dataLayer;
    };

    const collectConsentState = (options = {}) => {
        const includeAliases = options.includeAliases !== false;
        const granted = [];
        const denied = [];
        checks.forEach((check)=>{
            const value = check.getAttribute('value');
            if (!value) {
                return;
            }
            const alias = includeAliases ? check.getAttribute('data-cm-alias') : null;
            if(check.checked){
                granted.push(value);
                if (alias) {
                    granted.push(alias);
                }
            }else{
                denied.push(value);
                if (alias) {
                    denied.push(alias);
                }
            }
        });
        if (!granted.includes('functional')) {
            granted.unshift('functional');
        }
        return {
            granted: Array.from(new Set(granted)),
            denied: Array.from(new Set(denied))
        };
    };

    const updateConsentState = (source) => {
        const current = collectConsentState();
        const normalGranted = current.granted.slice().sort();
        const normalDenied = current.denied.slice().sort();
        const signature = normalGranted.join('|') + '::' + normalDenied.join('|');
        const previous = window.cmConsentState || {};
        const changed = previous.signature !== signature;
        window.cmConsentState = {
            granted: normalGranted,
            denied: normalDenied,
            signature: signature,
            source: source,
            lastUpdated: Date.now()
        };
        return {
            granted: normalGranted,
            denied: normalDenied,
            changed: changed
        };
    };

    const pushConsentEvent = (eventName, source, force) => {
        const state = updateConsentState(source);
        if (!force && !state.changed) {
            return state;
        }
        ensureDataLayer().push({
            event: eventName,
            consentGranted: state.granted,
            consentDenied: state.denied,
            eventSource: source,
            eventId: nextConsentEventId()
        });
        window.cmGetConsentState = function(){
            return window.cmConsentState || {granted: [], denied: []};
        };
        return state;
    };

    if(cm_acc && cm_acc.length > 0){
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
        const stateNoAlias = collectConsentState({includeAliases:false});
        const granted = stateNoAlias.granted.slice();
        const denied = stateNoAlias.denied.slice();
        const values = granted.join(',');

        if(cm_triggerGoogleConsentConsent && typeof cm_updateConsent === 'function'){
            cm_updateConsent(granted,denied);
        }
        pushConsentEvent('cm_consent_applied', 'user-action');
        fetch('/actions/cookiemng/permission/set',{
            method: 'POST',
            headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json'
            },
            body: JSON.stringify({permissions:values,siteHandle:cm_main.getAttribute('data-site-handle')})
        })
        .then(response => response.json())
        .then(data => {
            //console.log(data);
        })
        cm_onClose();
    }
    let cm_onClose = () => {
        cm_defaultView.classList.add('cm__active');
        if(cm_customizeView){
            cm_customizeView.classList.remove('cm__active');
        }
        cm_main.classList.remove("cm__active");
        if(cm_blocked){
            cm_blocked.classList.remove('cm__active');
        }
    }
    cm_buttons.forEach((button,index)=>{
        button.addEventListener('click', function(){
            let action = this.getAttribute('data-cm-action');
            switch(action){
                case  'onCustomize':
                    cm_defaultView.classList.remove('cm__active');
                    if(cm_customizeView){
                        cm_customizeView.classList.add('cm__active');
                    }
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
    window.cm_displaySettings = () => {
        cm_defaultView.classList.add('cm__active');
        if(cm_customizeView){
            cm_customizeView.classList.remove('cm__active');
        }
        cm_main.classList.add("cm__active");
        cm_main.classList.add("cm__dismissable");
        if(cm_blocked){
            cm_blocked.classList.add('cm__active');
        }
    }

    pushConsentEvent('cm_consent_ready', 'initial-load', true);
}