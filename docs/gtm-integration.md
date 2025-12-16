# Google Tag Manager Integration

This guide explains how to connect the Cookie Manager plugin with Google Tag Manager (GTM) so that marketing/analytics tags only fire after the correct consent level has been granted. The integration is fully GDPR-compliant and works with the plugin's asynchronous loading workflow.

## 1. Data Layer Events Exposed by the Plugin

The plugin pushes structured events into `window.dataLayer` whenever consent data is available or changes. Each event includes a unique `eventId` so GTM can be configured to fire once per page.

| Event name | When it fires | Payload fields |
|------------|---------------|----------------|
| `cm_consent_ready` | Immediately after the consent panel is injected and the current cookie state is read | `consentGranted` (array), `consentDenied` (array), `eventSource="initial-load"`, `eventId` |
| `cm_consent_applied` | Every time a visitor saves preferences from the panel (Accept All, Save, etc.). Only emitted when the consent state actually changes. | `consentGranted` (array), `consentDenied` (array), `eventSource="user-action"`, `eventId` |
| `consent_update` | (Legacy) still emitted by the consent script for backwards compatibility. Mirrors Google Consent Mode calls. | `consent` object used by gtag | 

Additionally, the plugin exposes helper getters:

```javascript
window.cmConsentState; // { granted: [], denied: [], signature, source, lastUpdated }
window.cmGetConsentState(); // returns the same object; safe to call before events fire
```

These make it easy to build custom variables inside GTM. If you enable the plugin's optional "Extra" consent category, the plugin always exposes it to GTM under the canonical slug `custom_consent`, even if you rename the category in the control panel. That means you can wire GTM once and editors can rename the UI without breaking the triggers.

## 2. Preparing Google Tag Manager

1. **Ensure the plugin assets load** on every page (the async loader handles this automatically).
2. **Publish the GTM container** you are working with.
3. **Open GTM Preview Mode** so you can watch data layer events in real time while configuring triggers.

### Optional: Import the prebuilt workspace

The repository ships with an importable workspace at `gtm-templates/cookiemng-consent-toolkit.json`. It contains:

- Data Layer variables for `consentGranted`, `consentDenied`, `eventId`, and `eventSource`
- Helper Custom JS variables that flatten granted/denied categories into comma-separated strings
- Consent triggers for analytics/advertising/personalization and `custom_consent` (ready, applied, revoked)
- Paused example tags covering GA4 pageviews (prior + opt-in), LinkedIn Insight, GA4 form events, Google Ads conversions, and consent revocation cleanup

To import it:

1. In GTM, go to **Admin → Container → Import Container**.
2. Choose the JSON file and select either *Existing workspace* (Merge, overwrite conflicting) or create a new workspace.
3. Review the summary so you understand which variables/triggers/tags will be created.
4. After import, open each example tag and set **Advanced Settings → Tag firing options → Once per page** (the API no longer accepts this flag in JSON exports).
5. Replace the placeholder HTML in the example tags with your production analytics/marketing tags and publish.

## 3. Create Helpful Data Layer Variables

Inside GTM (Variables → New → Data Layer Variable):

| Variable name | Data Layer variable name | Notes |
|---------------|--------------------------|-------|
| `CM - Consent Granted` | `consentGranted` | Returns the array of categories granted for the most recent consent event. |
| `CM - Consent Denied`  | `consentDenied`  | Returns the array of categories denied. |
| `CM - Consent Event Id` | `eventId` | Useful for debugging/logging. |
| `CM - Consent Source` | `eventSource` | Lets you distinguish between initial load and user updates. |

Set **Version** to *Version 2* for each variable.

Because GTM compares strings in trigger conditions, create a helper Custom JavaScript variable that converts the granted array into a readable string:

1. Variables → New → *Custom JavaScript*
2. Name: `CM - Granted String`
3. Code:
   ```javascript
   function() {
     var state = window.cmGetConsentState ? window.cmGetConsentState() : window.cmConsentState;
     var granted = state && state.granted ? state.granted.slice() : [];
     return granted.join(',');
   }
   ```

