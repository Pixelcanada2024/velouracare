import React from "react"
import NavLink from "@/components/ui/NavLink"
import FooterAccordion from "@/components/ui/FooterAccordion";
import FooterAccordionGroup from "@/components/ui/FooterAccordionGroup";
import SocialIcons from "./SocialIcons";
import { usePage } from "@inertiajs/react";
// start lang
import { useTranslation } from "@/contexts/TranslationContext";
// end lang

export default function TabletFooter({ FooterLogo }) {
  // Start language
  const [{lang, currency, tr}, _setTranslation] = useTranslation();
  // end lang
  const contact_info = usePage().props.contact_info;
  const { csrfToken } = usePage().props;
  const footer_links = usePage().props.footer_links;

  const meta = usePage().props.meta;
  const footer_qr_code = meta.footer_qr_code;

  return (
    <div className="p-6 xl:hidden z-50">
      {/* Website */}
      <div className="flex-wrap justify-between gap-24 xl:flex-row xl:flex">

        {/* Accordion */}
        <div className="my-4 sm:my-8 ">
          <FooterAccordionGroup>
            <FooterAccordion title={tr["quick_links"]}>
              <ul className="flex max-sm:flex-col sm:flex-wrap gap-2 sm:gap-5 text-sm ">
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
            </FooterAccordion>
            <FooterAccordion title={tr["other_links"]}>

              <ul className="flex max-sm:flex-col sm:flex-wrap gap-2 sm:gap-5 text-sm ">
                <li>
                  <NavLink href={route("react.about-us")}
                    active={route().current("react.about-us")} className="text-[#333333]">{tr["about_us"]}</NavLink>
                </li>
                <li>
                  <NavLink href={route("react.promotions")}
                    active={route().current("react.promotions")} className="text-[#333333]">{tr["promotions"]}</NavLink>
                </li>
                <li>
                  <NavLink href={route("react.faqs")}
                    active={route().current("react.faqs")} className="text-[#333333]">{tr["faqs"]}</NavLink>
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
            </FooterAccordion>
          </FooterAccordionGroup>
        </div>


        {/*Newsletter && Call*/}
        <div className='flex max-sm:flex-col  gap-4 '>
          {/* Newsletter */}
          <div className='sm:w-[50%]  max-sm:pb-5'>
            <h3 className="mb-4 font-bold sm:text-lg xl:text-[20px]  uppercase" style={{ fontFamily: "Times New Roman" }}>
              {tr["newsletter"]}
            </h3>

            <p className="mb-6 sm:mb-10 text-[15px] sm:text-base text-[#333]">
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
                    <path d="M16.25 0L0.25 9L5.35938 10.8906L13.25 3.5L7.25 11.5938V16L10.125 12.6562L13.75 14L16.25 0Z" fill="#0D0D0D" />
                  </svg>
                </span>
              </div>
              <button className="text-sm sm:text-base cursor-pointer w-full mt-3 font-bold text-white px-4 py-[12px] sm:py-[13px] bg-[#0D0D0D] border border-[#555555] rounded-full focus:outline-none uppercase">
                {tr["subscribe_now"]}
              </button>
            </form>
          </div>

          <div className="md:w-[5%] xl:hidden"></div>

          <div className='max-sm:hidden max-sm:w-full max-sm:h-1 sm:w-[1px] bg-[#CECECE] self-stretch '></div>

          {/* Call QR code */}
          <div className='sm:w-[40%] space-y-4 '>

            <div className="max-sm:flex max-sm:justify-center">
              <div>
                <h2 className='text-lg font-bold uppercase max-sm:text-center'> {tr["footer_contact_us"]}: {" "}</h2>
                <div className="mt-2 -mx-1">
                  <a href={footer_links.whatsapp_link} target="_blank" rel="noopener noreferrer">
                    <img src={footer_qr_code} alt="QR Code" className="w-[196px] h-[196px] lg:p-10" />
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>


      </div>

    </div>
  )
}
