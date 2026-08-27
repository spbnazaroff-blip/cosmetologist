# cosmetologist

Private website project for a cosmetologist.

## Branch model

- `main` — stable / production-ready only
- `dev` — current tested development
- `feature/*` — isolated feature work before merge into `dev`

## Current build

Initial responsive PHP landing page with:

- central `config.php` for specialist/contact data
- responsive layout
- services, approach, about, FAQ and contact sections
- noindex protection during development
- no secrets committed to the repository

## Development safety

The repository must not contain `.env`, passwords, API keys, SSH keys, database credentials or personal secrets.

Production indexing must stay disabled until the explicit launch step.