Repeat for denied categories if needed (`CM - Denied String`).

## 4. Build Consent-Based Triggers

### Example: Fire Analytics Tags Only With Analytics Consent

1. Triggers → New → *Custom Event*
2. Event name: `cm_consent_ready`
3. This trigger fires on: *Some Custom Events*
4. Condition: `{{CM - Granted String}}` **matches Regex** `(^|,)analytics($|,)`
5. Advanced Settings → Tag firing options → **Once per page**

Duplicate the trigger and change the Event name to `cm_consent_applied`. Use the same condition and firing options. Attach both triggers to your analytics tag so it fires once on initial load (if previously accepted) and once when a visitor opts in while browsing.

### Example: Block Tags When Consent Is Missing

Create a blocking trigger to prevent premature firing:

1. Triggers → New → *Custom Event*
2. Event name: `cm_consent_ready`
3. Some Custom Events with condition `{{CM - Granted String}}` **does not match Regex** `(^|,)analytics($|,)`
4. Advanced Settings → Tag firing options → **Once per page**
5. Add the same blocking trigger to `cm_consent_applied` if you want to prevent firing while the visitor is actively removing consent.

Apply this trigger as an *Exception* to any tag that requires analytics consent.

### Example: Marketing Pixel Requiring Advertising Consent

Use the same pattern with `advertising` instead of `analytics` in the regex condition. The plugin automatically tracks extra/custom categories using the slug defined in the settings, so you can repeat the process for those values as well.

### Example: GA4 Pageview Tag (Analytics Consent)

Most sites need their GA4 configuration to fire both when returning visitors already have consent and when new visitors opt in mid-session. You can handle both cases with a single GA4 Configuration tag:

1. Import the "Example - GA4 Pageview (Consent Ready + Opt-In)" tag (or recreate it).
2. Attach **both** `CM - Analytics Consent Ready` and `CM - Analytics Consent Applied` as firing triggers so GTM treats them as an OR condition.
3. Keep **Advanced Settings → Tag firing options → Once per page** enabled so the tag only executes once per page view even if both events occur.

This ensures GA4 boots immediately for returning visitors and also replays the config call the first time someone opts in without creating duplicate hits.

### Example: LinkedIn Insight Tag (Advertising Consent)

The LinkedIn Insight script is considered advertising/remarketing, so attach it to the `CM - Advertising Consent Ready` and `CM - Advertising Consent Applied` triggers. The toolkit's "Example - LinkedIn Insight (Advertising)" tag already wires the official snippet and pauses it by default. Replace `123456` with your LinkedIn partner ID and publish.

### Example: GA4 Form Sent Event

When a form submission pushes `form_sent` into `dataLayer`, you can forward it to GA4 as long as analytics consent is present:

```javascript
dataLayer.push({
  event: 'form_sent',
  form_name: 'contact_request'
});
```

Import or recreate the trigger `CM - Analytics Form Sent` (custom event = `form_sent` + analytics regex) and attach it to a GA4 Event tag that uses `gtag('event','form_sent', {...})`. This guarantees your conversion only fires after the visitor opts into analytics.

### Example: Google Ads Conversion (Advertising Consent)

If you raise Google Ads conversions via GTM, publish a dedicated data layer event whenever the conversion happens:

```javascript
dataLayer.push({
  event: 'cm_ads_conversion',
  conversionValue: 1,
  conversionCurrency: 'USD'
});
```

Map that event to the trigger `CM - Advertising Conversion Event` so the Google Ads conversion tag runs only when the visitor granted advertising consent. The example tag in the toolkit shows the `gtag('event','conversion', { send_to: 'AW-XXXX/label', ... })` pattern Google documents.

### Example: Optional Custom Consent

