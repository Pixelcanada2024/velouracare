import axios from 'axios';
// Import Tailwind CSS
import '../css/tailwind.css';
import { createInertiaApp } from '@inertiajs/react'
import { createRoot } from 'react-dom/client'
import { CartItemsProvider } from './contexts/CartItemsContext';
import { TranslationProvider } from './contexts/TranslationContext';
// axios
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (token) {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
}
// create app
createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.jsx', { eager: true })
    return pages[`./Pages/${name}.jsx`]
  },
  setup({ el, App, props }) {
    createRoot(el).render(
      <CartItemsProvider>
        <TranslationProvider>
          <App {...props} />
        </TranslationProvider>
      </CartItemsProvider>
    )
  },
})

