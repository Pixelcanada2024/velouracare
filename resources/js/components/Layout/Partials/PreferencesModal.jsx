
import { useState } from "react";
import Button from "@/components/ui/Button";
import PrimarySelect from "@/components/ui/PrimarySelect";
import PaymentMethods from "./Partials/PaymentMethods";

import Preferences from "@/components/Data/Preferences";
import { router, usePage } from "@inertiajs/react";
import axios from "axios";
// start lang
import ar from "@/translation/ar"
import en from "@/translation/en"
import { ModalCloseIcon } from "@/components/ui/ModalContent/Partials/ModalsIcons";
// end lang

export default function PreferencesModal({ isOpen, onClose }) {

  const {isSkyAmerica} = usePage().props;
  if ( isSkyAmerica ) return ;

  // start lang
  const lang = usePage().props.locale
  const tr = lang === "ar" ? ar : en

  const savedPreferences = usePage().props.preferences;

  const { gulfCountries, languages, currencies } = Preferences;

  const [country, setCountry] = useState(savedPreferences.country || "sa");
  const [language, setLanguage] = useState(savedPreferences.locale || "ar");
  const [currency, setCurrency] = useState(savedPreferences.currency || "SAR");

  if (!isOpen) return null;

  const changeLocale = (locale, currency, country) => {

    const data = { locale, currency, country }
    axios.post(route("change.locale"), data)
      .then(response => {
        if ( response.data.success ) {
          if ( response.data.redirectUrl !== null && response.data.env !== "local" ) {
            window.location.href = response.data.redirectUrl
          } else {
            window.location.reload();
          }
        }
      })
      .catch(error => {
        console.error(error.response.data.error);
      });
  };
  // end lang


  return (
    <div
      onClick={onClose}
      className="fixed inset-0 z-200 flex items-center justify-center bg-black/80 backdrop-blur-xs font-inter text-black"
    >
      <div
        onClick={(e) => e.stopPropagation()}
        className="relative w-full max-w-lg m-4 bg-white shadow-lg rounded-2xl p-4"
      >
        <div className='flex items-center justify-between mb-4'>
          <h2 className="text-lg sm:text-[22px] xl:text-2xl font-semibold ">{tr["site_preferences"]}</h2>

          <button
            onClick={onClose}
            className=" text-gray-500 cursor-pointer hover:text-gray-700"
            aria-label="Close"
          >
            <ModalCloseIcon />
          </button>

        </div>
        {/* Form fields */}
        <div className="space-y-2 [&_label]:!mb-1 [&_*]:!text-sm  ">
          <div>
            <PrimarySelect
              id="country"
              label={tr["country_region"]}
              options={gulfCountries.map(c => ({ value: c.value, label: c.labelTrans[lang] }))}
              value={country}
              onChange={e => {
                setCountry(e.target.value);
                const selected = gulfCountries.find(cn => cn.value === e.target.value);
                if (selected) setCurrency(selected.currency);
                if ( e.target.value === "us" ) setLanguage("en");
              }}
              required
              hasLeftBorder={false}
              labelClassName=" text-[#7B7B7B] "
            />
          </div>
          <div>
            <PrimarySelect
              id="language"
              label={tr["language"]}
              options={languages.map(lng => ({ value: lng.value, label: lng.labelTrans[lang] }))}
              value={language}
              onChange={e => setLanguage(e.target.value)}
              required
              disabled={ country === "us" }
              hasLeftBorder={false}
              labelClassName=" text-[#7B7B7B] "

            />
          </div>
          <div>
            <PrimarySelect
              id="currency"
              label={tr["currency"]}
              options={currencies}
              value={currency}
              disabled
              onChange={e => setCurrency(e.target.value)}
              required
              hasLeftBorder={false}
              labelClassName=" text-[#7B7B7B] "

            />
          </div>
        </div>

        <PaymentMethods />

        {/* Action buttons */}
        <div className="flex gap-4 mt-10">
          <Button
            variant="primary"
            fullWidth
            className="!rounded-[2rem] text-sm py-1 "
            onClick={() => changeLocale(language, currency, country)}
          >
            {tr["save"]}
          </Button>
          <Button
            variant="white"
            fullWidth
            className="!rounded-[2rem] text-sm py-1 border "
            onClick={onClose}
          >
            {tr["cancel"]}
          </Button>
        </div>
      </div>
    </div>
  );
}

