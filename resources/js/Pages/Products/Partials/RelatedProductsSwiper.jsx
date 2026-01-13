import React, { useRef } from 'react'
import { Swiper, SwiperSlide } from 'swiper/react'
import { Navigation, Autoplay } from 'swiper/modules'
import 'swiper/css';
import 'swiper/css/navigation';
import ProductCard from '@/components/Shared/ProductCard';
import { Link, usePage } from '@inertiajs/react';
// start lang
import { useTranslation } from '@/contexts/TranslationContext';
// end lang

export default function RelatedProductsSwiper({ frequently_bought_products }) {

  // Start language
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  // end lang

  const prevRef = useRef(null);
  const nextRef = useRef(null);
  const navBtnClasses = `w-12 h-12 text-xl p-0 grid place-items-center bg-white
  text-black rounded-full cursor-pointer  hover:opacity-100 transition duration-300 font-bold rounded-full border border-[#E5E7EB]`;


  return (
    <div className='my-16'>

      <div className="container">
        <div className='flex items-center justify-between'>
          <div className='text-xl font-medium'>{tr['related_products']}</div>
          <div className='flex items-center justify-between gap-8' dir="ltr">
            <button ref={prevRef} className={"left-0 md:-left-10 " + navBtnClasses}>
              <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M21 12.513L3.211 12.5M9.988 19.5L3 12.5L9.988 5.5" stroke="black" strokeLinecap="round" strokeLinejoin="round" />
              </svg>
            </button>
            <button ref={nextRef} className={"right-0 md:-right-10 " + navBtnClasses}>
              <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M3 12.513L20.789 12.5M14.012 19.5L21 12.5L14.012 5.5" stroke="black" strokeLinecap="round" strokeLinejoin="round" />
              </svg>

            </button>
          </div>
        </div>

        <div className="w-full  mb-0 relative !pb-0">

          <div className="[&_.swiper-wrapper]:space-y-10 [&_.swiper-wrapper]:py-5 ">
            <Swiper
              modules={[Navigation, Autoplay]}
              navigation={{
                prevEl: prevRef.current,
                nextEl: nextRef.current,
              }}
              onBeforeInit={(swiper) => {
                swiper.params.navigation.prevEl = prevRef.current;
                swiper.params.navigation.nextEl = nextRef.current;
              }}
              slidesPerView={4}
              spaceBetween={30}
              loop={false}
              autoplay={{ delay: 3000 }}
              breakpoints={{
                250: { slidesPerView: 1.5, centeredSlides: true },
                640: { slidesPerView: 2, centeredSlides: false },
                960: { slidesPerView: 3, centeredSlides: false },
                1180: { slidesPerView: 4, centeredSlides: false },
                1320: { slidesPerView: 5, centeredSlides: false },
              }}
            >
              {!!frequently_bought_products?.length && frequently_bought_products.map((product, index) => (
                <SwiperSlide key={index} className="flex justify-center items-center">
                  <ProductCard key={index + product.title} product={product} />

                </SwiperSlide>
              ))}
            </Swiper>
          </div>
        </div>
      </div>
    </div>
  )
}
