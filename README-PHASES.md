# EduFocus – Phase Plan

## Phase 1: Foundation and Auth
- Set up Laravel 12 project, configs, Tailwind/Vite.
- Roles: admin, parent, student on `users.role`.
- Basic models: `User`, `Student`, `Course`, `AiInteraction`.
- Middleware: `role` alias.

## Phase 2: Public Website and Onboarding UX
- Home page matching hero + sign-in card and SSO buttons.
- Reusable popup component `resources/views/components/welcome-modal.blade.php` with localStorage dismiss.
- Structure: `resources/views/home/index.blade.php`, components loaded via `@include` and Vite module `resources/js/components/welcome-modal.js`.

## Phase 3: AI Features (MVP)
- Controller endpoints for homework help and study plan.
- Log interactions into `ai_interactions`.
- Configure `OPENAI_API_KEY` and service wrapper for requests.

## Phase 4: Role Dashboards
- `admin.dashboard`, `parent.dashboard`, `student.dashboard` skeleton pages.
- Navigation based on role.

## Phase 5: Curriculum & Data Models
- Entities: courses, enrollments, assignments.
- Teacher linking to courses; student enrollment pivot.

## Phase 6: Cognitive Suite
- Focus mode timers and logs.
- Cognitive mini-games module placeholders; analytics events.

## Phase 7: SSO and Compliance
- SSO via Google/Microsoft/Classlink (Laravel Socialite or school SSO provider).
- Data privacy settings, audit logs, exports.

## Phase 8: Polish & Docs
- Consistent styles, components library, accessibility review.
- Deployment guides and environment templates.

### Implementation Notes
- Styles via Tailwind; no Alpine usage (plain JS modules) per current setup.
- Components under `resources/views/components`; page groupings under `resources/views/<area>`.
- Keep auth flows minimal now; wire to real SSO later.
