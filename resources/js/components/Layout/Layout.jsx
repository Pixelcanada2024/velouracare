import Footer from "../../components/Partials/Footer";
import Header from "../../components/Partials/Header";
import { Head, usePage } from "@inertiajs/react";
import Modals from "./Partials/Modals";
import { useCart } from "@/contexts/CartItemsContext";
import { useEffect } from "react";
// start lang
import ar from "@/translation/ar"
import en from "@/translation/en"
import { useTranslation } from "@/contexts/TranslationContext";
import { initGA, trackPageView } from "@/utils/ga";
// end lang

const MEASUREMENT_ID = import.meta.env.VITE_GA_ID || "";

export default function Layout({ children, ShowFooterSwiper = true, breadcrumbs, pageTitle, hasTitleHeader = false }) {

  const meta = usePage().props.meta;
  const appName = meta.website_name;
  const { showAddedSuccessMsgState } = useCart();
  const [ showAddedSuccessMsg ] = showAddedSuccessMsgState;

  // Start language & currency
  const lang = usePage().props.locale;
  const tr = lang === "ar" ? ar : en;
  const currencySymbol = usePage().props?.currency ?? "SAR";
  const currency = tr[currencySymbol] || currencySymbol;
  const [_, setTranslation] = useTranslation();
  // end lang

  const { url } = usePage();

  useEffect(() => {
    initGA(MEASUREMENT_ID);
    
    const successMsg = document.querySelector(".success-msg-modal");
    let timer;
    
    if (successMsg) {
      successMsg.classList.add("hidden");
      timer = setTimeout(() => {
        successMsg.classList.remove("hidden");
      }, 5000);
    }

    return () => clearTimeout(timer);
  }, []);

  useEffect(() => {
    // Track SPA navigation
    trackPageView(url, MEASUREMENT_ID);
  }, [url]);

  useEffect(() => {
    setTranslation({
      lang: lang,
      tr: tr,
      currency: currency
    });
  }, [currency]);

  return (
    <div  className={lang == "ar" ? "custom-font-tajawal" : "custom-font-inter"} dir={lang == "ar" ? "rtl" : "ltr"}>
      <Head title={pageTitle ? ` ${appName} | ${pageTitle}` : appName} >

        {meta?.meta_description && (
          <meta name="description" content={meta.meta_description} />
        )}
        {meta?.meta_keywords && (
          <meta name="keywords" content={meta.meta_keywords} />
        )}
        {meta?.meta_image && (
          <meta property="og:image" content={meta.meta_image} />
        )}

        {meta?.site_icon && (
          <link rel="icon" href={meta.site_icon} type="image/png" />
        )}

      </Head>

      {/* Header */}
      <Header pageTitle={pageTitle} breadcrumbs={breadcrumbs} hasTitleHeader={hasTitleHeader} />

      {/* Main Content */}
      <main className="flex-1 min-h-[50vh]">
        {children}
      </main>

      {/* Footer */}
      <Footer ShowFooterSwiper={ShowFooterSwiper} />

      {/* It will be displayed also when a stock check happened via code  */}
      {showAddedSuccessMsg && <div className="fixed top-10 left-10 bg-green-600 text-white py-2 px-4 rounded animate-bounce z-100 success-msg-modal">{tr["cart_updated_successfully"]}</div>}
      <div id='layout-success-msg' className=' p-5 bg-green-300 fixed top-10 left-1/2 -translate-x-1/2 z-150 animate-bounce max-w-120 text-center rounded-xl ' style={{ display: "none" }}></div>

      {/* All Site Modals */}
      <Modals />


    </div>
  );
}
