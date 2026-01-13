import React from 'react';
import { Swiper, SwiperSlide } from 'swiper/react';
import { Autoplay, Pagination } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/pagination';
import { Link } from '@inertiajs/react';

export default function Hero({ home_hero_main, home_hero_one, home_hero_two, imageLinks, containerClassName }) {
  const heroImages = Array.isArray(home_hero_main) ? home_hero_main : [home_hero_main];

  return (
    <section className={'container hero-section ' + containerClassName}>
      <div className="w-full sm:mt-10 mb-0 relative  !pb-0">
        <div className="w-full">
          {heroImages.length > 1 ? (
            <Swiper
              modules={[Autoplay, Pagination]}
              slidesPerView={1}
              spaceBetween={0}
              loop={true}
              autoplay={{ delay: 4000 }}
              pagination={{ clickable: true }}
            >
              {heroImages.map((image, index) => (
                <SwiperSlide key={index}>
                  <Link href={imageLinks['slider'][index]}>
                    <img
                      src={image}
                      alt={`Hero ${index + 1}`}
                      className='w-full h-full rounded-xl object-cover'
                    />
                  </Link>
                </SwiperSlide>
              ))}
            </Swiper>
          ) : (
            <Link href={imageLinks['slider']['default']}>
              <img
                src={heroImages[0]}
                alt="Hero"
                className='w-full h-full rounded-xl object-cover'
              />
            </Link>

          )}
        </div>
      </div>

      <div className='grid grid-cols-2 gap-4 pt-4'>
        <div>
          <Link href={imageLinks['hero_image_one']}>
            <img src={home_hero_one} className='w-full h-full rounded-xl' />
          </Link>
        </div>
        <div>
          <Link href={imageLinks['hero_image_two']}>
            <img src={home_hero_two} className='w-full h-full rounded-xl' />
          </Link>
        </div>
      </div>
    </section>
  );
}
