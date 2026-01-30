# Admin — Administration, Evaluation & Assessments

This app is for **Admin: Administration, Evaluation & Assessments**. For Hiring use **hr** (`/hr`). Document download is in this folder: `/admin/document-download.php`.

**Always keep:**

| Content type | Location only | Do not |
|--------------|----------------|--------|
| **JavaScript** | `.js` files under `assets/js/` | No inline `<script>`, no `onclick`/`onload` in HTML |
| **CSS** | `.css` files under `assets/css/` | No inline `style=""`, no `<style>` in HTML |
| **HTML / markup** | `.php` templates under `includes/`, `pages/` | No embedded styles or script logic |

## Files

- **JS:** `assets/js/theme-init.js` (head), `assets/js/portal.js` (body)
- **CSS:** `assets/css/portal.css` (entry), plus `variables.css`, `layout.css`, `sidebar.css`, `main.css`, `components.css`, `responsive.css`
- **HTML:** `includes/layout.php`, `pages/*.php`; entry `index.php` only includes and outputs

This convention applies to all new and changed code in **admin** (administration, evaluation, assessments).
