# AI Google Analytics

Connects Canvas AI agents to Google Analytics so they can monitor page
performance and suggest improvements. When GA4 metrics fall below your
benchmarks, the module's monitoring agent flags underperforming pages and
notifies the site administrator.

## How It Works

1. **Cron fetches GA4 data.** On each cron run the module queries engaged
   sessions, bounce rate, and key event rate for every Canvas page marked for
   monitoring. Results are stored directly on the page entity.
2. **Presave triggers the agent.** When stored metrics change, the Analytics
   Monitoring Agent compares the new values against benchmarks defined in the
   AI Context system. If any benchmark fails, the agent flags the page and
   sends an email to the site administrator.
3. **Review page links to Canvas.** The admin review table at
   `/admin/content/ga-page-review` shows flagged pages with a "Work on it"
   link that opens the Canvas editor with the agent's performance summary
   pre-loaded in the AI chat panel.
4. **Function call for on-demand queries.** AI agents can call the
   `ai_google_analytics_get_data` function tool to pull GA4 data for any set
   of page paths without waiting for cron.

## Requirements

- Drupal 10.3 or 11.x
- [AI](https://www.drupal.org/project/ai) module
- [AI Agents](https://www.drupal.org/project/ai_agents) module
- [Canvas](https://www.drupal.org/project/canvas) module
- [AI Context](https://www.drupal.org/project/ai_context) module (for
  benchmark context items used by the monitoring agent)
- Google Analytics Data API PHP client (`google/analytics-data`)
- A GA4 property with a service account that has Viewer access
- Drupal private file system configured (credentials are stored in
  `private://`)

## Installation

Install via Composer:

```bash
composer require drupal/ai_google_analytics
drush en ai_google_analytics
```

## Configuration

1. Go to **Admin > Configuration > Web services > Google Analytics Settings**
   (`/admin/config/services/ai-google-analytics`).
2. Enter your GA4 property ID (the numeric ID, not the measurement ID).
3. Upload the service account credentials JSON file. The file is stored in the
   private file system.

### Enabling monitoring on a page

Edit any Canvas page and check the **Monitor analytics for this page**
checkbox. The next cron run will begin fetching GA4 data for that page.

### Setting benchmarks

Benchmarks are defined as AI Context items and mapped to the Analytics
Monitoring Agent through the AI Context system. Create a context item with your
target thresholds (for example, minimum engaged sessions, maximum bounce rate)
and assign it to the `analytics_monitoring_agent`.

## Components

### `GoogleAnalytics` (AiFunctionCall plugin)

Plugin ID: `ai_google_analytics:get_data`
Function name: `ai_google_analytics_get_data`
Group: `information_tools`

Accepts a comma-separated list of page paths and returns GA4 metrics (engaged
sessions, bounce rate, key event rate) for each path over the last 90 days.

### Analytics Monitoring Agent

Shipped as config in `config/install/`. Compares GA metrics against benchmarks
from the AI Context system and returns a JSON response indicating whether any
benchmark failed, with a summary of the results.

### `GoogleAnalyticsHooks`

Adds four base fields to Canvas page entities:
- `monitor` (boolean) — opt-in toggle for GA monitoring
- `engaged_sessions` (string) — last fetched engaged sessions count
- `bounce_rate` (string) — last fetched bounce rate percentage
- `key_event_rate` (string) — last fetched key event rate percentage

Also implements `hook_canvas_page_presave` to trigger the monitoring agent when
metric values change.

### `GoogleAnalyticsReviewController`

Renders the admin review table at `/admin/content/ga-page-review`. Each flagged
page shows the agent's summary and a link to open it in the Canvas editor with
the AI panel pre-populated.

## Maintainers

- Alex Urevick-Ackelsberg ([AlexUA](https://www.drupal.org/u/alexua)) —
  [Zivtech](https://www.zivtech.com)
