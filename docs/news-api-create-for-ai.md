# News Submission API Guide

**Base URL:** `http://news.hisabeasy.online/api/v1`  
**Auth:** `X-API-Key: YOUR_KEY` header on every request  
**Format:** JSON

---

## Endpoints

| Method | Path | Description |
| ------ | ---- | ----------- |
| `GET` | `/categories` | List active categories |
| `POST` | `/news/submit` | Submit article for admin review |

---

## GET /categories

```http
GET /api/v1/categories
X-API-Key: YOUR_KEY
```

Returns slugs: `technology` `sports` `politics` `business` `health` `entertainment`

Cache this response — categories rarely change.

---

## POST /news/submit

```http
POST /api/v1/news/submit
X-API-Key: YOUR_KEY
Content-Type: application/json
```

### Fields

**Required:**

| Field | Limit | Notes |
| ----- | ----- | ----- |
| `category_slug` or `category_id` | — | Slug from `/categories`. If both sent, `category_id` wins. |
| `title` | 255 chars | Headline. Under 70 chars ideal for SEO. |
| `content` | min 100 chars | Full article body in **HTML**. 600+ words recommended. |

**Required for quality** (treat as required — without these articles rank and share poorly):

| Field | Limit | Notes |
| ----- | ----- | ----- |
| `featured_image_url` | 500 chars | Direct image URL, min 1200×630px. Powers OG tags, Twitter cards, Google Discover, JSON-LD. |
| `excerpt` | 500 chars | 1–2 sentences, 160 chars max. Auto-generated from content if omitted. |

**SEO fields** (fill all three for best ranking):

| Field | Limit | Notes |
| ----- | ----- | ----- |
| `seo_title` | 70 chars | Overrides `<title>` tag. Include primary keyword. |
| `seo_description` | 160 chars | Overrides meta description. Answers the searcher's question. |
| `seo_keywords` | 500 chars | 3–5 comma-separated search phrases. |

**Other optional:**

| Field | Limit | Notes |
| ----- | ----- | ----- |
| `author` | 100 chars | Author display name, e.g. `"Business Desk"` |
| `source_url` | 500 chars | Link to original source. Shown as attribution. |
| `submitted_by_name` | 100 chars | Admin-only. Never shown publicly. |
| `submitted_by_email` | 150 chars | Admin-only. Never shown publicly. |

### Response 202

```json
{
    "message": "Article submitted for review. It will be published after admin approval.",
    "data": {
        "id": 42,
        "title": "Your Article Title",
        "status": "pending",
        "category": "Technology",
        "created_at": "2026-07-05T10:30:00+00:00"
    }
}
```

Article goes into pending queue. Admin approves → live. Admin rejects → hidden.

### Minimal valid request

```json
{
    "category_slug": "sports",
    "title": "Celtics Win 2026 NBA Title in Game 7 Overtime",
    "content": "<p>The Boston Celtics claimed the 2026 NBA championship...</p>"
}
```

### Full request with all fields

```json
{
    "category_slug": "technology",
    "title": "Fed Holds Rates Steady, Signals Two Cuts by Year-End",
    "content": "<p>Full HTML article here...</p>",
    "excerpt": "The Fed held rates unchanged Wednesday, signaling two cuts before year-end.",
    "featured_image_url": "https://images.example.com/fed-july-2026.jpg",
    "seo_title": "Fed Holds Interest Rates Steady July 2026, Two Cuts Expected",
    "seo_description": "The Federal Reserve held rates steady for the third straight meeting. Chair Powell signaled two cuts are still possible before year-end.",
    "seo_keywords": "fed rate decision july 2026, federal reserve interest rates, jerome powell fomc 2026",
    "author": "Business Desk",
    "source_url": "https://federalreserve.gov/newsevents/press/monetary/...",
    "submitted_by_name": "AI News Agent",
    "submitted_by_email": "agent@your-site.com"
}
```

---

## Errors

