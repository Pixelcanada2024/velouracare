

import { useTranslation } from "@/contexts/TranslationContext";
import Button from "../ui/Button";
// start lang
import { Link, usePage } from "@inertiajs/react";
// end lang

const CookieVerificationModal = ({ isOpen, onClose }) => {
  if (!isOpen) return null;

  const confirmVerification = (value) => {
    localStorage.setItem("CookieVerification", value);
    if (onClose) onClose();
  };

  // Start language
  const [{lang, currency, tr}, _setTranslation] = useTranslation();
  // end lang
  return (
      <div className="max-lg:w-screen max-lg:-bottom-2 max-lg:pb-6 lg:w-225 lg:bottom-5 lg:ltr:left-5 lg:rtl:right-5  max-w-[100vw] bg-white shadow-[0_0_5px_5px_rgba(0,0,0,0.3)] rounded-2xl   py-4 px-8 fixed text-justify z-200">
        <button
          className="cursor-pointer absolute top-4 rtl:left-4 ltr:right-4 text-xl sm:text-3xl text-gray-400 hover:text-gray-600 focus:outline-none"
          aria-label="Close"
          onClick={onClose}
        >
          <svg width="23" height="23" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M17.0002 17L26.9168 26.9167M17.0002 17L7.0835 7.08337M17.0002 17L7.0835 26.9167M17.0002 17L26.9168 7.08337" stroke="black" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        </button>
        <h2 className="text-lg sm:text-xl lg:text-2xl font-bold mb-4 text-gray-900">{tr["cookies_settings"]}</h2>
        <div className="[&_*]:text-xs [&_*]:sm:text-sm [&_*]:lg:text-base flex max-sm:flex-col sm:gap-5">
          <div className="">
            <p className="mb-4 text-gray-700 ">
              {tr["cookie_desc_line_1"]}
            </p>
            <p className="mb-4 text-gray-700 ">
              {tr["cookie_desc_line_2"]} <Link href="#" className="text-blue-500 hover:underline">{tr["cookie_privacy_policy"]}</Link>
            </p>
          </div>
          <div className="gap-4 [&_button]:font-medium [&_button]:sm:w-36 [&_button]:sm:p-2 flex sm:flex-col ">
            <Button
              onClick={() => confirmVerification("allow_all")}
              fullWidth
            >
              {tr["allow_all"]}
            </Button>
            <Button
              onClick={() => confirmVerification("reject_all")}
              fullWidth
              variant="outline"
              className="!border-[#CECECE]"
            >
              {tr["reject_all"]}
            </Button>
          </div>
        </div>
      </div>
  );
};

export default CookieVerificationModal;
