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

        {isValid && phoneValue && (
          <div className={`absolute top-1/2 transform -translate-y-1/2 pointer-events-none ${isRTL ? 'left-3' : 'right-3'}`}>
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M8 0C3.58862 0 0 3.58862 0 8C0 12.4114 3.58862 16 8 16C12.4114 16 16 12.4114 16 8C16 3.58862 12.4114 0 8 0ZM11.8047 6.4L7.59864 10.6061C7.55178 10.6529 7.49695 10.69 7.43693 10.7154C7.37691 10.7407 7.31287 10.7538 7.24819 10.7538C7.18351 10.7538 7.11947 10.7407 7.05945 10.7154C6.99944 10.69 6.9446 10.6529 6.89775 10.6061L4.19526 7.90358C4.09809 7.80642 4.04362 7.67405 4.04362 7.53585C4.04362 7.39764 4.09809 7.26528 4.19526 7.16811C4.29242 7.07095 4.42479 7.01648 4.56299 7.01648C4.7012 7.01648 4.83356 7.07095 4.93073 7.16811L7.24819 9.48557L11.0693 5.66446C11.1664 5.5673 11.2988 5.51283 11.437 5.51283C11.5752 5.51283 11.7076 5.5673 11.8047 5.66446C11.9019 5.76163 11.9564 5.894 11.9564 6.0322C11.9564 6.1704 11.9019 6.30277 11.8047 6.39993V6.4Z" fill="#10b981" />
            </svg>
          </div>
        )}
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
