# Release Notes for daytwo/craft-cookiemng

## 1.1.0
- Add async loading for the cookie panel to avoid CDN-cached consent state
- Add `/actions/cookiemng/panel/get-panel` endpoint for dynamic panel + consent script rendering
- Emit GTM-friendly dataLayer events (`cm_consent_ready`, `cm_consent_applied`) with stable state tracking
- Add GTM integration docs and importable toolkit template
- Improve asset publishing with version-based hashing and include async loader JS

## 1.0.0
- Initial release
