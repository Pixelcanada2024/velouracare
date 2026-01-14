import NavLink from "@/components/ui/NavLink"
import React from "react"
import SocialIcons from "./SocialIcons"
import { usePage } from "@inertiajs/react";
// start lang
import { useTranslation } from "@/contexts/TranslationContext";
// end lang
export default function WebFooter({ FooterLogo }) {
  const contact_info = usePage().props.contact_info;
  const { csrfToken } = usePage().props;

  // Start language
  const [{lang, currency, tr}, _setTranslation] = useTranslation();
  // end lang

  const meta = usePage().props.meta;
  const footer_qr_code = meta.footer_qr_code;
  const footer_links = usePage().props.footer_links;

  return (
    <div className="container px-4 py-8 mx-auto xl:py-16 max-xl:hidden z-50">
      {/* Website */}
      <div className="hidden xl:gap-5 xl:flex-row xl:flex xl:flex-wrap xl:justify-between ">

        {/* Logo & Description | First Col*/}
        <div className="w-[350px] space-y-5 relative -top-4">

          <div className="w-[297px]">
            <NavLink className='w-full' href={route("react.home")}>
              <FooterLogo />
            </NavLink>
          </div>

          <p className=" text-[15px] sm:text-base text-[#333333]">
            {tr["footer_company_info"]}
          </p>

          <div className="mt-5">
            <h2 className='text-base font-bold uppercase'> {tr["contact_us"]}: {" "}</h2>
            <div className="mt-2 -mx-1">
              <a href={footer_links.whatsapp_link} target="_blank" rel="noopener noreferrer">
                <img src={footer_qr_code} alt="QR Code" className="w-48 h-48" />
              </a>
            </div>
          </div>



        </div>

        {/* Quick Links | Second Col */}
        <div className='relative'>
          <h3 className="mb-4 font-bold text-xl" style={{ fontFamily: "Times New Roman" }}>
            {tr["quick_links"]}
          </h3>
          <ul className="space-y-4 text-sm ">
            <li>
              <NavLink href={route("react.home")}
                active={route().current("react.home") || route().current("home")} className="text-[#333333]">{tr["home"]}</NavLink>
            </li>
            <li>
              <NavLink active={usePage().props.currentFullUrl == encodeURI(route("react.products") + "?categories[0]=skin care")} href={route("react.products") + "?categories[]=skin care"} className="text-[#333333]">{tr["skin_care"]}</NavLink>
            </li>
            <li>
              <NavLink active={usePage().props.currentFullUrl == encodeURI(route("react.products") + "?categories[0]=health care")} href={route("react.products") + "?categories[]=health care"} className="text-[#333333]">{tr["health_care"]}</NavLink>
            </li>
            <li>
              <NavLink active={usePage().props.currentFullUrl == encodeURI(route("react.products") + "?categories[0]=hair care")} href={route("react.products") + "?categories[]=hair care"} className="text-[#333333]">{tr["hair_care"]}</NavLink>
            </li>
            <li>
              <NavLink active={usePage().props.currentFullUrl == encodeURI(route("react.products") + "?categories[0]=makeup")} href={route("react.products") + "?categories[]=makeup"} className="text-[#333333]">{tr["makeup"]}</NavLink>
            </li>
            <li>
              <NavLink href={route("blog")}
                active={route().current("blog")} className="text-[#333333]">{tr["blog"]}</NavLink>
            </li>

          </ul>
          {/* Social Links */}
          { !!footer_links.show && <div className='mt-12 space-y-4 absolute'>
            <h2 className='text-base font-bold uppercase'> {tr["follow_us_on_social_media"]}</h2>
            <SocialIcons />
          </div>}
        </div>


        {/* Other Links | Third Col*/}
        <div>
          <h3 className="mb-4 font-bold text-xl" style={{ fontFamily: "Times New Roman" }}>
            {tr["other_links"]}
          </h3>

          <ul className="space-y-4 text-sm ">
            <li>
              <NavLink href={route("react.about-us")}
                active={route().current("react.about-us")} className="text-[#333333]">{tr["about_us"]}</NavLink>
            </li>
            <li>
              <NavLink href={route("react.promotions")} active={route().current("react.promotions")} className="text-[#333333]">{tr["promotions"]}</NavLink>
            </li>
            <li>
              <NavLink href={route("react.faqs")} active={route().current("react.faqs")} className="text-[#333333]">{tr["faqs"]}</NavLink>
            </li>
            <li>
              <NavLink href={route("custom-pages.show_custom_page", { slug: "terms" })} active={route().current("custom-pages.show_custom_page", { slug: "terms" })} className="text-[#333333]">{tr["terms_conditions"]}</NavLink>
            </li>
            <li>
              <NavLink href={route("custom-pages.show_custom_page", { slug: "privacy-policy" })} active={route().current("custom-pages.show_custom_page", { slug: "privacy-policy" })} className="text-[#333333]">{tr["privacy_policy"]}</NavLink>
            </li>
            <li>
              <NavLink href={route("custom-pages.show_custom_page", { slug: "return-policy" })} active={route().current("custom-pages.show_custom_page", { slug: "return-policy" })} className="text-[#333333]">{tr["return_policy"]}</NavLink>
            </li>
          </ul>
        </div>

        {/*Newsletter | Fourth Col*/}
        <div className="w-[380px]">
          <div>
            <h3 className="mb-4 font-bold text-xl" style={{ fontFamily: "Times New Roman" }}>
              {tr["newsletter"]}
            </h3>

            <p className="mb-10 10 text-[15px] sm:text-base text-[#333] ">
              {tr["sub_newsletter"]}
            </p>
            <form method="POST" action={route("subscribers.store")}>
              <input
                type="hidden"
                name="_token"
                value={csrfToken}
              />
              <div className="relative">
                <input
                  type="email"
                  name="email"
                  required
                  placeholder={tr["your_mail_address"]}
                  className="w-full px-4 py-[11px] sm:py-[12px] text-gray-600 bg-white border-[1.5px] border-[#E6E6E6] rounded-full focus:outline-none"
                />
                <span className={
                  "absolute p-2 -translate-y-1/2 rounded-md top-1/2 " +
                  (lang == "ar" ? "left-2 rotate-270" : "right-2")
                } >
                  <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M16.25 0L0.25 9L5.35938 10.8906L13.25 3.5L7.25 11.5938V16L10.125 12.6562L13.75 14L16.25 0Z" fill="#004AAD" />
                  </svg>
                </span>
              </div>
              <button className="text-sm sm:text-base cursor-pointer w-full mt-4 font-bold text-white px-4 py-[12px] sm:py-[13px] bg-[#0D0D0D] border border-[#555555] rounded-full focus:outline-none uppercase">
                {tr["subscribe_now"]}
              </button>
            </form>

          </div>
        </div>

      </div>

    </div>
  )
}
