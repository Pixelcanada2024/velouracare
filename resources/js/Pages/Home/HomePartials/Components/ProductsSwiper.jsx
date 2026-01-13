import { Swiper, SwiperSlide } from 'swiper/react'
import { Navigation, Autoplay, Grid } from 'swiper/modules'
import { useRef } from 'react';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/grid';
import ProductCard from '@/components/Shared/ProductCard';

export default function ProductsSwiper({ products }) {
  const prevRef = useRef(null);
  const nextRef = useRef(null);
  const navBtnClasses = `w-12 h-12 text-xl p-0 grid place-items-center absolute z-10 top-1/2 -translate-y-1/2 bg-white
  text-black rounded-full cursor-pointer  hover:opacity-100 transition duration-300 font-bold`;

  return (
    <div className="w-full mb-0 relative  !pb-0" dir="ltr">
      <button ref={prevRef} className={"left-0 md:-left-14 " + navBtnClasses}>
        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M21.5 12.513L3.711 12.5M10.488 19.5L3.5 12.5L10.488 5.5" stroke="black" strokeLinecap="round" strokeLinejoin="round" />
        </svg>
      </button>
      <button ref={nextRef} className={"right-0 md:-right-14 " + navBtnClasses}>
        <svg width="25" height="25" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M3.5 12.513L21.289 12.5M14.512 19.5L21.5 12.5L14.512 5.5" stroke="black" strokeLinecap="round" strokeLinejoin="round" />
        </svg>
      </button>
      <div className="[&_.swiper-wrapper]:space-y-10 [&_.swiper-wrapper]:py-5 " >
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
          grid={{ rows: 1, fill: 'row', }}
          slidesPerView={5}
          spaceBetween={30}
          loop={false}
          autoplay={{ delay: 3000 }}
          breakpoints={{
            250: { slidesPerView: 1.5, centeredSlides: true, grid: { rows: 1, fill: 'row', } },
            640: { slidesPerView: 2, centeredSlides: false, grid: { rows: 1, fill: 'row', } },
            960: { slidesPerView: 3, centeredSlides: false, grid: { rows: 1, fill: 'row', } },
            1180: { slidesPerView: 4, centeredSlides: false, grid: { rows: 1, fill: 'row', } },
            1800: { slidesPerView: 5, centeredSlides: false, grid: { rows: 1, fill: 'row', } },
          }}
        >
          {products.map((product, index) => (
            <SwiperSlide key={index} className="flex justify-center items-center">
              <ProductCard key={index + product.id + product.title} product={product} />
            </SwiperSlide>
          ))}
        </Swiper>
      </div>
    </div>
  )
}
