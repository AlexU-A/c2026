# drupal.org Module Descriptions

Ready-to-paste project page descriptions for each module. Written in Zivtech style for the Drupal AI initiative audience.

---

## 1. AI Agents Canvas Direct Edit

**Machine name:** `ai_agents_canvas_direct_edit`
**Package:** AI Tools
**Dependencies:** ai_agents, tool, canvas, canvas_ai

### Short description (for d.o. project page header)

Deterministic Canvas component property editing without LLM. Resolves simple prop edits from SDC schemas in <7ms at 0 tokens.

### Full description (paste below this line)

When users make simple changes to Canvas components ("set the color to blue,"
"change the heading to Welcome"), the correct result is deterministic. The prop
name and value can be resolved directly from the SDC component schema without
any AI reasoning.

This module does exactly that. It reads your theme's component YAML schemas,
builds prop alias and enum value maps, and resolves edits through seven match
tiers:

- **Exact and alias matches:** "set text_color to primary" or "set the color
  to blue"
- **Bare value inference:** Just "blue" resolves to the correct prop when
  unambiguous
- **Relative adjustments:** "bigger" or "smaller" navigates enum ordinals
- **Boolean toggles:** "show the header" or "hide the footer"
- **Reset patterns:** "reset the color" returns the prop to its default
- **Compound edits:** "change the heading to Welcome and set the color to blue"

Anything the matcher can't resolve with certainty gets a 422 response, routing
the request to the existing AI agent chain. Zero false positives by design.

**Key features:**

- 8 Tool API plugins with automatic MCP, CLI, and AI agent discovery
- HTTP bridge controller at the same endpoint the Canvas frontend already calls
- Optional MCP server submodule (JSON-RPC 2.0) for external clients
- Schema-driven: adapts automatically when theme components change
- Config-driven verb and alias lists for customization without patching
- Opt-in telemetry with PII-safe defaults (SHA-256 hashing, no raw text storage)
- Works without AI providers configured (Canvas Lite mode: deterministic edits
  succeed, unmatched edits return 503 instead of routing to AI)

**Measured results** (15-component demo page):

- Deterministic path: 0 tokens, <7ms
- AI baseline: ~101K tokens, 16.4s
- Hit rate: 60% on 20 mixed edits, zero false positives

59 kernel tests, 221 assertions. PHPCS clean (Drupal + DrupalPractice).

Requires: [AI Agents](https://www.drupal.org/project/ai_agents),
[Tool](https://www.drupal.org/project/tool),
[Canvas](https://www.drupal.org/project/canvas).

---

## 2. Canvas AI SEO

**Machine name:** `canvas_ai_seo`
**Package:** Drupal Canvas
**Dependencies:** canvas_ai, metatag

### Short description

AI-generated Schema.org JSON-LD for Canvas pages.

### Full description (paste below this line)

Adds structured data generation to the Canvas AI page builder. When an AI agent
builds or edits a Canvas page, this module can generate Schema.org JSON-LD
markup based on the page content and inject it into the page response.

The module provides two AiFunctionCall plugins:

- **AddSchemaOrgJson:** Generates Schema.org JSON-LD from the page's component
  tree and attaches it to the canvas page entity. The SEO agent calls this
  after page content is finalized.
- **GetLinkableComponents:** Scans the page layout for components that accept
  internal links (href, url props), returning a structured tree the agent can
  use for internal linking decisions.

A `LayoutResponseSubscriber` injects the generated JSON-LD into the page
response at render time, and a `ConfigAction` plugin (`AiContextAgentsUpdate`)
maps ai_context items to the SEO agent during recipe installation.

Integrates with the [Metatag](https://www.drupal.org/project/metatag) module
for meta tag management. The JSON-LD output is validated against Schema.org
vocabularies and truncated to avoid blocking page publishing for long payloads.

Requires: [Canvas AI](https://www.drupal.org/project/canvas),
[Metatag](https://www.drupal.org/project/metatag).

---

## 3. AI Google Analytics

**Machine name:** `ai_google_analytics`
**Package:** AI
**Dependencies:** ai, ai_agents, ai_context, canvas

### Short description

Deterministic GA4 benchmark evaluation for Canvas pages with AI-powered failure summaries.

### Full description (paste below this line)

Monitors Google Analytics metrics for Canvas pages and flags pages that fall
below performance benchmarks. The pass/fail decision is deterministic — PHP
compares metrics against configurable thresholds. AI is used only to summarize
failures for editorial review.

**How it works:**

1. **Cron fetches GA4 data:** The `GoogleAnalyticsCronService` polls the GA4
   Data API (last 90 days) for each monitored Canvas page and stores engaged
   sessions, bounce rate, and key event rate on the page entity.
2. **Presave evaluates benchmarks:** When a Canvas page is saved, the
   `BenchmarkEvaluator` compares stored metrics against thresholds. No AI is
   involved in this step.
3. **AI summarizes failures:** If any benchmark fails, the module invokes the
   `analytics_monitoring_agent` (shipped in config) to generate a
   human-readable summary. The admin receives an email notification.

**What's included:**

- **GoogleAnalytics AiFunctionCall plugin:** Exposes GA data queries to any AI
  agent in the pipeline. Agents can request metrics for specific pages (last
  90 days).
- **BenchmarkEvaluator service:** Deterministic threshold comparison for
  engaged sessions, bounce rate, and key event rate. Supports per-page
  overrides.
- **Cron service:** Polls GA4 Data API on cron and stores metrics on Canvas
  page entities.
- **Settings form:** Configure GA property ID, API credentials, global
  benchmark thresholds, and notification settings.
- **Review controller:** Displays pages that have failed benchmarks with
  AI-generated summaries and a link to open Canvas for remediation.
- **7 base fields on Canvas pages:** Monitoring toggle, 3 metric fields
  (engaged sessions, bounce rate, key event rate), and 3 per-page benchmark
  override fields.
- **Shipped agent config:** A pre-configured `analytics_monitoring_agent` with
  structured output schema for failure summaries.

Requires: [AI](https://www.drupal.org/project/ai),
[AI Agents](https://www.drupal.org/project/ai_agents),
[AI Context](https://www.drupal.org/project/ai_context),
[Canvas](https://www.drupal.org/project/canvas).
Also requires the `google/analytics-data` Composer package.
Google Analytics API credentials must be configured separately.

