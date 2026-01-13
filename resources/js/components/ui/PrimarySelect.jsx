import React from "react";
// start lang
import ar from '@/translation/ar'
import en from '@/translation/en'
import { usePage } from '@inertiajs/react';
// end lang
const PrimarySelect = ({
  id,
  options = [],
  value,
  onChange = (() => { }),
  placeholder = "",
  className = "",
  label = "",
  error,
  disabled = false,
  required = false,
  valid = false,
  hasBottomMargin = true,
  hasLeftBorder = true,
  helperText = "",
  hasTranslation = false,
  selectContainerClasses = '',
  labelClassName = "",
  ...props
}) => {
  // Determine select state for styling
  const hasError = !!error;
  const isValid = valid && !hasError;

  // Start language
  const lang = usePage().props.locale;
  const tr = lang === 'ar' ? ar : en;
  // end lang
  return (
    <div className={" w-full " + (hasBottomMargin && " mb-4 ") + selectContainerClasses}>
      {label && (
        <label htmlFor={id} className={"block mb-2  [&_span]:!text-error text-sm sm:text-base " + labelClassName}>
          {label} {required && <span className="">*</span>}
        </label>
      )}
      <div className="relative">
        <select
          value={value}
          onChange={onChange}
          className={`text-sm sm:text-base w-full px-4 py-3 ltr:pr-10 border bg-white border-gray-300 rounded appearance-none text-gray-800 focus:outline-none ${disabled ? 'bg-gray-100 cursor-not-allowed' : ''
            } ${hasError ? 'border-error focus:ring-2 focus:ring-error/30' :
              (isValid ? 'border-success focus:ring-2 focus:ring-success/30' :
                'border-gray-300 focus:ring-2 focus:ring-secondary-500/30')
            } ${className}`}
          disabled={disabled}
          required={required}
          {...props}
        >

          {placeholder && (
            <option disabled value="">
              {placeholder}
            </option>
          )}
          {options.map((option, index) => (hasTranslation && tr[option.label]) && (
            <option key={index} value={option.value}>
              {tr[option.label]}
            </option>
          ))}
          {options.map((option, index) => (!hasTranslation) && (
            <option key={index} value={option.value}>
              {option.label}
            </option>
          ))}
        </select>

        {/* Status icon for validation states */}
        {isValid && (
          <div className="absolute transform -translate-y-1/2 rtl:left-12 ltr:right-12 top-1/2">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M8 0C3.58862 0 0 3.58862 0 8C0 12.4114 3.58862 16 8 16C12.4114 16 16 12.4114 16 8C16 3.58862 12.4114 0 8 0ZM11.8047 6.4L7.59864 10.6061C7.55178 10.6529 7.49695 10.69 7.43693 10.7154C7.37691 10.7407 7.31287 10.7538 7.24819 10.7538C7.18351 10.7538 7.11947 10.7407 7.05945 10.7154C6.99944 10.69 6.9446 10.6529 6.89775 10.6061L4.19526 7.90358C4.09809 7.80642 4.04362 7.67405 4.04362 7.53585C4.04362 7.39764 4.09809 7.26528 4.19526 7.16811C4.29242 7.07095 4.42479 7.01648 4.56299 7.01648C4.7012 7.01648 4.83356 7.07095 4.93073 7.16811L7.24819 9.48557L11.0693 5.66446C11.1664 5.5673 11.2988 5.51283 11.437 5.51283C11.5752 5.51283 11.7076 5.5673 11.8047 5.66446C11.9019 5.76163 11.9564 5.894 11.9564 6.0322C11.9564 6.1704 11.9019 6.30277 11.8047 6.39993V6.4Z" fill="#2BB900" />
            </svg>
          </div>
        )}

        {/* Dropdown arrow */}
        <div className={" p-2 m-2 mx-0 absolute inset-y-0  flex items-center  pointer-events-none rtl:left-1  rtl:pl-2  ltr:right-0 ltr:pr-3   " + (hasLeftBorder ? ' rtl:border-r border-[#CDCDCD] ltr:border-l' : ' ') }>
          <svg
            width="16"
            height="16"
            viewBox="0 0 16 16"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              d="M4 6L8 10L12 6"
              stroke="#000000"
              strokeWidth="1.5"
              strokeLinecap="round"
              strokeLinejoin="round"
            />
          </svg>
        </div>
      </div>

      {/* Helper text */}
      {helperText && !hasError && !isValid && (
        <div className="mt-1 text-sm text-primary-300">
          {helperText}
        </div>
      )}

      {/* Display error message */}
      {hasError && (
        <div className="mt-1 text-sm text-error">
          {error}
        </div>
      )}

      {/* Display validation success message */}
      {isValid && !hasError && (
        <div className="mt-1 text-sm text-success">
          Valid selection
        </div>
      )}
    </div>
  );
};

export default PrimarySelect;
