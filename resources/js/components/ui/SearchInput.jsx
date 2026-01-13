import React, { useRef } from 'react';
import { router } from '@inertiajs/react';

export default function SearchInput({
  id,
  type = 'text',
  placeholder = 'Search by order ID...',
  value,
  className = '',
  queryKey = 'search',
  routeName,
  routeParams = {},
  pageParam = 'page',
  ...rest
}) {
  const inputRef = useRef(null);

  const handleKeyDown = (e) => {
    if (e.key !== 'Enter') return;

    let queryString = {
      ...Object.fromEntries(new URLSearchParams(window.location.search)),
    };

    if (e.target.value) {
      queryString[queryKey] = e.target.value;
    } else {
      delete queryString[queryKey];
    }

    // Reset page when searching
    queryString[pageParam] = 1;

    if (routeName) {
      router.get(route(routeName, routeParams), queryString, {
        preserveState: true,
        replace: true,
      });
    }
  };

  return (
    <div className={className}>
      <div className="relative">
        <input
          id={id}
          type={type}
          ref={inputRef}
          defaultValue={value}
          placeholder={placeholder}
          onKeyDown={handleKeyDown}
          className="w-full pl-6 pr-12 rtl:pr-6 rtl:pl-12 py-3 border border-[#CECECE] rounded-full bg-[#F8F8F8] focus:outline-none focus:ring-2 focus:ring-gray-300 focus:border-gray-400 text-sm disabled:bg-gray-100 disabled:cursor-not-allowed"
          {...rest}
        />

        <div className="absolute inset-y-0 right-4 rtl:right-auto rtl:left-4 flex items-center pointer-events-none">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M15.553 15.553C16.2086 14.8973 16.7287 14.119 17.0835 13.2624C17.4383 12.4058 17.6209 11.4877 17.6209 10.5605C17.6209 9.63327 17.4383 8.71515 17.0835 7.85854C16.7287 7.00192 16.2086 6.22359 15.553 5.56796C14.8973 4.91234 14.119 4.39227 13.2624 4.03745C12.4058 3.68262 11.4877 3.5 10.5605 3.5C9.63327 3.5 8.71515 3.68262 7.85854 4.03745C7.00192 4.39227 6.22359 4.91234 5.56796 5.56796C4.24387 6.89205 3.5 8.68791 3.5 10.5605C3.5 12.433 4.24387 14.2289 5.56796 15.553C6.89205 16.8771 8.68791 17.6209 10.5605 17.6209C12.433 17.6209 14.2289 16.8771 15.553 15.553ZM15.553 15.553L20 20" stroke="black" strokeWidth="1.6" strokeLinecap="round" strokeLinejoin="round" />
          </svg>
        </div>
      </div>
    </div>
  );
}
