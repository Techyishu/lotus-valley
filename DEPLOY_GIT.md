# Deploying to Hostinger via Git

This guide explains how to deploy your **Lotus Valley** website using Git (GitHub/GitLab/Bitbucket) and Hostinger's Git Deployment feature.

## Prerequisites
1.  A **GitHub**, **GitLab**, or **Bitbucket** account.
2.  A **Hostinger** account with a hosting plan.
3.  **Git** installed on your local computer.

---

## Step 1: Push Your Code to a Remote Repository
First, you need to push your local code to a cloud repository.

1.  **Create a Repository:**
    *   Go to GitHub (or your preferred provider) and create a **New Repository**.
    *   Name it `lotus-valley` (Private recommended).

2.  **Push Local Code:**
    Open your terminal in the project folder and run:
    ```bash
    # Initialize Git (if not already done)
    # git init

    # Add all files
    git add .

    # Commit
    git commit -m "feat: Initial commit for production"

    # Link to your remote repository (Replace URL with yours)
    git remote add origin https://github.com/yourusername/lotus-valley.git
    # If valid remote already exists, use: git remote set-url origin https://...

    # Push to main branch
    git push -u origin main
    ```

    *Note: The `.gitignore` file will ensure sensitive files like `.htaccess` (containing passwords) are NOT pushed to GitHub.*

---

## Step 2: Configure Hostinger Git Deployment
1.  Log in to **Hostinger hPanel**.
2.  Go to **Websites** -> **Manage**.
3.  Scroll down to the **Advanced** section and click **Git**.
4.  **Add a Repository:**
    *   **Repository URL:** Paste your GitHub/GitLab URL (e.g., `https://github.com/yourusername/lotus-valley.git`).
    *   **Branch:** `main` (or `master`).
    *   **Directory:** Leave blank to deploy into `public_html` (recommended if this is the only site), or type a folder name (e.g., `school`) to deploy to `public_html/school`.
        *   *Tip:* If you want the site to be at `yourdomain.com`, deploy to `public_html` (make sure it's empty first).
5.  **Authentication:**
    *   If your repo is **Private**, you need to add an SSH key or Personal Access Token. Hostinger provides an SSH key—copy it and add it to your GitHub "Deploy Keys" section (Settings -> Deploy Keys -> Add deploy key).
    *   If **Public**, no auth needed.
6.  Click **Create**.
7.  Once created, click **Deploy** (in the "Manage" section of the repo you just added).
    *   *Hostinger will pull your code from GitHub and place it in the server directory.*

---

## Step 3: Database Setup
1.  **Create Database:**
    *   Go to **Databases** -> **Management** in hPanel.
    *   Create a new database (Name, User, Password). **Write these down.**
2.  **Import Schema:**
    *   Click **Enter phpMyAdmin**.
    *   Select your database -> **Import**.
    *   Upload the `database.sql` file from your local computer.
    *   Click **Go**.

---

## Step 4: Configure Environment Variables (.htaccess)
Since `.htaccess` (with your passwords) was ignored by Git for security, you must create it manually on the server.

1.  Go to **File Manager** in hPanel.
2.  Navigate to your deployment folder (e.g., `public_html`).
3.  Find `.htaccess.example` (which was deployed via Git).
4.  **Right-click** `.htaccess.example` -> **Rename** to `.htaccess` (remove `.example`).
5.  **Right-click** `.htaccess` -> **Edit**.
6.  Scroll to the bottom and find the **Database Environment Variables** section.
7.  Update the values with your **Hostinger** credentials:

    ```apache
    <IfModule mod_env.c>
        SetEnv DB_HOST "localhost"
        SetEnv DB_NAME "u123456789_lotus"  <-- Your Real Database Name
        SetEnv DB_USER "u123456789_admin"  <-- Your Real App Username
        SetEnv DB_PASS "YourPassword123!"  <-- Your Real Password
        SetEnv DB_PORT "3306"
        SetEnv DB_TYPE "mysql"
    </IfModule>
    ```
8.  **Save** the file.

---

## Step 5: Verify Deployment
1.  Visit your website URL.
2.  If you see "Database Connection Failed", double-check your `.htaccess` values in File Manager.
3.  Log in to `/admin` (Default: `admin` / `admin123`) and **change your password**.

## Future Updates
When you make changes locally:
1.  `git commit` and `git push` to GitHub.
2.  Go to Hostinger -> **Git** -> **Auto Deployment** (Click "Auto Deploy" to enable webhook, or manually click "Deploy" button to pull latest changes).
