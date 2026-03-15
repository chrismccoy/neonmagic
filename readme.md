# Neon Magic

A WordPress theme built for digital download marketplaces. Its built for selling and distributing design resources, templates, and digital files.

## Features

### Design System
- 🎨 Neo-brutalist design with hard box shadows, bold 3px borders, and physical button interactions
- 🌈 Three-color neon palette — Cyan (#5CE1E6), Yellow (#FFDE59), Pink (#FF90E8)
- 🔤 Curated typography stack — Inter (800/900) for headings, IBM Plex Mono (500–700) for accents
- 💡 Custom Tailwind CSS configuration with hard shadow utilities (sm/md/lg/xl) and marquee animation
- 📐 1400px content width with responsive breakpoints across mobile, tablet, and desktop

### Custom Post Meta
- 💰 Paid/Free toggle — switches between Buy (external URL) and Download (direct file) CTA flows
- 💵 Price field with external purchase URL (Gumroad, Payhip, etc.)
- 📦 File metadata — type, size, compatibility, and version string fields
- 📊 Download counter — auto-incremented on every tracked free download
- 📜 License and attribution fields with smart defaults based on paid/free status
- ✅ "What's Included" checklist — one item per line textarea, rendered as icon list
- ⚙️ Requirements field — typed rows (check / info / cross) with pipe-delimited format
- 📝 Changelog — JSON-structured version history with color-coded release entries
- 🖼️ Preview gallery — up to 4 additional images via WordPress Media Library (comma-separated IDs)

### Admin Interface
- 🗂️ Six dedicated meta boxes — Pricing & Download, File Information, Preview Gallery, What's Included, Requirements, Changelog
- 🔐 Full nonce verification and capability checks on all meta save operations
- 🖼️ WordPress Media Library integration for the gallery uploader with duplicate prevention
- 👁️ Dynamic field visibility — paid fields hide when free mode is selected, and vice versa
- ➕ Dynamic repeater rows for Requirements and Changelog entries with add/remove controls
- 🔧 Client-side changelog template system with form serialization on submit

### Archive & Homepage
- 🦸 Hero block trio on homepage — Instant Access, Commercial License, Free Updates
- 🃏 Responsive post grid — 1 column (mobile) → 2 (tablet) → 3 columns (desktop), 6px gap
- 🔄 AJAX Load More button with spinner state and "All Files Loaded" end-state
- 🔢 Numbered pagination — fixed 8-page window (3 before / 4 after current) with ellipsis and edge clamping
- 🏷️ Adaptive archive title — adjusts label for home, search, category, tag, and date archives
- 🍞 Breadcrumb navigation — Home › Category › Current Page
- 🔍 "Nothing Found" empty state with fallback search form

### Single Post Page
- 🖼️ Main preview image with 1:1 aspect ratio, paid/free badge overlay, and file type badge
- 🎞️ Thumbnail gallery strip — up to 5 images (featured + 4 gallery) with active-state switcher
⭐ Star rating display with average score and review count pulled from comments
- 📥 Download count display with formatted thousands separator
- 💳 Large price box (paid) or Free box (free) with file size, version, and date metadata
- 🗒️ Excerpt display when available
- 📋 "What's Inside" checklist rendered in a 2-column grid with checkmark icons
- 🛒 Context-aware CTA — "Buy Now" (external link) for paid, "Free Download" (AJAX) for free
- 🔗 Secondary action row — Share (Web Share API / clipboard fallback), Save (localStorage), Report
- 🏅 Info badge grid — Instant Download, Clean File, License type, Last Updated
- 📑 Five-tab content section — About, File Details, Requirements, Changelog, Reviews
- 📊 File Details tab — formatted 2-column metadata table
- ✔️ Requirements tab — typed icon indicators (green check, blue info, gray cross)
- 📅 Changelog tab — color-coded version headers with date and change list
- 💬 Reviews tab — average rating display, review list, and comment form with star widget
- 🔀 Related posts section — 4 random posts from the same category

### Navigation & Header
- 📌 Sticky header with z-index layering
- 🔰 Logo badge — custom logo or initials fallback, yellow background, rotated on hover
- 🔍 Integrated search form in the header with icon button
- 🌈 Custom nav walker — primary menu items cycle through neon accent colors
- 📍 Active menu item highlighted with cyan background
- 🔁 Fallback navigation — shows Downloads link + top 5 categories when no menu is assigned

### Footer & Mobile
- 📱 Mobile slide-out menu — fixed overlay with yellow background and translate animation
- ✖️ Pink close button for mobile menu with Escape key support
- 🔝 Scroll-to-top button — fixed bottom-right, appears after 300px scroll, smooth scroll behavior
- 🗓️ Dynamic copyright year
- 🦶 Footer navigation with custom walker and simplified brutalist link styling

### Sidebar
- 📌 Sticky sidebar — top-28, hidden on mobile, visible on medium+ screens
- 📁 File Types section — categories with post count badges, sorted by count descending
- 🕒 Recent Uploads — 5 latest posts with thumbnail (64×48), file type badge, title, and date
- 🏷️ Tags section — all tags sorted by count with hashtag prefix styling
- 🧩 Dynamic widget area (sidebar-1) for custom widgets

### AJAX & Interactivity
⬇️ Download tracking AJAX — increments count, returns file URL, drives spinner → checkmark → reset UI flow
- ♾️ Load More AJAX — supports category, tag, and search context; hides button when all posts are loaded
- 🌟 Interactive star rating widget — hover paint, click-to-select, hidden input update, keyboard accessible
- 🖼️ Gallery thumbnail switcher — click to swap main image with active border/shadow state
- 📑 Tab switcher — aria-attribute management, panel visibility, no-flash toggle
- 💾 Save/favorite system — localStorage persistence, toggled state across sessions
- 📤 Share button — native Web Share API with clipboard fallback and success feedback
