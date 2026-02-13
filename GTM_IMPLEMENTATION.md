# GTM Implementation Guide (CookieMng Consent Toolkit)

This document explains how to implement Google Tag Manager using the provided template file:

- `gtm-templates/cookiemng-consent-toolkit.json`

It is based on the exact objects defined in that export (variables, triggers, and example tags).

## 1) What the template contains

### Variables

The template creates the following GTM variables:

- `CM - Consent Granted (DLV)` → Data Layer variable `consentGranted`
- `CM - Consent Denied (DLV)` → Data Layer variable `consentDenied`
- `CM - Consent Event ID (DLV)` → Data Layer variable `eventId`
- `CM - Consent Source (DLV)` → Data Layer variable `eventSource`
- `CM - Granted CSV` → Custom JS helper to convert granted categories to a comma-separated string
- `CM - Denied CSV` → Custom JS helper to convert denied categories to a comma-separated string

The helper variables include fallback reads from:

- `window.cmGetConsentState()`
- `window.cmConsentState`

This makes trigger conditions more reliable even if Data Layer timing varies.

### Triggers

The template defines Custom Event triggers for these consent categories:

- `analytics`
- `advertising`
- `personalization`
- `custom_consent`

Event coverage in the template:

- **Ready** (`cm_consent_ready`) for granted consent
- **Applied** (`cm_consent_applied`) for granted consent changes
- **Revoked** (`cm_consent_applied`) for denied consent changes

Trigger names included:

- `CM - Analytics Consent Ready`
- `CM - Analytics Consent Applied`
- `CM - Analytics Consent Revoked`
- `CM - Advertising Consent Ready`
- `CM - Advertising Consent Applied`
- `CM - Advertising Consent Revoked`
- `CM - Personalization Consent Ready`
- `CM - Personalization Consent Applied`
- `CM - Personalization Consent Revoked`
- `CM - Custom Consent Ready`
- `CM - Custom Consent Applied`
- `CM - Custom Consent Revoked`

### Example tags (paused)

The import also includes three paused HTML example tags:

- `Example - Analytics Tag (Consent Ready)`
- `Example - Analytics Tag (Consent Applied)`
- `Example - Analytics Cleanup (Consent Revoked)`

You must replace placeholder HTML in these tags with your real implementation.

## 2) Import the template into GTM

1. Open GTM and select your container.
2. Go to **Admin → Import Container**.
3. Choose `gtm-templates/cookiemng-consent-toolkit.json`.
4. Import into a new workspace or merge into an existing one.
5. Review conflicts and complete import.

## 3) Required post-import checks

After import, validate these settings before publish:

1. Open all imported variables and confirm they are enabled and saved.
2. Open all imported triggers and confirm event names and regex conditions are intact.
3. Open each example tag and set:
   - **Advanced Settings → Tag firing options → Once per page**
4. Replace each placeholder HTML block with production code.
5. Keep revocation tags focused on cleanup only (cookie deletion, opt-out API call, etc.).

## 4) How consent matching works

Triggers use regex against CSV helper variables:

- `(^|,)analytics($|,)`
- `(^|,)advertising($|,)`
- `(^|,)personalization($|,)`
- `(^|,)custom_consent($|,)`

This avoids false positives when multiple categories are present.

## 5) Suggested implementation pattern

Use this pattern per category:

1. **Ready trigger** for users who already had consent on page load.
2. **Applied trigger** for users who grant consent during the current session.
3. **Revoked trigger** for cleanup when consent is removed.

For analytics specifically:

- Fire your analytics bootstrap/config tag on both:
  - `CM - Analytics Consent Ready`
  - `CM - Analytics Consent Applied`
- Fire cleanup logic on:
  - `CM - Analytics Consent Revoked`

## 6) Mapping notes

The template assumes these Data Layer payload keys from CookieMng events:

- `consentGranted` (array)
- `consentDenied` (array)
- `eventId` (string)
- `eventSource` (`initial-load` or `user-action`)

Custom/extra consent should be matched in GTM using the canonical slug:

- `custom_consent`

## 7) Test checklist (GTM Preview)

1. Open GTM Preview mode and load your site.
2. Confirm `cm_consent_ready` appears.
3. Verify granted/denied arrays populate the DLV variables.
4. Accept analytics in the banner and confirm `CM - Analytics Consent Applied` can fire.
5. Revoke analytics and confirm `CM - Analytics Consent Revoked` can fire.
6. Verify tags execute once per page when expected.

## 8) Troubleshooting

### Triggers never fire

- Confirm the site emits `cm_consent_ready` / `cm_consent_applied`.
- Confirm variable names exactly match template names.
- Confirm regex is unchanged.

### Wrong category behavior

- Verify you are matching the expected slug (`analytics`, `advertising`, `personalization`, `custom_consent`).
- Inspect `CM - Granted CSV` and `CM - Denied CSV` values in Preview mode.

### Duplicate firing

- Set tag firing option to **Once per page**.
- Avoid attaching duplicate equivalent triggers to the same tag unless required.

## 9) Minimal go-live checklist

- Import template
- Replace paused example tag HTML with production tags
- Set firing option to Once per page
- Validate ready/applied/revoked flow in GTM Preview
- Publish workspace