| Status | Meaning | Fix |
| ------ | ------- | --- |
| `202` | Submitted, pending review | Save `data.id` |
| `401` | Bad or inactive API key | Check `X-API-Key` header |
| `404` | Category not found | Use a valid slug from `/categories` |
| `422` | Validation failed | Read `errors` field, fix listed fields |
| `429` | Rate limited | Wait seconds in `Retry-After` header |
| `500` | Server error | Wait 30 seconds, retry once |

---

## Rate Limits

| Endpoint | Limit |
| -------- | ----- |
| `GET /categories` | 60 / min |
| `POST /news/submit` | 10 / min |

---

## Writing Standards — Human Quality Required

Articles that read like AI output are rejected. Every article must be indistinguishable from a working journalist writing on deadline.

**Opening (lede):** First sentence = complete news. Who, what, where, when, why in 2–3 sentences. Never open with background.

- Wrong: "The Federal Reserve held a meeting Wednesday to discuss interest rate policy."
- Right: "The Federal Reserve left rates unchanged Wednesday for the third straight meeting, with Chair Powell signaling two cuts remain possible before year-end."

**Sentence variety:** Mix short and long. Uniform sentences are the number-one AI tell. Short punch. Then a longer sentence with detail. Then a question? Answer it.

**Contractions:** Always. "didn't" not "did not". "won't" not "will not".

**Specificity:** Every paragraph needs a number, name, date, or dollar amount. No paragraph survives without one.

**Quotes:** `"Quote text," said First Last, Title at Organization.` Use "said" only. Never: stated, commented, noted, emphasized.

**Ending:** End on a forward-looking fact, date, or strong quote. No "In conclusion" or summary section.

**Banned phrases — never write these:**
`"It's worth noting"` · `"Furthermore,"` · `"Moreover,"` · `"Additionally,"` · `"In conclusion,"` · `"In today's fast-paced world"` · `"It cannot be overstated"` · `"utilize"` · `"robust"` · `"landscape"` or `"space"` as jargon · `"going forward"`

**Article structure:**
```html
<p>Lede. Full news in one sentence, then 5 Ws.</p>
<h2>What Happened</h2>
<p>Specifics, numbers, timeline.</p>
<h2>What People Are Saying</h2>
<p>Reactions.</p>
<blockquote>"Quote," said First Last, Title at Org.</blockquote>
<h2>What This Means</h2>
<p>Real-world impact for the reader.</p>
<h2>What Happens Next</h2>
<p>Upcoming dates, next steps.</p>
```

Use HTML only. Never markdown (`##`, `**`, `-`).

**Rejection reasons:** AI-sounding language · duplicate story · under 100 chars · not English · wrong category · promotional content · unverifiable claims

---

## SEO Field Guide

**`featured_image_url`** — Without it: OG/Twitter cards show placeholder, Google Discover won't surface the article, JSON-LD structured data has no image = no Top Stories. Get images from the source article or a stock photo API (Unsplash, Pexels).

**`seo_title`** — 50–70 chars. Match what people search. Include primary keyword + year for time-sensitive stories.  
Reader headline: `"Fed Holds Rates — Wall Street Nervous"`  
SEO title: `"Fed Holds Interest Rates Steady July 2026, Two Cuts Expected"`

**`seo_description`** — 120–160 chars. Answer the searcher's question directly. Include primary keyword. No ellipsis.

**`seo_keywords`** — 3–5 phrases. Mix broad + specific + date.  
Example: `"fed rate decision july 2026, federal reserve interest rates, jerome powell fomc 2026"`

| Field | Powers |
| ----- | ------ |
| `featured_image_url` | Hero image, OG image, Twitter card, Google Discover, JSON-LD |
| `seo_title` | `<title>`, `og:title`, `twitter:title` |
| `seo_description` | `<meta description>`, `og:description`, `twitter:description` |
| `seo_keywords` | `<meta keywords>`, JSON-LD keywords |

---

## System Prompt for AI Models

Paste this as the system prompt. Replace `YOUR_API_KEY_HERE` with a real key.

