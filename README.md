

# vilt-crud
A simple crud application in VILT 

---

## Project Setup

Follow these steps to set up the project on your local machine:

### 1. Install Laravel
```bash
composer create-project --prefer-dist laravel/laravel vilt "9.*"
cd vilt
```


### 2. Install Laravel Breeze

```bash
composer require laravel/breeze --dev
php artisan breeze:install
```



During the installation, you will be prompted with the following options:
- **Which stack would you like to install?**  
  Select `2` for Vue.js.
- **Would you like to install dark mode support?**  
  Type `yes` or `no` as per your preference.
- **Would you like to install Inertia SSR support?**  
  Type `yes` or `no` as per your preference.
- **Would you prefer Pest tests instead of PHPUnit?**  
  Type `no` unless you want to use Pest.

### 3. Install Dependencies and Configure Environment
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan db:seed
```
---

## Shadcn
<https://www.shadcn-vue.com/docs/installation/laravel.html>

## Tailwind CSS
<https://www.shadcn-vue.com/docs/installation/vite>

To set up the front-end, run the following commands:
```bash
npm install
npm run dev
```

## Font Awesome

<https://docs.fontawesome.com/web/use-with/vue>

### Install
```sh
npm i --save @fortawesome/vue-fontawesome@latest-3
npm i --save @fortawesome/fontawesome-svg-core
npm i --save @fortawesome/free-solid-svg-icons
npm i --save @fortawesome/free-regular-svg-icons
npm i --save @fortawesome/free-brands-svg-icons
```

### Add in app.js

```js
import './bootstrap';
import { createApp } from 'vue'
import App from './App.vue'
import './assets/main.css'
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
import {library} from "@fortawesome/fontawesome-svg-core";
import {fas} from "@fortawesome/free-solid-svg-icons";

const app = createApp(App)
app.component("FontAwesomeIcon", FontAwesomeIcon)
app.mount('#app')
```

### Add icon

```vue
// import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

<script>
function scrollToTop() {
    window.scrollTo({top: 0, behavior: 'smooth'});
    // window.scrollIntoView({ behavior: 'smooth' })    
    // window.scrollTo(0,0);
}
</script>
<template>    
    <a href="#" @click="scrollToTop"><div class="scrolltop">
        <FontAwesomeIcon icon="chevron-up" />
    </div></a>
</template>
<style>
html {
    scroll-behavior: smooth;
}

.scrolltop {
    color: #fff;
    background: #0099ff;
    position: fixed;
    bottom: 10px;
    right: 10px;
    padding: 15px;    
}
</style>
```

---

## Axios
```
npm install --save axios vue-axios
```
<https://www.npmjs.com/package/vue-axios>


---

## ESLint Prettier
<https://dev.to/devidev/setting-up-eslint-9130-with-prettier-typescript-vuejs-and-vscode-autosave-autoformat-n0
>

To start the Laravel development server, run:

```bash
php artisan serve
```


---

## Project Structure

- **Laravel**: Handles the back-end logic, database migrations, and API routes.
- **Vue.js**: Used for building the front-end components.
- **Inertia.js**: Enables seamless integration between Laravel and Vue.js.
- **Tailwind CSS**: Provides utility-first CSS for styling the application.

---

## Contributing

If you'd like to contribute to this project, please follow these steps:
1. Fork the repository.
2. Create a new branch for your feature or bugfix.
3. Submit a pull request with a detailed description of your changes.

---

## License

This project is open-source and available under the [MIT License](LICENSE).

---

Happy coding! 🚀
