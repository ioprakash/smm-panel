# Laravel SMM Panel - Deployment & Usage Guide

## 1. Installation

1.  **Environment Setup**:
    *   Ensure PHP 8.2+, MySQL, and Redis are running.
    *   Copy `.env.example` to `.env` and configure `DB_*` settings.
    *   Run `php artisan key:generate`.

2.  **Database**:
    *   Run `php artisan migrate --seed` to setup tables and demo data.
    *   **Default Admin**: `admin@smmpanel.com` / `password`
    *   **Default User**: `user@smmpanel.com` / `password`

3.  **Cron Job**:
    *   Set up a system cron to run the schedule every minute:
        ```bash
        * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
        ```
    *   This is CRITICAL for order status syncing.

## 2. Core Features

### Wallet & Payments (~Nepal Focused~)
*   **Deposit**: Users can deposit via **eSewa** (Simulated in this version) or **Khalti**.
*   **Flow**:
    1.  User enters amount.
    2.  Redirects to Gateway (Simulated).
    3.  On Success, redirects back to `/payment/success` and credits balance.
    4.  Transaction logic is in `WalletController`.

### Order System
*   **Placing Orders**: Users select Category -> Service -> Link -> Qty.
*   **Validation**: Checks user balance and min/max quantity.
*   **Provider Sync**:
    *   If a service is linked to a Provider (e.g., JAP), order is sent to their API immediately.
    *   `SyncProviderOrders` command runs every minute to check status (Pending -> Processing -> Completed).

### Reseller API
*   Your panel has a public API for other panels to connect to.
*   **Endpoint**: `POST /api/v2`
*   **Actions**: `add`, `status`, `services`, `balance`.
*   **Auth**: Users generate an API Key in their profile (Auto-generated in this seed).

## 3. Directory Structure
*   `app/Services/Smm`: Core logic for connecting to upstream providers.
*   `app/Services/Payment`: Payment Gateway implementations (EsewaGateway).
*   `app/Console/Commands/Cron`: Background tasks.
*   `tests/Feature`: Automated tests for Order logic.

## 4. Testing
Run the test suite to verify all flows:
```bash
php artisan test
```

## 5. Next Steps for Production
*   **Redis**: Switch `QUEUE_CONNECTION` to `redis` for better performance.
*   **Real Gateways**: Update `EsewaGateway.php` with production URLs and Merchant Credentials.
*   **UI Polish**: Customize `dashboard` views further with Tailwind.
# smm-panel
# smm-panel
# smm-panel
