import FooterLogo from "./FooterLogo";
import bgImage from '/public/website-assets/footer/bg.png';
import FooterSwiper from "@/components/Shared/FooterSwiper";
import WebFooter from "./Partials/WebFooter";
import TabletFooter from "./Partials/TabletFooter";
import { Link, usePage } from "@inertiajs/react";

// start lang
import { useTranslation } from "@/contexts/TranslationContext";
import SocialIcons from "./Partials/SocialIcons";
// end lang
export default function Footer({ ShowFooterSwiper }) {

  const { auth: { user } } = usePage().props;

  // Start language
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  // end lang
  const footer_links = usePage().props.footer_links;

  return (
    <>

      {(ShowFooterSwiper && user) && (
        <FooterSwiper />
      )}
      <footer className="  shadow-[0_-4px_10px_rgba(0,0,0,0.1)] ">

        <div className="container mx-auto px-4 flex justify-center py-8">
          <div className="flex flex-col items-center">
            <div className="w-[310px]">
              <Link href={route("react.home")}>
                <FooterLogo />
              </Link>
            </div>
            <p className="text-center max-w-sm max-sm:text-sm">
              {tr["footer_veloura_description"]}
            </p>
          </div>
        </div>


        {/* Social Links */}
        {!!footer_links.show && (
          <div>
            <div className="flex items-center gap-4 max-sm:container max-sm:mx-auto max-sm:px-4">
              {/* Left Line */}
              <div className="flex-1 h-[1px] bg-gray-300"></div>

              {/* Social Icons */}
              <div className="flex-shrink-0">
                <SocialIcons />
              </div>

              {/* Right Line */}
              <div className="flex-1 h-[1px] bg-gray-300"></div>
            </div>
          </div>
        )}

        <WebFooter FooterLogo={FooterLogo} />
        <TabletFooter FooterLogo={FooterLogo} />

        {/* Copyright Bar */}
        <div className="bg-[#0D0D0D] -mb-2">
          <div className="container max-lg:flex-col gap-3 flex justify-between items-center py-4 mx-auto [&_*]:text-white  [&_*]:max-lg:!text-xs [&_*]:lg:!text-sm text-center  ">
            <div className="">©  {tr["veloura_care"]} {new Date().getFullYear()} | {tr['all_rights_reserved']}</div>
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
