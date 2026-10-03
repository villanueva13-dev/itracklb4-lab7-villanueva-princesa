# ITRAKB4 LA7 - Web Programming 2

## Part F: Questions & Answers

### Q1: Your form sends data with POST rather than GET. Explain what would go wrong if it used GET instead. Your answer should say something about what a browser does when a page is refreshed.
If a form uses GET to submit new data, all user input is appended directly to the URL as query parameters. When a user refreshes a page loaded via a GET request, the browser re-issues that exact same request without warning, causing the server to execute the save logic again and duplicate the record. Using POST forces the browser to alert the user before re-submitting data and allows the server to handle the request as a state-altering action.

### Q2: When validation fails, your controller does not run the code that saves the record — and you did not write an if statement to stop it. Explain what actually stops it, and where the visitor ends up.
When `$request->validate()` detects a validation failure, Laravel automatically throws a `ValidationException`. This exception immediately interrupts the normal controller execution flow before reaching any code below it. Laravel's exception handler intercepts this exception and redirects the visitor back to the form page they came from, flashing the validation errors and old input back into the session.

### Q3: Your success message is displayed from the layout, which renders on every page. Explain why it does not appear on every page.
The success message is stored in the session as "flash" data using `with('success', '...')`. Flash data only lives in the session for the immediate next HTTP request and is automatically deleted right after that single request completes. Since the layout checks whether `session('success')` exists before rendering the banner, it only displays on the page rendered right after the redirect and vanishes when navigating to any other page.
