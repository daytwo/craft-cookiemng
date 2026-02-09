# Cookie Manager Async Loading

## Overview

The Cookie Manager plugin now supports asynchronous loading to prevent cookie panel state from being cached by CDNs. This ensures that each visitor receives the correct cookie consent state based on their individual cookies, even when pages are served from a CDN cache.

## How It Works

### Architecture

1. **Placeholder Rendering**: The main `render()` method now outputs a minimal placeholder `<div>` instead of the full panel HTML
2. **Client-Side Loading**: After the page loads, JavaScript fetches the panel HTML from a dedicated endpoint
3. **Cache Prevention**: The endpoint returns proper `Cache-Control` headers (`private, no-store`) to bypass CDN caching
4. **Dynamic Injection**: The fetched HTML is injected into the page and event listeners are attached

### Components

#### 1. PanelController (`src/controllers/PanelController.php`)
- **Endpoint**: `/actions/cookiemng/panel/get-panel`
- **Method**: `actionGetPanel()`
- **Purpose**: Returns cookie panel HTML and consent script as JSON
- **Headers**: Sets `Cache-Control: private, no-store, no-cache, must-revalidate` to prevent caching

#### 2. Placeholder Template (`src/templates/panel/placeholder.twig`)
- Renders minimal HTML with data attributes
- Contains endpoint URL and site configuration
- No visible content until populated by JavaScript

#### 3. Async Loader (`src/pluginassets/dist/js/cookiemng-async.js`)
- Loads on DOM ready
- Fetches panel from endpoint with cache-busting
- Injects HTML and initializes event listeners
- Handles Google Consent Mode integration

## Usage

### In Your Templates

The usage remains the same as before:

```twig
{# Basic usage #}
{{ craft.cookiemng.render()|raw }}

{# With site handle #}
{{ craft.cookiemng.render('default')|raw }}

{# Hide on specific pages #}
{{ craft.cookiemng.render('default', true)|raw }}
```

### What Changed

**Before** (synchronous):
- Panel HTML rendered server-side with current cookie state
- Full HTML included in cached page
- Cookie state could be cached by CDN

**After** (asynchronous):
- Only placeholder div rendered server-side
- Panel HTML fetched client-side after page load
- Each user gets fresh cookie state from non-cached endpoint

## CDN Configuration

### Endpoint Exclusions

Ensure your CDN/cache layer **does not cache** the following endpoint:

```
/actions/cookiemng/panel/get-panel
```

### Example Configurations

#### Cloudflare Page Rule
```
URL: yoursite.com/actions/cookiemng/*
Cache Level: Bypass
```

#### Nginx
```nginx
location /actions/cookiemng/ {
    proxy_no_cache 1;
    proxy_cache_bypass 1;
}
```

#### Varnish
```vcl
if (req.url ~ "^/actions/cookiemng/") {
    return (pass);
}
```

## Testing

### Verify Async Loading

1. Open your site in a browser
2. Open Developer Tools (Network tab)
3. Reload the page
4. Look for request to `/actions/cookiemng/panel/get-panel`
5. Check response headers - should include `Cache-Control: private, no-store`

### Verify Cookie State

1. Clear all cookies for your site
2. Reload page - should see cookie consent banner
3. Accept cookies
4. Reload page - banner should not appear (cookies remembered)
5. Open in incognito/private window - should see banner again

## Backward Compatibility

The plugin maintains backward compatibility:

- Existing Twig template calls work without modification
- JavaScript API (`window.cm_displaySettings()`) still available
- Cookie storage mechanism unchanged
- All settings and configurations preserved

## Performance Considerations

### Pros
- Cached pages load faster (no server-side cookie checking)
- CDN can cache the main page effectively
- Fresh cookie state for every user

### Cons
- Additional HTTP request after page load (minimal ~1-5kb)
- Slight delay before panel appears (typically <100ms on fast connections)

### Optimization Tips

1. **Preconnect to your domain**: Add to `<head>`:
   ```html
   <link rel="preconnect" href="https://yoursite.com">
   ```

2. **Inline critical CSS**: Consider inlining minimal cookie panel CSS for immediate visibility

3. **HTTP/2**: Ensure your server uses HTTP/2 for efficient parallel requests

## Troubleshooting

### Panel doesn't appear

1. Check browser console for JavaScript errors
2. Verify the endpoint returns valid JSON
3. Check CDN isn't caching the endpoint
4. Ensure JavaScript files are loaded

### Console shows "Panel container not found"

- Ensure `{{ craft.cookiemng.render()|raw }}` is in your template
- Check template is actually rendering the placeholder div

### Panel appears with wrong state

- Clear CDN cache
- Verify endpoint has correct `Cache-Control` headers
- Check cookie domain/path settings

### Double-loading issues

- Ensure you're not calling `render()` multiple times
- Check for duplicate JavaScript file inclusions

## Migration from Old Version

No migration needed! The changes are automatic:

1. Update plugin files
2. Clear Craft cache: `./craft clear-caches/all`
3. Clear CDN cache
4. Test on your site

## Advanced: Pure Client-Side Mode

If you don't need server-side consent script rendering, you can further optimize by handling everything client-side:

1. Read the consent cookie directly in JavaScript
2. Decide whether to show banner based on cookie presence
3. Skip the endpoint entirely

This approach is more complex but eliminates the HTTP request. Consider this only if you have specific performance requirements.

## Support

For issues or questions:
- GitHub: [daytwo/craft-cookiemng](https://github.com/daytwo/craft-cookiemng)
- Email: tech@daytwo.no
