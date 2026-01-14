import React, { useState, useEffect } from "react";
import { PhoneInput as ReactPhoneInput } from "react-international-phone";
import { PhoneNumberUtil } from "google-libphonenumber";
import "react-international-phone/style.css";

// start lang
import ar from "@/translation/ar"
import en from "@/translation/en"
import { usePage } from "@inertiajs/react";
// end lang

const phoneUtil = PhoneNumberUtil.getInstance();

const isPhoneValid = (phone) => {
  try {
    return phoneUtil.isValidNumber(phoneUtil.parseAndKeepRawInput(phone));
  } catch (error) {
    return false;
  }
};

export default function PhoneInput({
  id,
  label,
  value,
  onChange,
  placeholder = "Phone number",
  error,
  required = false,
  valid = false,
  className = "",
  defaultCountry = null,
  ...rest
}) {
  const { isSkyAmerica } = usePage().props;
  const lang = usePage().props.locale;
  const isRTL = lang === "ar";
  const tr = lang === "ar" ? ar : en;

  const defaultCountryCode = defaultCountry || usePage().props.preferences?.country?.toLowerCase() || (isSkyAmerica ? 'us' : 'sa');
  const [phoneValue, setPhoneValue] = useState(value || "");
  const [isValidPhone, setIsValidPhone] = useState(false);

  useEffect(() => {
    setPhoneValue(value || "");
  }, [value]);

  useEffect(() => {
    if (phoneValue) {
      const validPhone = isPhoneValid(phoneValue);
      setIsValidPhone(validPhone);
    } else {
      setIsValidPhone(false);
    }
  }, [phoneValue]);

  const handlePhoneChange = (phone) => {
    setPhoneValue(phone);
    if (onChange) {
      onChange({ target: { value: phone } });
    }
  };

  // Determine input state for styling
  const hasError = !!error;
  const isValid = (valid || isValidPhone) && !hasError;


  const containerClasses = `
    [&_.react-international-phone-input]:w-full
    [&_.react-international-phone-input]:!p-6
    [&_.react-international-phone-input]:rounded-md
    [&_.react-international-phone-country-selector-button]:!p-6
    [&_.react-international-phone-country-selector-button]:border-none
    [&_.react-international-phone-country-selector-button]:bg-transparent
    ${hasError ?
      "[&_.react-international-phone-input]:!border-red-500 [&_.react-international-phone-input]:focus:!ring-2 [&_.react-international-phone-input]:focus:!ring-red-500/30" :
      isValid ?
        "[&_.react-international-phone-input]:!border-green-500 [&_.react-international-phone-input]:focus:!ring-2 [&_.react-international-phone-input]:focus:!ring-green-500/30" :
        "[&_.react-international-phone-input]:border-gray-300 [&_.react-international-phone-input]:focus:!ring-2 [&_.react-international-phone-input]:focus:!ring-blue-500/30"
    }
  `;

  return (
    <div className={`mb-4 ${className}`}>
      {label && (
        <label htmlFor={id} className="block mb-2 text-black text-sm sm:text-base">
          {label} {required && <span className="text-red-500">*</span>}
        </label>
      )}

      <div className={`relative ${containerClasses}`} style={{ '--react-international-phone-border-radius': '0' }}>
        <ReactPhoneInput
          defaultCountry={defaultCountryCode}
          value={phoneValue}
          onChange={handlePhoneChange}
          placeholder={placeholder}
          inputProps={{
            id: id,
            required: required,
            ...rest
          }}
        />

      </div>

      {hasError && (
        <div className="mt-1 text-sm text-red-500">
          {error}
        </div>
      )}

      {isValid && !hasError && phoneValue && (
        <div className="mt-1 text-sm text-green-600">
          {tr["phone_valid"]}
        </div>
      )}

      {phoneValue && !isValidPhone && !hasError && (
        <div className="mt-1 text-sm text-red-500">
          {tr["phone_invalid"]}
        </div>
      )}
    </div>
  );
}