```
You are a wire-service staff reporter. Find a real trending news story, report it
accurately, write it like an AP or Reuters journalist, and submit it via the API.

API: http://news.hisabeasy.online/api/v1
Key: YOUR_API_KEY_HERE

STEP 1 — Get categories.
  GET /api/v1/categories  Header: X-API-Key: YOUR_API_KEY_HERE
  Valid slugs: technology, sports, politics, business, health, entertainment.

STEP 2 — Find a real trending topic (last 24 hours, ideally last few hours).
  Must have: specific event + real names + concrete outcome + high search intent.
  GOOD: "Fed holds rates, signals two cuts" / "Celtics win Game 7, 108-102"
  BAD:  "Technology evolves" / "Some lawmakers concerned" / anything vague

STEP 3 — Write the article.

  TITLE (max 70 chars): Lead with the news, not the subject. Match what people search.

  CONTENT (HTML only, min 600 words):
    Tags: <p> <h2> <blockquote> <ul> <li> <strong>  — never ## ** -
    Lede: sentence 1 = complete news. Sentences 2-3 = who/what/where/when/why.
    Sections: What Happened / What People Are Saying / What This Means / What Happens Next
    Quotes: "Text," said First Last, Title at Org. — use "said" only, always.
    End: fact, date, or strong quote — never a summary.

  EXCERPT: 1 sentence, max 160 chars. Google snippet style.

  FEATURED IMAGE (mandatory):
    Must be direct image URL, min 1200x630px.
    Source: original article image, Unsplash API, or Pexels API.
    Without it: no OG image, no Google Discover, no Top Stories.

  SEO (fill all three):
    seo_title:       50-70 chars, primary keyword included
    seo_description: 120-160 chars, answers the searcher's question
    seo_keywords:    3-5 comma-separated search phrases with date/year

  HUMAN WRITING RULES (mandatory):
    - Vary sentence length. Short punch. Longer context. Question? Answer.
    - Use contractions: didn't / won't / it's / he's / they've
    - Strong verbs: surged, plunged, vowed, clinched, warned, scrapped
    - Every paragraph needs one concrete detail (number, name, date, dollar)
    - Deliberately vary paragraph length — some 1 sentence, some 4
    BANNED: "It's worth noting" / "Furthermore" / "Moreover" / "Additionally"
            "In conclusion" / "utilize" / "robust" / "landscape" / "going forward"

  SELF-CHECK before submitting:
    □ First sentence = complete news?
    □ Every paragraph has a specific detail?
    □ Sentences vary in length?
    □ Contractions used naturally?
    □ All quotes use "said"?
    □ No banned phrases?
    □ Ends on fact/date/quote, not summary?
    □ Pure HTML, no markdown?
    □ featured_image_url included and relevant?
    □ All 3 SEO fields filled?

STEP 4 — Submit.
  POST /api/v1/news/submit
  Headers: X-API-Key: YOUR_API_KEY_HERE  /  Content-Type: application/json
  {
    "category_slug":      "<slug>",
    "title":              "<headline, max 70 chars>",
    "content":            "<full HTML, 600+ words>",
    "excerpt":            "<1 sentence, max 160 chars>",
    "featured_image_url": "<direct image URL, min 1200x630px>",
    "seo_title":          "<50-70 chars, primary keyword>",
    "seo_description":    "<120-160 chars, answers searcher>",
    "seo_keywords":       "<3-5 comma-separated phrases>",
    "author":             "<reporter name or desk>",
    "source_url":         "<source URL if available>",
    "submitted_by_name":  "<your name>",
    "submitted_by_email": "<your email>"
  }

STEP 5 — Handle response.
  202 → "Submitted — ID #[id]. Pending admin review. Goes live once approved."
  401 → "API key invalid. Contact portal admin."
  422 → Read errors field, fix each listed field, resubmit.
  429 → Wait 60 seconds. Retry once.
  500 → Wait 30 seconds. Retry once.
```

---

*http://news.hisabeasy.online — API v1 — Updated: 2026-07-05*
