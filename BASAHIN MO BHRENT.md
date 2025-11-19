# AI Budgeting Website — README for Groupmate

This README explains everything you need to start working on your assigned modules (budgets, incomes, expenses, AI categorization, and summaries). It also shows how to connect your code with the backend foundation already built by your partner. You can work independently even if you live in another city.

---

# 📌 Overview

This project is a web-based AI-assisted budgeting tool that allows users to:

* Create budgets (daily, weekly, monthly)
* Add income and expenses
* Auto-categorize expenses (rule-based + AI)
* View financial summaries and insights
* Track needs vs wants based on category metadata

The database schema is already complete, and core backend infrastructure is handled separately. Your tasks plug into existing authentication, profile setup, and category filtering.

---

# 🗂 Folder Structure

All your files will go into these folders:

```
/budget/
/expense/
/summary/
```

The backend foundation already includes:

```
/config/db.php
/auth/
/user/
/categories/
```

You will call the existing endpoints when needed.

---

# 🎯 Your Responsibilities

You will create the functional budgeting modules. Below are the components assigned to you, with a short description and expected file locations.

## ✅ 1. Budget Creation API

**Files:**

* `/budget/create_budget.php`
* `/budget/get_budget.php`

**What it does:**

* Creates a new budget depending on the user's chosen cycle (daily/weekly/monthly)
* Stores the income amount
* Ensures that a budget is linked correctly to the user
* Returns the active budget when needed

You will receive `user_id` from your partner’s authentication and session validator.

---

## ✅ 2. Income Handling

Income tracking uses the existing `income` table. You may create a helper API:

* `/budget/add_income.php` (optional)

This accepts:

* income amount
* category (usually “Salary”, “Allowance”, etc.)
* date

You may also skip this if income is stored directly inside the budget.

---

# ✅ 3. Expense System

### Main file:

* `/expense/add_expense.php`

When a user adds an expense:

1. You save the entry to the `expenses` table.
2. Before saving, call your categorization logic to determine the category.
3. Insert both planned and unplanned expenses if needed.

This is one of your most important modules.

---

# 🤖 4. AI + Rule-Based Categorization

**File:** `/expense/auto_categorize.php`

Your job:

* Write a rule-based matching system (keywords → category)
* If no match is found, use fallback logic such as:

  * Pick the most commonly used category
  * OR implement an LLM call (optional, depending on project scope)

The schema supports category metadata:

* `necessity`: need / want / savings / n/a
* `target_audience`: student / employee / business / all

Your categorizer should try to match the correct type.

---

# 📊 5. Summary & Dashboard

### Files:

* `/summary/get_summary.php`
* `/summary/get_ai_insights.php`

### Summary must compute:

* Total expenses
* Total income
* Remaining balance
* Needs vs wants breakdown
* Category totals
* Progress bar values for dashboard

### AI Insights:

* Analyze last spending period
* Look for increases or decreases
* Identify overspending trends
* Insert insights into `ai_insights` table if necessary

These two APIs power the user dashboard.

---

# 🔌 Connecting to Existing Backend

Your partner already built:

* Authentication (`login`, `register`, `check_session`)
* User profile updater
* Category filtering based on occupation / target audience
* Global helpers like `getJsonInput()`, `jsonResponse()`, `requireFields()`

### When building your APIs, you must:

1. Always start file with:

```
require_once "../config/db.php";
require_once "../auth/check_session.php";
```

2. Use `$_SESSION["user_id"]` as the authenticated user.
3. Use helper functions instead of writing raw response code.

This ensures all your modules cooperate smoothly.

---

# 🔧 Testing Endpoints

Use Postman, Thunder Client, or browser fetch calls.

### Example JSON POST request:

```
{
  "amount": 150.25,
  "description": "Milk Tea",
  "date": "2025-11-20"
}
```

### Example header (must include token):

```
Authorization: your_token_here
```

---

# 👥 Collaboration Tips

* Push your code into a `/feature/groupmate-name/` branch before merging.
* Test each endpoint using sample data.
* Communicate when adding new fields or modifying schemas.
* Avoid touching `/auth/`, `/config/`, `/categories/`, and `/user/` so the division remains clean.

---

# ✔️ Final Notes

Once your modules (budget → expense → summary → insights) are complete, the entire system will work end-to-end. You do not need to modify core authentication or database structure—everything is ready for you to build on.

If you need help creating any file listed here, I can generate the full code for you anytime.
