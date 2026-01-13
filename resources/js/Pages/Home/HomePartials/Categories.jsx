import React, { useRef } from 'react'
import CategoryCard from './CategoryCard'
// start lang
import { useTranslation } from '@/contexts/TranslationContext';
// end lang
import { Swiper, SwiperSlide } from 'swiper/react';
import { Autoplay, Navigation, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';
import SwiperButtons from '@/components/ui/SwiperButtons';

export default function Categories({ categories }) {

  const categoryList = categories || {};
  // Start language
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  // end lang

  const prevRef = useRef(null);
  const nextRef = useRef(null);

  return (
    <section className='container'>
      <div>
        <div className="flex items-center gap-2 max-sm:text-xs font-semibold"><div className="bg-[#004AAD] w-3 h-[2px]"></div>{tr['search_and_explore']}</div>
        <h2 className='text-2xl sm:text-4xl font-bold mt-2'>{tr['our_categories']}</h2>
      </div>

      <div className='mt-8 sm:mt-16 '>

        {Object.values(categoryList).length > 4 ? (
          <div className="w-full relative">
            <SwiperButtons prevRef={prevRef} nextRef={nextRef}></SwiperButtons>
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
              slidesPerView={1}
              spaceBetween={10}
              breakpoints={{
                500: {
                  slidesPerView: 2,
                  spaceBetween: 20,
                },
                880: {
                  slidesPerView: 3,
                  spaceBetween: 20,
                },
                1440: {
                  slidesPerView: 4,
                  spaceBetween: 20,
                },

              }}
              loop={true}
              autoplay={{ delay: 4000 }}
            >

              {Object.values(categoryList).map((category, index) => (
                <SwiperSlide key={`${index}-${category.text}`}>
                  <CategoryCard
                    key={index}
                    category={category}
                  />
                </SwiperSlide>
              ))}
            </Swiper>
          </div>
        ) : (
          <div className="grid grid-cols-2 xl:grid-cols-4 gap-5">
            {Object.values(categoryList).map((category, index) => (
              <CategoryCard
                key={index}
                category={category}
              />
            ))}
          </div>
        )}
      </div>

    </section>
  )
}
