import { Link } from '@inertiajs/react';
import React from 'react';

/**
 * Button component that can behave as either a button or a link
 *
 * Props:
 * - href: If provided, renders as Link component
 * - size: 'sm', 'md', 'lg' - Defines button size
 * - variant: 'primary', 'secondary', 'outline', 'text' - Defines button style
 * - fullWidth: Boolean to make button take full width
 * - className: Additional CSS classes
 * - disabled: Disables the button
 * - onClick: Click handler function
 * - children: Button content
 * - type: Button type (submit, button, reset)
 * - otherProps: Additional properties passed to the button or link element
 */
export default function Button({
  href,
  size = 'md',
  variant = 'primary',
  fullWidth = false,
  className = '',
  disabled = false,
  onClick,
  children,
  type = 'button',
  ...otherProps
}) {
  // Base classes for all button variants
  const baseClasses = `
        inline-flex max-sm:text-sm items-center justify-center font-medium transition-colors duration-200 rounded-full
        rounded focus:outline-none focus:ring-2 focus:ring-offset-1
        tracking-wide
        ${fullWidth ? 'w-full' : ''}
        ${disabled ? ' cursor-not-allowed opacity-60 ' : ' cursor-pointer '}
    `;

  // Size classes
  const sizeClasses = {
    sm: 'py-2 px-3 text-sm',
    md: 'py-3 px-5 text-base',
    lg: 'py-4 px-6 text-lg'
  };

  // Variant classes
  const variantClasses = {
    primary: `bg-black text-white hover:bg-primary-400 active:bg-black focus:ring-primary-400
                    ${disabled ? 'bg-[#D0D2D8] hover:bg-primary-400' : ''}`,
    secondary: `bg-secondary-500 text-white hover:bg-secondary-400 active:bg-secondary-600 focus:ring-secondary-400
                    ${disabled ? 'bg-[#D0D2D8] hover:bg-primary-400' : ''}`,
    outline: `bg-transparent border border-black text-black hover:bg-primary-100 active:bg-primary-200 focus:ring-primary-300
                    ${disabled ? 'border-[#D0D2D8] text-primary-400 hover:bg-transparent' : ''}`,
    text: `bg-transparent text-primary-400 hover:bg-primary-100 active:bg-primary-200 focus:ring-primary-300
                    ${disabled ? 'text-[#D0D2D8]' : ''}`,
    white: `bg-white text-primary-400 border border-gray-300 hover:bg-gray-50 active:bg-gray-100 focus:ring-primary-300
                    ${disabled ? "bg-[#F5F5F5] text-gray-400 border-gray-200" : ""}`,
    out_of_stock: ` bg-[#DF6363] text-white border border-[#B00E0E] hover:bg-[#C55454] active:bg-[#A94444] focus:ring-[#FFBABA]
                    ${disabled ? "bg-[#DF6363] text-gray-400 border-gray-200 cursor-not-allowed" : ""} `,
    out_of_stock_unsubscribe: `bg-transparent border border-[#CECECE] text-[#565656] hover:bg-primary-100 active:bg-primary-200 focus:ring-primary-300
                    ${disabled ? 'border-[#D0D2D8] text-primary-400 hover:bg-transparent' : ''}`,

  };

  // Combine all classes
  const buttonClasses = `${baseClasses} ${sizeClasses[size]} ${variantClasses[variant]} ${className}`;

  // Render as Link if href is provided
  if (href && !disabled) {
    // If external link or target _blank → render <a>
    if (otherProps?.target === "_blank") {
      return (
        <a
          href={href}
          className={buttonClasses}
          {...otherProps}
        >
          {children}
        </a>
      );
    }

    // Default: render Inertia <Link>
    return (
      <Link
        href={href}
        className={buttonClasses}
        {...otherProps}
      >
        {children}
      </Link>
    );
  }

  // Render as button
  return (
    <button
      type={type}
      className={buttonClasses}
      disabled={disabled}
      onClick={!disabled ? onClick : undefined}
      {...otherProps}
    >
      {children}
    </button>
  );
}
