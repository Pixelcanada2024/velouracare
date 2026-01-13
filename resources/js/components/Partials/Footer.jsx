import FooterLogo from "./FooterLogo";
import bgImage from '/public/website-assets/footer/bg.png';
import FooterSwiper from "@/components/Shared/FooterSwiper";
import WebFooter from "./Partials/WebFooter";
import TabletFooter from "./Partials/TabletFooter";
import { Link, usePage } from "@inertiajs/react";

// start lang
import { useTranslation } from "@/contexts/TranslationContext";
// end lang
export default function Footer({ ShowFooterSwiper }) {

  const { auth: { user } } = usePage().props;

  // Start language
  const [{lang, currency, tr}, _setTranslation] = useTranslation();
  // end lang
  
  return (
    <>

      {(ShowFooterSwiper && user) && (
        <FooterSwiper />
      )}
      <footer className="  shadow-[0_-4px_10px_rgba(0,0,0,0.1)] ">

        <div className=" relative">
          <WebFooter FooterLogo={FooterLogo} />
          <TabletFooter FooterLogo={FooterLogo} />
        </div>

        {/* Copyright Bar */}
        <div className="bg-[#004AAD] -mb-2">
          <div className="container max-lg:flex-col gap-3 flex justify-between items-center py-4 mx-auto [&_*]:text-white  [&_*]:max-lg:!text-xs [&_*]:lg:!text-sm text-center  ">
            <div className="">©  Sky Business Trade {new Date().getFullYear()} | {tr['all_rights_reserved']}</div>
            <div>
              <ul className="flex gap-8 items-center">
                <li>
                  <Link href={route('custom-pages.show_custom_page', { slug: 'terms' })} className="hover:underline text-xs lg:text-sm">{tr['terms_conditions']}</Link>
                </li>
                <li>
                  <Link href={route('custom-pages.show_custom_page', { slug: 'privacy-policy' })} className="hover:underline text-xs lg:text-sm">{tr['privacy_policy']}</Link>
                </li>
                <li>
                  <Link href={route('react.contact-us')} className="hover:underline text-xs lg:text-sm">{tr['footer_contact_us']}</Link>
                </li>
              </ul>
            </div>
          </div>
        </div>

      </footer>
    </>
  );
}
