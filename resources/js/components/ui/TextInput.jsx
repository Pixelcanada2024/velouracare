import React from 'react';

export default function TextInput({
  id,
  type = 'text',
  label,
  value,
  onChange,
  placeholder,
  error,
  required = false,
  valid = false,
  className = '',
  InputClassName = '',
  helperText = '',
  ShowError = true,
  ...rest
}) {
  // Determine input state for styling
  const hasError = !!error;
  const isValid = valid && !hasError;

  // Input classes based on state
  const inputClasses = `
        w-full p-3 border rounded focus:outline-none
        ${rest.disabled ? 'bg-gray-100 cursor-not-allowed' : 'bg-white'}
        ${hasError ? 'border-error focus:ring-2 focus:ring-error/30' :
      (isValid ? 'border-success focus:ring-2 focus:ring-success/30' :
        'border-gray-300 focus:ring-2 focus:ring-secondary-500/30')}
    ` + InputClassName;

  return (
    <div className={`${className}`}>
      {label && (
        <label htmlFor={id} className="block mb-2 text-black text-sm sm:text-base">
          {label} {required && <span className="text-error">*</span>}
        </label>
      )}

      <div className="relative">
        <input
          id={id}
          type={type}
          value={value}
          onChange={onChange}
          placeholder={placeholder}
          className={inputClasses + " text-sm sm:text-base "}
          required={required}
          {...rest}
        />


        {/* Status icon for validation states */}
        {isValid && (
          <div className="absolute transform -translate-y-1/2 rtl:left-3 ltr:right-3 top-1/2">
            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M8 0C3.58862 0 0 3.58862 0 8C0 12.4114 3.58862 16 8 16C12.4114 16 16 12.4114 16 8C16 3.58862 12.4114 0 8 0ZM11.8047 6.4L7.59864 10.6061C7.55178 10.6529 7.49695 10.69 7.43693 10.7154C7.37691 10.7407 7.31287 10.7538 7.24819 10.7538C7.18351 10.7538 7.11947 10.7407 7.05945 10.7154C6.99944 10.69 6.9446 10.6529 6.89775 10.6061L4.19526 7.90358C4.09809 7.80642 4.04362 7.67405 4.04362 7.53585C4.04362 7.39764 4.09809 7.26528 4.19526 7.16811C4.29242 7.07095 4.42479 7.01648 4.56299 7.01648C4.7012 7.01648 4.83356 7.07095 4.93073 7.16811L7.24819 9.48557L11.0693 5.66446C11.1664 5.5673 11.2988 5.51283 11.437 5.51283C11.5752 5.51283 11.7076 5.5673 11.8047 5.66446C11.9019 5.76163 11.9564 5.894 11.9564 6.0322C11.9564 6.1704 11.9019 6.30277 11.8047 6.39993V6.4Z" fill="#2BB900" />
            </svg>
          </div>
        )}
      </div>
      {helperText && (
        <div className="mt-1 text-sm text-[#949494]">
          {helperText}
        </div>
      )}

      {/* Display error message */}
      {ShowError && (
        <div className={` mt-1 text-sm text-error ` + (hasError ? " " :" invisible")}>
          {error ?? "-"}
        </div>
      )}

      {/* Display validation success message */}
      {isValid && !hasError && (
        <div className="mt-1 text-sm text-success">
          Valid input
        </div>
      )}
    </div>
  );
}
