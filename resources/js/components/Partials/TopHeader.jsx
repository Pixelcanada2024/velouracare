import { Link, usePage } from "@inertiajs/react";
import React, { useState, useRef, useEffect } from "react"
import NavLink from "@/components/ui/NavLink";
import Button from "../ui/Button";
import Modal from "@/components/ui/Modal";
import SearchComponent from "../Layout/Partials/Search/SearchComponent";
import PreferencesModal from "../layout/Partials/PreferencesModal";

// start lang
import ar from "@/translation/ar"
import en from "@/translation/en"
import { useCart } from "@/contexts/CartItemsContext";
import LogoutModal from "./Partials/LogoutModal";
// end lang

export default function TopHeader() {

  const contact_info = usePage().props.contact_info;
  const links = usePage().props.footer_links;
  const { isSkyAmerica } = usePage().props;
  //  get user total qty
  const { cartData, updateCart } = useCart();
  const userId = usePage().props?.auth?.user?.id ?? null;
  const totalQty = cartData?.find(item => item.user_id === userId)?.total_qty ?? 0;


  const { auth: { user } } = usePage().props;
  const [isAccountDropdownOpen, setIsAccountDropdownOpen] = useState(false);
  const dropdownRef = useRef(null);

  useEffect(() => {
    function handleClickOutside(event) {
      if (dropdownRef.current && !dropdownRef.current.contains(event.target)) {
        setIsAccountDropdownOpen(false);
      }
    }

    document.addEventListener("mousedown", handleClickOutside);
    return () => {
      document.removeEventListener("mousedown", handleClickOutside);
    };
  }, []);


  const [isLogoutModalOpen, setLogoutModalOpen] = useState(false);

  const toggleLogoutModal = () => { setLogoutModalOpen(!isLogoutModalOpen); }

  // Start language
  const preferences = usePage().props.preferences;

  const { csrfToken } = usePage().props;

  const [isPreferencesModalOpen, setPreferencesModalOpen] = useState(false);

  const lang = usePage().props.locale
  const tr = lang === "ar" ? ar : en
  // end lang
  return (
    <div className="flex justify-between items-center bg-[#0D0D0D] text-sm  p-2">
      <div className="container mx-auto flex justify-between items-center">
        {/* <div className="flex justify-between items-center gap-3 max-lg:hidden"> */}
        <div className="flex justify-between items-center gap-3 ">
          {/* <div className="flex justify-between items-center gap-2">
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M4.16701 8.21663C5.76715 11.7005 8.60983 14.4617 12.1387 15.96L12.7053 16.2125C13.3329 16.4919 14.0389 16.5409 14.699 16.3508C15.3591 16.1607 15.9309 15.7437 16.3137 15.1733L17.0545 14.07C17.1704 13.8969 17.2174 13.6868 17.1861 13.4808C17.1547 13.2749 17.0475 13.0882 16.8853 12.9575L14.3753 10.9325C14.2879 10.862 14.1872 10.8099 14.0791 10.7793C13.9711 10.7488 13.858 10.7404 13.7466 10.7546C13.6352 10.7689 13.5279 10.8055 13.431 10.8622C13.3341 10.919 13.2497 10.9948 13.1828 11.085L12.4062 12.1325C10.4125 11.1478 8.79878 9.5338 7.8145 7.53997L8.86117 6.7633C8.95138 6.69643 9.02714 6.61203 9.08391 6.51515C9.14068 6.41827 9.17728 6.31092 9.19152 6.19954C9.20577 6.08817 9.19735 5.97506 9.16679 5.86701C9.13623 5.75897 9.08415 5.65821 9.01367 5.5708L6.98867 3.0608C6.85792 2.89868 6.6712 2.79139 6.46529 2.76008C6.25938 2.72877 6.0492 2.7757 5.87617 2.89163L4.76534 3.63663C4.19151 4.02145 3.77291 4.59748 3.58413 5.2621C3.39535 5.92673 3.44864 6.63679 3.7345 7.2658L4.16701 8.21663Z" fill="#F4F4F4" />
            </svg>
            <span className='font-inter'>{contact_info.contact_phone}</span>
          </div> */}

          {!isSkyAmerica &&
            <>
              <button className='cursor-pointer' onClick={() => setPreferencesModalOpen(true)} dir='ltr'>
                <div className='border border-white rounded-3xl flex items-center justify-between gap-3 py-[2px] px-4 xl:py-1 text-xs sm:text-sm' >
                  <span className='uppercase text-[#F4F4F4] '>{preferences.currency}</span>
                  <div className="w-[2px] h-6 bg-[#FFFFFF33]"></div>
                  <span className='uppercase text-[#F4F4F4]'>{preferences.locale}</span>
                  <div className="w-[2px] h-6 bg-[#FFFFFF33]"></div>
                  <div className='flex items-center gap-2'>
                    <span className='uppercase text-[#F4F4F4]'>{preferences.country}</span>
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M3 7.5H17M3 12.5H17M2.5 10C2.5 10.9849 2.69399 11.9602 3.0709 12.8701C3.44781 13.7801 4.00026 14.6069 4.6967 15.3033C5.39314 15.9997 6.21993 16.5522 7.12987 16.9291C8.03982 17.306 9.01509 17.5 10 17.5C10.9849 17.5 11.9602 17.306 12.8701 16.9291C13.7801 16.5522 14.6069 15.9997 15.3033 15.3033C15.9997 14.6069 16.5522 13.7801 16.9291 12.8701C17.306 11.9602 17.5 10.9849 17.5 10C17.5 8.01088 16.7098 6.10322 15.3033 4.6967C13.8968 3.29018 11.9891 2.5 10 2.5C8.01088 2.5 6.10322 3.29018 4.6967 4.6967C3.29018 6.10322 2.5 8.01088 2.5 10Z" stroke="#F4F4F4" strokeWidth="0.833333" strokeLinecap="round" strokeLinejoin="round" />
                      <path d="M9.58566 2.5C8.18178 4.74968 7.4375 7.34822 7.4375 10C7.4375 12.6518 8.18178 15.2503 9.58566 17.5M10.419 2.5C11.8229 4.74968 12.5672 7.34822 12.5672 10C12.5672 12.6518 11.8229 15.2503 10.419 17.5" stroke="#F4F4F4" strokeWidth="0.833333" strokeLinecap="round" strokeLinejoin="round" />
                    </svg>
                  </div>
                </div>
              </button>
              <div className="w-[2px] h-6 bg-[#FFFFFF33] max-lg:hidden"></div>
            </>
          }


          <div className="flex justify-between items-center gap-2 max-lg:hidden" dir='ltr'>
            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M16.6665 3.33337H3.33317C2.4165 3.33337 1.67484 4.08337 1.67484 5.00004L1.6665 15C1.6665 15.9167 2.4165 16.6667 3.33317 16.6667H16.6665C17.5832 16.6667 18.3332 15.9167 18.3332 15V5.00004C18.3332 4.08337 17.5832 3.33337 16.6665 3.33337ZM16.3332 6.87504L10.4415 10.5584C10.1748 10.725 9.82484 10.725 9.55817 10.5584L3.6665 6.87504C3.58294 6.82813 3.50977 6.76476 3.45141 6.68875C3.39305 6.61275 3.35072 6.52569 3.32698 6.43285C3.30324 6.34001 3.29859 6.24332 3.3133 6.14863C3.32801 6.05394 3.36178 5.96322 3.41257 5.88196C3.46336 5.8007 3.53011 5.73059 3.60878 5.67587C3.68744 5.62115 3.7764 5.58297 3.87025 5.56362C3.9641 5.54428 4.06091 5.54418 4.1548 5.56333C4.24869 5.58248 4.33772 5.62049 4.4165 5.67504L9.99984 9.16671L15.5832 5.67504C15.662 5.62049 15.751 5.58248 15.8449 5.56333C15.9388 5.54418 16.0356 5.54428 16.1294 5.56362C16.2233 5.58297 16.3122 5.62115 16.3909 5.67587C16.4696 5.73059 16.5363 5.8007 16.5871 5.88196C16.6379 5.96322 16.6717 6.05394 16.6864 6.14863C16.7011 6.24332 16.6964 6.34001 16.6727 6.43285C16.649 6.52569 16.6066 6.61275 16.5483 6.68875C16.4899 6.76476 16.4167 6.82813 16.3332 6.87504Z" fill="#F4F4F4" />
            </svg>
            <span className="text-white">{contact_info.contact_email}</span>
          </div>
        </div>

        <div className="flex justify-between items-center gap-3">

          <SearchComponent />

          <div className="w-[2px] h-6 bg-[#FFFFFF33]"></div>

          <div className="flex justify-between items-center gap-2 relative" ref={dropdownRef}>

            {user ?
              <>
                <div
                  className="flex items-center gap-2 cursor-pointer"
                  onClick={() => setIsAccountDropdownOpen(!isAccountDropdownOpen)}
                >
                  <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.0026 3.33337C10.8867 3.33337 11.7345 3.68456 12.3596 4.30968C12.9847 4.93481 13.3359 5.78265 13.3359 6.66671C13.3359 7.55076 12.9847 8.39861 12.3596 9.02373C11.7345 9.64885 10.8867 10 10.0026 10C9.11855 10 8.2707 9.64885 7.64558 9.02373C7.02046 8.39861 6.66927 7.55076 6.66927 6.66671C6.66927 5.78265 7.02046 4.93481 7.64558 4.30968C8.2707 3.68456 9.11855 3.33337 10.0026 3.33337ZM10.0026 11.6667C13.6859 11.6667 16.6693 13.1584 16.6693 15V16.6667H3.33594V15C3.33594 13.1584 6.31927 11.6667 10.0026 11.6667Z" fill="#F4F4F4" />
                  </svg>
                  <span className='max-sm:hidden text-white'>{tr["account"]}</span>
                </div>

                {/* Dropdown Menu */}
                {isAccountDropdownOpen && (
                  <>
                    <div className="absolute w-0 h-0 border-16 border-b-white border-transparent border-b-[16px]  z-5 bottom-[-17px] rtl:left-1 ltr:right-1" />

                    <div className="absolute top-full text-sm mt-2 w-32 bg-white rounded-md shadow-lg  py-2 z-50 font-inter rtl:left-0 ltr:right-0">
                      <Link
                        href={route("react.dashboard")}
                        className="block px-4 py-2 text-sm text-black "
                        onClick={() => setIsAccountDropdownOpen(false)}
                      >
                        {tr["my_account"]}
                      </Link>
                      <Link
                        href={route("react.dashboard.orders")}
                        className="block px-4 py-2 text-sm  text-black"
                        onClick={() => setIsAccountDropdownOpen(false)}
                      >
                        {tr["my_orders"]}
                      </Link>
                      <button
                        onClick={toggleLogoutModal}
                        className={"block w-full  px-4 py-2 text-sm   text-black hover:text-secondary-500 transition-colors  cursor-pointer" + (lang == "ar" ? " text-right " : " text-left ")}
                      >
                        {tr["logout"]}
                      </button>
                    </div>
                  </>
                )}
              </>
              :
              <>
                <Link href={route("register")}>
                  <div className='flex justify-between items-center gap-2'>
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M8.88932 3.33337C9.77338 3.33337 10.6212 3.68456 11.2463 4.30968C11.8715 4.93481 12.2227 5.78265 12.2227 6.66671C12.2227 7.55076 11.8715 8.39861 11.2463 9.02373C10.6212 9.64885 9.77338 10 8.88932 10C8.00527 10 7.15742 9.64885 6.5323 9.02373C5.90718 8.39861 5.55599 7.55076 5.55599 6.66671C5.55599 5.78265 5.90718 4.93481 6.5323 4.30968C7.15742 3.68456 8.00527 3.33337 8.88932 3.33337ZM8.88932 11.6667C12.5727 11.6667 15.556 13.1584 15.556 15V16.6667H2.22266V15C2.22266 13.1584 5.20599 11.6667 8.88932 11.6667Z" fill="#F4F4F4" />
                      <path d="M16.666 6.66663V11.1111" stroke="#F4F4F4" strokeWidth="0.888889" strokeLinecap="round" />
                      <path d="M14.4443 8.88879H18.8888" stroke="#F4F4F4" strokeWidth="0.888889" strokeLinecap="round" />
                    </svg>
                    <span className='max-sm:hidden text-white'>{tr["become_a_customer"]}</span>
                  </div>
                </Link>
              </>
            }



          </div>
          <div className="w-[2px] h-6 bg-[#FFFFFF33]"></div>

          <div className="flex justify-between items-center gap-2">
            {user ? (<>
              <>
                <Link href={route("react.cart")} >
                  <div className="flex items-center gap-2 cursor-pointer">
                    <div className="relative">
                      <span className={"absolute bg-white text-[9px] font-bold text-[#0D0D0D] rounded-full grid place-items-center " +
                        (totalQty > 99 ? " w-6 h-6 -top-4 -right-4 " : (totalQty > 9 ? " w-5 h-5 -top-3 -right-3 " : " w-4 h-4 -top-2 -right-2 "))}>
                        {totalQty}
                      </span>
                      <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clipPath="url(#clip0_447_1254)">
                          <path d="M6.66732 18.3334C7.12756 18.3334 7.50065 17.9603 7.50065 17.5001C7.50065 17.0398 7.12756 16.6667 6.66732 16.6667C6.20708 16.6667 5.83398 17.0398 5.83398 17.5001C5.83398 17.9603 6.20708 18.3334 6.66732 18.3334Z" stroke="#F4F4F4" strokeWidth="1.66667" strokeLinecap="round" strokeLinejoin="round" />
                          <path d="M15.8333 18.3334C16.2936 18.3334 16.6667 17.9603 16.6667 17.5001C16.6667 17.0398 16.2936 16.6667 15.8333 16.6667C15.3731 16.6667 15 17.0398 15 17.5001C15 17.9603 15.3731 18.3334 15.8333 18.3334Z" stroke="#F4F4F4" strokeWidth="1.66667" strokeLinecap="round" strokeLinejoin="round" />
                          <path d="M1.70898 1.70837H3.37565L5.59232 12.0584C5.67363 12.4374 5.88454 12.7763 6.18874 13.0166C6.49294 13.2569 6.87141 13.3837 7.25898 13.375H15.409C15.7883 13.3744 16.1561 13.2444 16.4515 13.0066C16.747 12.7687 16.9524 12.4371 17.034 12.0667L18.409 5.87504H4.26732" stroke="#F4F4F4" strokeWidth="1.66667" strokeLinecap="round" strokeLinejoin="round" />
                        </g>
                        <defs>
                          <clipPath id="clip0_447_1254">
                            <rect width="20" height="20" fill="white" />
                          </clipPath>
                        </defs>
                      </svg>
                    </div>
                    <span className='max-sm:hidden text-white'>{tr["cart"]}</span>
                  </div>
                </Link>
              </>
            </>) : (
              <>
                <Link href={route("login")}>
                  <div className="flex items-center gap-2 cursor-pointer">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M10.8333 17.5C10.5972 17.5 10.3994 17.42 10.24 17.26C10.0806 17.1 10.0006 16.9022 10 16.6667C9.99945 16.4311 10.0794 16.2333 10.24 16.0733C10.4006 15.9133 10.5983 15.8333 10.8333 15.8333H15.8333V4.16667H10.8333C10.5972 4.16667 10.3994 4.08667 10.24 3.92667C10.0806 3.76667 10.0006 3.56889 10 3.33333C9.99945 3.09778 10.0794 2.9 10.24 2.74C10.4006 2.58 10.5983 2.5 10.8333 2.5H15.8333C16.2917 2.5 16.6842 2.66333 17.0108 2.99C17.3375 3.31667 17.5006 3.70889 17.5 4.16667V15.8333C17.5 16.2917 17.3369 16.6842 17.0108 17.0108C16.6847 17.3375 16.2922 17.5006 15.8333 17.5H10.8333ZM9.3125 10.8333H3.33334C3.09723 10.8333 2.89945 10.7533 2.74 10.5933C2.58056 10.4333 2.50056 10.2356 2.5 10C2.49945 9.76444 2.57945 9.56667 2.74 9.40667C2.90056 9.24667 3.09834 9.16667 3.33334 9.16667H9.3125L7.75 7.60417C7.59722 7.45139 7.52084 7.26389 7.52084 7.04167C7.52084 6.81944 7.59722 6.625 7.75 6.45833C7.90278 6.29167 8.09722 6.20472 8.33334 6.1975C8.56945 6.19028 8.77084 6.27028 8.9375 6.4375L11.9167 9.41667C12.0833 9.58333 12.1667 9.77778 12.1667 10C12.1667 10.2222 12.0833 10.4167 11.9167 10.5833L8.9375 13.5625C8.77084 13.7292 8.57306 13.8092 8.34417 13.8025C8.11528 13.7958 7.91722 13.7089 7.75 13.5417C7.59722 13.375 7.52445 13.1772 7.53167 12.9483C7.53889 12.7194 7.61861 12.5283 7.77084 12.375L9.3125 10.8333Z" fill="#F4F4F4" />
                    </svg>

                    <span className='max-sm:hidden text-white'>{tr["login"]}</span>
                  </div>
                </Link>
              </>
            )}
          </div>

        </div>
      </div>


      <LogoutModal isModalOpen={isLogoutModalOpen} setModalOpen={setLogoutModalOpen} toggleModal={toggleLogoutModal} />

      {/* Language Modal */}
      {!isSkyAmerica && <PreferencesModal
        isOpen={isPreferencesModalOpen}
        onClose={() => setPreferencesModalOpen(false)}
      />}

    </div>
  )
}
