/**
 * Cookie Manager Async Loader
 * Loads cookie panel asynchronously to avoid CDN caching issues
 */
(function() {
    'use strict';
    let cmEventSequence = 0;

    const nextConsentEventId = () => {
        cmEventSequence += 1;
        return 'cm-' + Date.now().toString(36) + '-' + cmEventSequence;
    };
    
    /**
     * Load the cookie panel from the server
     */
    function loadCookiePanel() {
        const container = document.getElementById('cookiemng-panel-container');
        const consentContainer = document.getElementById('cookiemng-consent-container');
        
        if (!container) {
            console.warn('Cookie Manager: Panel container not found');
            return;
        }
        
        const endpoint = container.getAttribute('data-endpoint');
        const siteHandle = container.getAttribute('data-site-handle') || 'default';
        const deactivated = container.getAttribute('data-deactivated') === '1';
        
        // Prepare request params
        const params = new URLSearchParams({
            siteHandle: siteHandle,
            deactivated: deactivated ? '1' : '0'
        });
        
        // Fetch panel from server with cache-busting
        fetch(endpoint + '?' + params.toString(), {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            cache: 'no-store' // Ensure browser doesn't cache this request
        })
        .then(function(response) {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.json();
        })
        .then(function(data) {
            if (!data.success) {
                console.warn('Cookie Manager: Server returned error');
                return;
            }
            
            if (!data.enabled) {
                // Cookie manager is disabled for this site
                return;
            }
            
            // Inject consent script first (Google Consent Mode)
            if (data.consentScript && consentContainer) {
                consentContainer.innerHTML = data.consentScript;
                
                // Execute any script tags in the consent script
                const scripts = consentContainer.getElementsByTagName('script');
                for (let i = 0; i < scripts.length; i++) {
                    const script = document.createElement('script');
                    script.textContent = scripts[i].textContent;
                    document.head.appendChild(script);
                }
            }
            
            // Inject panel HTML
            if (data.html) {
                container.innerHTML = data.html;
                container.style.display = '';
                
                // Initialize the panel functionality
                initializeCookiePanel();
            }
        })
        .catch(function(error) {
            console.error('Cookie Manager: Failed to load panel', error);
        });
    }
    
    /**
     * Initialize cookie panel after injection
     * This re-implements the logic from cookiemng.js
     */
    function initializeCookiePanel() {
        var cm_main = document.querySelector(".__cookiemng__");
        if(!cm_main){
            return;
        }
        
        var cm_blocked = document.querySelector(".cm__blocked");
        var cm_triggerGoogleConsentConsent = cm_main.getAttribute('data-google-consent');
        var cm_acc = document.getElementsByClassName("cm__acc-trigger");

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

        const ensureDataLayer = () => {
            if (typeof window.dataLayer === 'undefined') {
                window.dataLayer = [];
            }
            return window.dataLayer;
        };

        const collectConsentState = () => {
            const granted = [];
            const denied = [];
            checks.forEach((check)=>{
                const value = check.getAttribute('value');
                if (!value) {
                    return;
                }
                if(check.checked){
                    granted.push(value);
                }else{
                    denied.push(value);
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
            const dl = ensureDataLayer();
            dl.push({
                event: eventName,
                consentGranted: state.granted,
                consentDenied: state.denied,
                eventSource: source,
                eventId: nextConsentEventId()
            });
            return state;
        };

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
                // Cookie saved
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
                    case 'onCustomize':
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
        window.cmGetConsentState = function(){
            return window.cmConsentState || {granted: [], denied: []};
        };
    }
    
    /**
     * Initialize on DOM ready
     */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', loadCookiePanel);
    } else {
        loadCookiePanel();
    }
})();
