# AGENTS.md — Pustaka

> Digital library web app. Only source of truth: `PRD.md`. No code exists yet.

---

## Tech Stack (from PRD §8)

| Layer | Decision |
|-------|----------|
| Framework | Next.js 14 (App Router) |
| Language | TypeScript (strict) |
| Styling | Tailwind CSS + shadcn/ui |
| Database | PostgreSQL (Neon) |
| ORM | Drizzle ORM |
| Auth | Auth.js (NextAuth v5) — email/password + Google OAuth |
| File Storage | Cloudflare R2 (S3-compatible, via @aws-sdk/client-s3) |
| PDF Reader | PDF.js (Mozilla) |
| EPUB Reader | epub.js (Future Press) |
| Email | Resend |
| Deploy | Vercel |
| Cron | Vercel Cron / pg_cron |
| Testing | Vitest + Playwright |

**Key constraint**: "Warm Neutral" theme (PRD §6) — Lora serif for headings, Inter for UI, specific color tokens.

---

## Project Phases (PRD §10 + refined)

| Phase | Duration | Deliverable | Dependencies |
|-------|----------|-------------|--------------|
| 0: Discovery | 1 wk | Resolve open questions, finalize DB schema, pick storage | — |
| 1: Foundation & Auth | 2 wk | Project, DB, Auth (email + OAuth), RBAC | Phase 0 |
| 2: Katalog & Admin CRUD | 2 wk | Book schema, API katalog, UI katalog/detail, Admin CRUD + upload | Phase 1 |
| 3: Reader & Bookshelf | 3 wk | PDF.js, epub.js, progress tracking, bookmark, bookshelf UI | Phase 2 |
| 4: Sirkulasi | 2 wk | Pinjam/kembali, reservasi, extend, cron auto-return, notifikasi email | Phase 1, 2 |
| 5: Admin Dashboard | 2 wk | Layout admin, user mgmt, dashboard sirkulasi, export CSV | Phase 1, 4 |
| 6: QA & Deploy | 1 wk | Testing, a11y, perf, deploy Vercel/Neon/R2 | All |

---

## Resolved Decisions (2026-11-02)

| Decision | Detail |
|----------|--------|
| Storage | Cloudflare R2 (S3-compatible, zero egress fee, via @aws-sdk/client-s3) |
| Max pinjam simultan per user | 3 buku |
| Sumber konten | Admin upload only (v1), self-publish v2 (out of scope) |
| Moderasi konten | Tidak perlu (hanya admin upload) |
| Denda | `fine_amount` numeric di borrowings; hitung Rp1.000/hari overdue, tidak ditagih; user diblokir pinjam baru jika >7 hari terlambat |
| Schema buku | Satu tabel `books` dengan `total_copies` + `available_copies`, tanpa `book_copies` |
| Reservation queue | ORDER BY created_at ASC; posisi dihitung on-the-fly via COUNT reserv aktif |

---

## Schema Summary

All schema decisions are resolved (see Resolved Decisions above):
- Single `books` table with `total_copies` + `available_copies` (no `book_copies` relation)
- `fine_amount` numeric on borrowings (logged, not charged)
- Reservation queue ordered by `created_at ASC`, position calculated on-the-fly
- CSV import required columns: `title`, `author`, `category`, `isbn`, `total_copies`, `file_url`, `cover_url`

## Critical Schema Fixes (Pre-Execution)

| Issue | Fix |
|-------|-----|
| FK type mismatch `users.id` (uuid) vs `accounts.user_id`/`sessions.user_id` | Ganti `text("user_id")` menjadi `uuid("user_id")` di tabel `accounts` dan `sessions` |
| `verificationTokens` primary key | Tambah composite PK: `primaryKey({ columns: [identifier, token] })` dari `drizzle-orm/pg-core` |

---

## Commands (Expected — Not Yet Configured)

```bash
# Setup (after Phase 0)
pnpm install
pnpm r2:setup       # configure AWS credentials + R2 bucket + endpoint
pnpm db:generate    # drizzle-kit generate
pnpm db:push        # drizzle-kit push (dev)
pnpm db:migrate     # drizzle-kit migrate (prod)
pnpm dev            # next dev

# Quality gates (run in order)
pnpm lint           # eslint
pnpm typecheck      # tsc --noEmit
pnpm test           # vitest
pnpm test:e2e       # playwright
```

---

## Conventions from PRD

- **Dark mode**: Only in reader (PST-306), not global
- **RBAC**: Two roles only — `member` | `admin` (PST-105)
- **Loan duration**: 7 hari default, extend 1× (PST-404)
- **Progress tracking**: Debounced 2s save on page change + visibility change (PST-304)
- **Email templates**: H-1, due today, overdue, reserved available, extended (PST-407)
- **Accessibility**: WCAG 2.1 AA target (PST-602)

---

## Out of Scope (v1) — Do Not Implement

Native mobile app, social features (review/forum/following), paid fines/payments, AI recommendation, multi-language, multi-tenant.

---

## Files to Create First (Phase 1)

```
/src
  /app                    # Next.js App Router
  /components             # shadcn/ui + custom
  /lib
    /db                   # drizzle client + schema
    /auth                 # auth.js config
    /utils                # helpers
  /types                  # shared types
drizzle.config.ts
next.config.js
tailwind.config.ts
tsconfig.json
.env.example
```

---

## Risk Areas (High)

1. **epub.js on mobile** — test early, layout reflow issues
2. **File storage choice** — affects upload flow, CDN, cost
3. **Cron reliability** — Vercel Cron vs pg_cron for auto-return
4. **PDF.js worker** — must configure for Next.js App Router