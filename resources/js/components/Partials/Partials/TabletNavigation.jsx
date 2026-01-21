import NavLink from "@/components/ui/NavLink"
import { usePage } from "@inertiajs/react";
import React, { useState } from "react"
import FooterLogo from "../FooterLogo";
import Promotions from "./../../../Pages/Promotions/Promotions";
import LogoutModal from "./LogoutModal";
import { useTranslation } from "@/contexts/TranslationContext";

export default function TabletNavigation() {
  // Start language
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  // end lang
  const { csrfToken } = usePage().props;

  const {
    auth: { user },
  } = usePage().props;
  const { homeCategories } = usePage().props;
  // Mobile menu state
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);

  // Categories submenu state
  const [isCategoriesMenuOpen, setIsCategoriesMenuOpen] = useState(false);

  // Account submenu state
  const [isAccountMenuOpen, setIsAccountMenuOpen] = useState(false);

  const toggleMobileMenu = () => {
    setIsMobileMenuOpen(!isMobileMenuOpen);
    if (isMobileMenuOpen) {
      setIsCategoriesMenuOpen(false);
      setIsAccountMenuOpen(false);
    }
  };

  const toggleCategoriesMenu = () => {
    setIsCategoriesMenuOpen(!isCategoriesMenuOpen);
  };

  const toggleAccountMenu = () => {
    setIsAccountMenuOpen(!isAccountMenuOpen);
  };

  const [isLogoutModalOpen, setLogoutModalOpen] = useState(false);
  const toggleLogoutModal = () => { setLogoutModalOpen(!isLogoutModalOpen); }

  return (
    <>
      <LogoutModal isModalOpen={isLogoutModalOpen} setModalOpen={setLogoutModalOpen} toggleModal={toggleLogoutModal} />

      <button
        onClick={() => setIsMobileMenuOpen(true)}
        className="xl:hidden cursor-pointer"
      >
        <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
          <rect x="0.5" y="0.5" width="31" height="31" rx="15.5" stroke="#0D0D0D" />
          <path d="M8.5 21V19.3333H23.5V21H8.5ZM8.5 16.8333V15.1667H23.5V16.8333H8.5ZM8.5 12.6667V11H23.5V12.6667H8.5Z" fill="#0D0D0D" />
        </svg>

      </button>

      <div
        className={`xl:hidden fixed inset-0 z-45 text-[#222222] bg-white transition-all duration-300 ease-in-out ${isMobileMenuOpen
          ? "opacity-100 visible"
          : "opacity-0 invisible"
          }`}
      >
        <nav className="w-full h-full overflow-y-auto">
          {/* Close button, Menu button */}

          <div className="flex justify-between items-center bg-[#0D0D0D] px-6 py-3 ">

            {/* Menu button */}

            <button
              onClick={toggleMobileMenu}
              className="flex items-center justify-center gap-3  cursor-pointer"
              aria-label="Toggle mobile menu"
            >
              <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3.25 19.5V17.3333H22.75V19.5H3.25ZM3.25 14.0833V11.9167H22.75V14.0833H3.25ZM3.25 8.66667V6.5H22.75V8.66667H3.25Z" fill="white" />
              </svg>
              <span className="text-[20px] sm:text-[24px] font-medium text-white uppercase">
                {tr["menu"]}
              </span>
            </button>

            {/* Close button */}
            <button
              onClick={toggleMobileMenu}
              className="flex items-center justify-center gap-3 text-white transition-colors hover:text-secondary-500 cursor-pointer w-[32px] h-[32px]"
              aria-label="Close mobile menu"
            >
              <svg className="w-full h-full" viewBox="0 0 49 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="49" height="49" fill="white" />
                <path d="M16 31L34 13M34 31L16 13" stroke="#0D0D0D" strokeWidth="2" strokeMiterlimit="10" strokeLinecap="round" strokeLinejoin="round" />
              </svg>
            </button>

          </div>

          <div className='flex flex-col h-[90vh] justify-between'>

            {/* Links */}
            <ul className="flex flex-col">
              <li className='border-b border-[#CECECE] py-3 uppercase'>
                <NavLink href={route("react.home")}
                  active={route().current("react.home") || route().current("home")}
                  className="px-5 ">{tr["home"]}</NavLink>
              </li>
              <li className='border-b border-[#CECECE] uppercase'>
                <button
                  onClick={toggleCategoriesMenu}
                  className={" uppercase px-5 w-full text-left flex items-center justify-between py-3 cursor-pointer " + (isCategoriesMenuOpen ? " bg-[#F2F4F7] " : " ")}
                >
                  {tr["categories"]}
                  <svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg" className={` ${isCategoriesMenuOpen ? "rotate-180" : ""}`}>
                    <path d="M1.25 1.5L5 5.25L8.75 1.5" stroke="#222222" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                  </svg>

                </button>
                <div className={`px-8 space-y-2 mt-2 uppercase text-[#7C8091] ${isCategoriesMenuOpen ? "block" : "hidden"}`}>
                  {homeCategories.map(cat => (
                    <NavLink key={cat.id} active={usePage().props.currentFullUrl == encodeURI(route("react.products") + "?categories[0]=" + cat.name)} href={route("react.products") + "?categories[]=" + cat.name} className="block py-1">{cat.name}</NavLink>
                  ))}
                </div>
              </li>
              <li className='border-b border-[#CECECE] py-3'>
                <NavLink href={route("react.brands")}
                  active={route().current("react.brands")} className="px-5 uppercase ">{tr["brands"]}</NavLink>
              </li>
              <li className='border-b border-[#CECECE] py-3'>
                <NavLink href={route("react.promotions")}
                  active={route().current("react.promotions")} className="px-5 uppercase">{tr["promotions"]}</NavLink>
              </li>
              <li className='border-b border-[#CECECE] py-3'>
                <NavLink href={route("react.cart")}
                  active={route().current("react.cart")} className="px-5 uppercase">{tr["cart"]}</NavLink>
              </li>
              <li className='border-b border-[#CECECE] py-3'>
                <NavLink href={route("react.about-us")}
                  active={route().current("react.about-us")} className="px-5 uppercase">{tr["about_us"]}</NavLink>
              </li>
              <li className='border-b border-[#CECECE] py-3'>
                <NavLink href={route("blog")}
                  active={route().current("blog")} className="px-5 uppercase">{tr["blog"]}</NavLink>
              </li>
              <li className='border-b border-[#CECECE] py-3'>
                <NavLink href={route("react.contact-us")}
                  active={route().current("react.contact-us")} className="px-5 uppercase">{tr["contact_us"]}</NavLink>
              </li>
              <li className='border-b border-[#CECECE] py-3'>
                <NavLink href={route("react.faqs")}
                  active={route().current("react.faqs")} className="px-5 uppercase">{tr["faqs"]}</NavLink>
              </li>
              {user ? (

                <li className='border-b border-[#CECECE] uppercase'>
                  <button
                    onClick={toggleAccountMenu}
                    className={" uppercase px-5 w-full text-left flex items-center justify-between py-3 cursor-pointer " + (isAccountMenuOpen ? " bg-[#F2F4F7] " : " ")}
                  >
                    {tr["account"]}
                    <svg width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg" className={` ${isAccountMenuOpen ? "rotate-180" : ""}`}>
                      <path d="M1.25 1.5L5 5.25L8.75 1.5" stroke="#222222" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                    </svg>

                  </button>
                  <div className={`px-2 gap-4 my-4  uppercase text-[#7C8091] ${isAccountMenuOpen ? "flex flex-col" : "hidden"}`}>
                    <NavLink href={route("react.dashboard")} active={route().current("react.dashboard")} className="px-5 uppercase">{tr["my_account"]}</NavLink>
                    <NavLink href={route("react.dashboard.orders")} active={route().current("react.dashboard.orders")} className="px-5 uppercase">{tr["my_orders"]}</NavLink>
                    <button
                      onClick={toggleLogoutModal}
                      className={"text-[15px] sm:text-base px-5 uppercase  cursor-pointer" + (lang == "ar" ? " text-right " : " text-left ")}
                    >
                      {tr["logout"]}
                    </button>
                  </div>
                </li>

              ) : (
                <>
                  <li className='border-b border-[#CECECE] py-3 uppercase' >
                    <NavLink
                      active={route().current("register")}
                      href={route("register")}
                      className="px-5 "
                    >
                      {tr["become_a_customer"]}
                    </NavLink>
                  </li>
                  <li className='border-b border-[#CECECE] py-3 uppercase'>
                    <NavLink
                      active={route().current("login")}
                      href={route("login")}
                      className="px-5 ">
                      {tr["login"]}
                    </NavLink>
                  </li>
                </>
              )}
            </ul>

          </div>

        </nav >
      </div >

    </>
  )
}