If you enable the plugin's extra consent toggle, GTM will always see it as `custom_consent`. Create triggers just like the ones above but match on `custom_consent`. Editors can rename the category for visitors without breaking your GTM wiring.

## 5. Handling Revocations Without Duplicate Firings

- Every consent event carries a unique `eventId`. When you set a tag’s **Advanced Settings → Tag firing options → Once per page**, GTM ensures the tag will run just once per page view, even if a visitor toggles consent repeatedly.
- If you need to run *cleanup* logic when consent is removed (e.g., wipe cookies), create a tag triggered on `cm_consent_applied` where the condition checks `{{CM - Denied String}}` matches the relevant category. Also set that tag to fire once per page to avoid duplicate cleanups.

## 6. Optional: Listen for Legacy `consent_update` Events

Some existing implementations may already listen for `consent_update`. The plugin continues to emit this event, but it fires every time `cm_updateConsent` runs, regardless of whether the selection changed. For new setups, prefer the scoped `cm_consent_ready` and `cm_consent_applied` events described above.

## 7. Reusable Custom Template (Variable)

A reusable variable template allows your entire team to ask “is consent granted for X?” without re-writing JavaScript.

### Template Code

1. In GTM, go to **Templates → New → Variable Template**
2. Click the **<>** icon to open the code editor
3. Paste the following sandboxed JavaScript:
   ```javascript
   const category = data.consentCategory || 'analytics';
   const fallbackOnReady = data.fireOnReady === true;
   const consentState = copyFromWindow('cmGetConsentState') ?
     copyFromWindow('cmGetConsentState')() :
     copyFromWindow('cmConsentState');

   const granted = consentState && consentState.granted ? consentState.granted : [];
   const denied = consentState && consentState.denied ? consentState.denied : [];

   const hasConsent = granted.indexOf(category) > -1;
   const stillUndefined = granted.length === 0 && denied.length === 0;

   if (!hasConsent && stillUndefined && fallbackOnReady) {
     const readyState = copyFromWindow('cmConsentState');
     return readyState && readyState.granted ? readyState.granted.indexOf(category) > -1 : false;
   }

   return hasConsent;
   ```
4. Add two input fields under **Fields**:
   - *Select* `consentCategory` with options `functional`, `analytics`, `advertising`, `personalization`, plus any custom slug you use
   - *Checkbox* `fireOnReady` (label “Fallback to cmConsentState when data layer has not fired yet”)
5. Save the template (suggested name: **CookieMng Consent Gate**)
6. Create a new variable using the template. Choose the category (e.g., Analytics) and enable fallback if you want to use the variable before `cm_consent_ready` fires.

You can now reference this variable directly inside trigger conditions (e.g., `{{CookieMng Consent Gate - Analytics}} equals true`).

## 8. Debugging Checklist

- Open GTM Preview mode and reload your site.
- Look for `cm_consent_ready` in the event stream. Confirm `consentGranted` and `consentDenied` arrays contain what you expect.
- Interact with the consent banner. Confirm you see `cm_consent_applied` only when the state actually changes.
- Check that tags fire exactly once per page load (Advanced Settings).
- When testing revocation, confirm cleanup tags run only once and that cookies are removed.

## 9. FAQ

- **Do I have to use both `cm_consent_ready` and `cm_consent_applied`?** Yes, if you want tags to fire for returning visitors and for people who opt-in during the session.
- **What happens if the panel never loads (e.g., blocking JS)?** `window.cmConsentState` remains `undefined`, so the reusable template or helper variables will return `false` and tags stay suppressed.
- **Can I map the events to GTM Consent Mode directly?** Yes. Use a Custom HTML tag listening for `cm_consent_applied` to call `gtag('consent','update', {...})` with your own mappings if needed. The plugin already does this when Google Consent Mode is enabled, but GTM mappings can provide a second layer of control.

With these steps, your GTM setup only fires tags when the visitor has explicitly granted the required consent level, preventing accidental tracking and keeping you compliant with GDPR and related regulations.
