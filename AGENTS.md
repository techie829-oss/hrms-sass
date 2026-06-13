# Antigravity Agent Rulebook — Solidrix HRMS Project

## ⚡ Core Working Rules

### 1. NO EXTRA SCRIPTS
**Never create standalone `.php` or `.py` scripts** to make changes.
- ❌ `fix_perms.php`, `update_payroll.py`, `init_system.php` — NEVER
- ✅ Directly edit the target file using view_file → replace_file_content or multi_replace_file_content

**Why (user explained):**
- Self-doubt — if I need a script, I'm not confident about what I'm doing
- Impact unverified — script runs but actual result not confirmed
- Untracked changes — git history unclear, no trace of what changed
- Existing code conflict risk — script can silently overwrite working code
- Review burden — user has to read extra files to understand what happened

---

### 2. READ BEFORE EDITING
Always view_file the target file before making any changes.
- Understand existing structure first
- Make surgical edits, not full rewrites unless necessary

---

### 3. USE CORRECT LAYOUT
All public/marketing pages must use layouts.marketing — NOT layouts.landing.
- Always check routes/web.php before editing views
- Route core.about → resources/views/core/about.blade.php
- Route core.contact → resources/views/core/contact.blade.php
- Route core.features → resources/views/core/features.blade.php

---

### 4. USE REAL DATA — NO PLACEHOLDERS
Content must come from sklops.com reference:
- Phone: +91 70074 20572
- Email: support@sklops.com
- Address: Lakhimpur, Uttar Pradesh – 262701
- WhatsApp: https://wa.me/917007420572

---

### 5. QA FIRST — ASK BEFORE DOING
If direction is unclear, ask the user before proceeding.
- Do not assume scope and go off track
- Confirm which file/route is actually being used before editing

---

### 6. ARCHITECTURE
- Laravel multi-tenant SaaS
- Central routes: routes/web.php
- Tenant routes: routes/tenant.php
- Permissions: use PermissionConstants — no hardcoded strings
- CSS: Tailwind via app.css
