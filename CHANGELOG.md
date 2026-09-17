# Release Notes for daytwo/craft-cookiemng

## Unreleased

### Breaking
- Remove the `personalization` consent category. The advertising ("Marketing") category now grants the Google Consent Mode signals `ad_storage`, `ad_user_data`, `ad_personalization` and `personalization_storage`. Re-point any GTM tags that used the personalization triggers to the advertising triggers; a stale `personalization` value left in an existing consent cookie is ignored and cleared on the next save. Visitors who previously granted only personalization are treated as denied for `ad_personalization` and `personalization_storage` until they re-consent to advertising.

## 1.1.0
- Add async loading for the cookie panel to avoid CDN-cached consent state
- Add `/actions/cookiemng/panel/get-panel` endpoint for dynamic panel + consent script rendering
- Emit GTM-friendly dataLayer events (`cm_consent_ready`, `cm_consent_applied`) with stable state tracking
- Add GTM integration docs and importable toolkit template
- Improve asset publishing with version-based hashing and include async loader JS

## 1.0.0
- Initial release
