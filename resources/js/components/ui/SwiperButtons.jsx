import React from 'react'

export default function SwiperButtons({ prevRef, nextRef, }) {
  const navBtnClasses = `w-5 h-5 text-xl p-0 m-0 grid place-items-center absolute z-100 top-1/2 -translate-y-1/2 bg-white
  text-black rounded-none cursor-pointer opacity-80 hover:opacity-100 hover:scale-110 transition duration-300 font-bold`;

  return (
    <>
      <button ref={prevRef} className={"-left-3 lg:-left-5  bg-black" + navBtnClasses}>
        <svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M6.5 1L1.5 6L6.5 11" stroke="#202228" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
        </svg>
      </button>
      <button ref={nextRef} className={"-right-3 lg:-right-5  bg-black" + navBtnClasses}>
        <svg width="8" height="12" viewBox="0 0 8 12" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M1.5 11L6.5 6L1.5 1" stroke="#202228" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
        </svg>
      </button>
    </>
  )
}
