````markdown
# Cronjob Commands

## Handle Unverified Accounts (Runs once a day)

```bash
0 0 * * * curl -s https://essenbyte.com/backend/automation/unverifiedAccoundHandler.php
```
````

## Update Exchange Rates (Runs once a day)

```bash
0 0 * * * curl -s https://essenbyte.com/backend/automation/updateExchangeRates.php
```

## Update Sitemap (Runs once a day)

```bash
0 0 * * * curl -X POST "https://essenbyte.com/backend/automation/generate-sitemap.php"
```
