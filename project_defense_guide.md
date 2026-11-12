# MEDLINK Project Defense Guide

This guide is designed to help you confidently explain your tech stack and debugging process to your panelists.

## 1. Architecture & Tech Stack Overview
When asked about your tech stack, explain that you are using a **"Hybrid Laravel Architecture"**:

* **Laravel (Backend):** 
  Handles all the heavy lifting—routing, database queries, authentication, and serving the Blade templates (like `welcome.blade.php`).
* **Tailwind CSS (Frontend Styling):** 
  We use Tailwind for our UI. Instead of writing separate CSS files, we use utility classes directly inside our HTML/Blade files. For our landing page, we use the Tailwind v4 CDN for instant rendering, injecting our custom brand colors using the `<style type="text/tailwindcss">` block in the `<head>`.
* **Vue.js (Frontend Interactivity):** 
  We use Vue.js for specific parts of the page that need to be highly interactive without reloading the page. Instead of building the entire frontend in Vue (which is complex and takes longer), we inject Vue components directly into our Laravel Blade views where needed.

## 2. Explaining "Where Vue.js Goes"
If the panelists ask, *"Where is your Vue code located and how does it load?"*

**Your Answer:**
> "Our Vue.js logic lives inside the `resources/js/` directory. Our main entry point is `resources/js/medlink.js`. 
> 
> We use **Vite** as our bundler. In our Blade files, we use the `@vite(['resources/js/medlink.js'])` directive. This tells Laravel to load the compiled Vue JavaScript. Whenever we want to add an interactive feature, we create a `.vue` component, import it into `medlink.js`, and mount it to a specific HTML `div` ID in our Blade file."

## 3. Explaining Your Debugging Process
Panelists love asking about debugging because it proves you actually wrote and understand the code. Divide your answer into Frontend and Backend.

**Question:** *"How do you debug this system when there's an error?"*

**Your Answer:**
> "Because our stack is separated into backend and frontend technologies, our debugging process depends on where the error happens:
>
> **1. For UI and Styling (Tailwind CSS):**
> If something looks wrong on the screen, we right-click and use the browser's **Inspect Element / DevTools**. Since Tailwind uses utility classes, we can toggle classes on and off directly in the browser to fix alignment or color issues before changing the actual code.
>
> **2. For Javascript & Interactivity (Vue.js & Vite):**
> If a button isn't clicking or data isn't updating on the screen, we check the **Browser Console** for JavaScript errors. We also use the **Vue DevTools** browser extension to inspect the state and variables of our Vue components in real-time. Additionally, we monitor our terminal running `npm run dev`—if we make a syntax error in Vue, Vite will instantly print the error there.
>
> **3. For Server, Database, & Logic (Laravel):**
> If the server crashes, data isn't saving, or we get a 500 Server Error, we rely on Laravel's error pages which tell us exactly what line caused the crash. For deeper debugging, we use Laravel's built-in `dd()` (dump and die) function to inspect variables in the backend before they reach the view. We also check the `storage/logs/laravel.log` file where all backend errors are recorded."

## Bonus Tip: Why not a Full Single Page Application (SPA)?
If they ask why you didn't just build the whole thing in pure Vue (as an SPA):

> "Building a Single Page Application adds unnecessary complexity with APIs and CORS. By using Laravel Blade for our main structure and sprinkling **Vue.js** only where we need interactivity, we keep our codebase much simpler, faster to load, and easier to maintain for a project of this scope."
