import React, { useState } from 'react';

const Accordion = ({ title, children, isOpen: externalIsOpen, onToggle }) => {
  const [internalIsOpen, setInternalIsOpen] = useState(false);

  // Use external state if provided, otherwise use internal state
  const isOpen = externalIsOpen !== undefined ? externalIsOpen : internalIsOpen;
  const handleToggle = onToggle || (() => setInternalIsOpen(!internalIsOpen));

  return (
    <div className='border-b border-[#CECECE]'>
      <button
        className="flex justify-between items-center w-full py-4 text-left  "
        onClick={handleToggle}
      >
        <div className='flex font-bold sm:text-lg xl:text-[20px] items-center gap-2 uppercase' style={{ fontFamily: 'Times New Roman' }}>
          {title}
        </div>
        <svg
          className={`transition-transform duration-200 ${isOpen ? 'rotate-180' : 'rotate-0'}`}
          width="18"
          height="19"
          viewBox="0 0 18 19"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M5.25 8.21265L9 11.9626L12.75 8.21265"
            stroke="#222222"
            strokeWidth="1.5"
            strokeLinecap="round"
            strokeLinejoin="round"
          />
        </svg>

      </button>
      <div
        className={`overflow-hidden  ${isOpen ? 'max-h-96 opacity-100 pb-4' : 'max-h-0 opacity-0'}`}
      >
        <div>
          {children}
        </div>
      </div>
    </div>
  );
};

export default Accordion;
