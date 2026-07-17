# Perwira Parcel System

Parcel tracking system with staff and receiver portals, QR code labels, and
tracking lookup. Built with PHP, MySQL, and Bootstrap, running on XAMPP.

## Running locally

1. Start Apache and MySQL from the XAMPP control panel.
2. Create a database named `project_db` in phpMyAdmin and import the schema.
3. Open <http://localhost/perwiraparcelsystem/landingpage.html>.

Database settings live in `db_connect.php`.

## Branching workflow

`main` is the protected branch. It should always be working code. You never
commit to it directly — changes reach it only through a reviewed pull request.

```
main ─────●────────────────────●──────>   (protected, always working)
           \                  /
    dev      ●──●──●─────────●            (your work, then PR)
```

To make a change:

```bash
# 1. Start from an up-to-date main
git checkout main
git pull

# 2. Branch for the thing you're building
git checkout -b feature/parcel-search

# 3. Work, then commit
git add .
git commit -m "Add parcel search by tracking number"

# 4. Push the branch to GitHub
git push -u origin feature/parcel-search
```

Then on GitHub, open a **Pull Request** from your branch into `main`. CI runs
automatically on the PR. Once the checks pass and the PR is approved, click
**Merge**. Delete the branch afterwards.

Branch naming: `feature/...` for new work, `fix/...` for bug fixes.

## What CI checks

Defined in `.github/workflows/ci.yml`, run on every push and every PR to `main`:

| Check | What it catches |
|---|---|
| PHP syntax check | Any `.php` file that won't parse — a typo that would white-screen the site |
| Repo hygiene | Generated QR cache or a `.env` file getting committed by accident |

A red check blocks the merge, so broken PHP can't reach `main`.

## Deployment

There is no automatic deploy. The app runs on XAMPP locally. If it is ever
hosted on a real server, a deploy job can be added to the CI workflow to
publish `main` on merge.
