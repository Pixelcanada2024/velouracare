import React from "react";
import Layout from "@/components/Layout/Layout";
import { Link, usePage } from "@inertiajs/react";
// start lang
import { useTranslation } from "@/contexts/TranslationContext";
import PartnerImages from "@/components/Shared/PartnerImages";
// end lang

export default function AboutUs({ images, textContent }) {
  // Start language
  const [{lang, currency, tr}, _setTranslation] = useTranslation();
  const nameExtra = lang === "ar" ? "_ar" : "";
  // end lang
  return (
    <Layout
      pageTitle={tr["about_us"]}
      ShowFooterSwiper={false}
    >
      <div className='bg-[#F2F4F7]'>
        <div className='container mx-auto py-3 sm:py-6 flex flex-col gap-10'>
          <div className="text-sm text-gray-600 mb-2">
            <span><Link href={route("react.home")}>{tr["home"]}</Link></span> / <span className="text-black"><Link href={route("react.about-us")}>{tr["about_us"]}</Link></span>
          </div>
          <h1 className="text-[28px] sm:text-4xl font-semibold ">{tr["about_us"]}</h1>
        </div>
      </div>

      <div className="container mx-auto my-10 xl:my-20">
        {/* Hero */}
        <div className='mb-8 lg:mb-12'>
          <h3 className='text-xl sm:text-2xl lg:text-3xl 2xl:text-4xl font-semibold mb-8 capitalize'>{textContent?.hero_title}</h3>
          <img src={images["about_us_hero_desktop"+nameExtra]} className='w-full h-full max-lg:hidden' alt="About Us Hero" />
          <img src={images["about_us_hero_mobile"+nameExtra]} className='w-full h-full lg:hidden' alt="About Us Hero Mobile" />
        </div>

        <div className="space-y-4 bg-[#F2F4F7] p-4 sm:p-8 rounded-2xl">
          <h2 className="text-2xl lg:text-3xl font-semibold mb-5 lg:mb-10">{textContent?.we_are_distributors_title}</h2>

          <p className='text-[#222222] mb-8'>
            {textContent?.we_are_distributors_description}
          </p>

          {/* Stats grid */}
          <div className="grid md:grid-cols-4 gap-4 text-[#222222]">
            <div className="text-center grid place-items-center bg-white p-4 gap-2 rounded-lg">
              <p className="text-xl lg:text-2xl font-semibold text-center capitalize">{textContent?.stats_years_experience} <sup>+</sup></p>
              <p className='text-xs lg:text-lg'>{tr["years_of_experience"]}</p>
            </div>
            <div className="text-center grid place-items-center bg-white p-4 gap-2 rounded-lg">
              <p className="text-xl lg:text-2xl font-semibold text-center ">{textContent?.stats_brands} <sup>+</sup></p>
              <p className='text-xs lg:text-lg'>{tr["brands"]}</p>
            </div>
            <div className="text-center grid place-items-center bg-white p-4 gap-2 rounded-lg">
              <p className="text-xl lg:text-2xl font-semibold text-center ">{textContent?.stats_products} <sup>+</sup></p>
              <p className='text-xs lg:text-lg'>{tr["products"]}</p>
            </div>
            <div className="text-center grid place-items-center bg-white p-4 gap-2 rounded-lg">
              <p className="text-xl lg:text-2xl font-semibold text-center ">{textContent?.stats_clients} <sup>+</sup></p>
              <p className='text-xs lg:text-lg'>{tr["clients"]}</p>
            </div>
          </div>
        </div>


        <div>
          <div className='grid items-center  grid-cols-1 xl:grid-cols-2 gap-8 xl:gap-16 mt-12'>
            <div className='relative w-full 2xl:w-160 mx-auto'>
              <img src={images["our_mission_image"+nameExtra]} alt="Our Mission" className='w-full object-cover h-90 sm:h-[750px] xl:h-[875px] rounded-xl' />
              <div className='max-xl:hidden absolute -bottom-3 rtl:-right-3 ltr:-left-3 w-[300px] h-[300px] -z-50 bg-[#FFF8F8] rtl:rounded-br-3xl ltr:rounded-bl-3xl'></div>
            </div>
            <div className='flex flex-col gap-8 justify-evenly'>
              <div className='bg-[#FFF8F8] p-4 sm:p-8 rounded-2xl'>
                <h3 className='font-semibold  text-xl lg:text-2xl mb-8 capitalize'>{textContent?.our_mission_title}</h3>
                <p className='sm:text-md lg:text-lg text-[#222222] mb-5'>{textContent?.our_mission_description_1}</p>
                <p className='sm:text-md lg:text-lg text-[#222222]'>{textContent?.our_mission_description_2}</p>
              </div>
              <div className='bg-[#FFF8F8] p-4 sm:p-8 rounded-2xl'>
                <h3 className='font-semibold  text-xl lg:text-2xl mb-8 capitalize'>{textContent?.what_we_offer_title}</h3>
                <p className='sm:text-md lg:text-lg text-[#222222] mb-5'>{textContent?.what_we_offer_description_1}</p>
                <p className='sm:text-md lg:text-lg text-[#222222]'>{textContent?.what_we_offer_description_2}</p>
              </div>
            </div>
          </div>

          <div className="grid items-center grid-cols-1 xl:grid-cols-2 gap-8 xl:gap-16 mt-12">
            <div className="relative w-full 2xl:w-160 mx-auto order-1 xl:order-2">
              <img src={images["our_values_image"+nameExtra]} alt="Our Values" className="w-full object-cover h-90 sm:h-[750px] xl:h-[875px] rounded-xl" />
              <div className="max-xl:hidden absolute -bottom-3 rtl:-left-3 ltr:-right-3 w-[300px] h-[300px] -z-50 bg-[#F0FAFF] rtl:rounded-bl-3xl ltr:rounded-br-3xl"></div>
            </div>
            <div className="flex flex-col gap-8 justify-evenly order-2 xl:order-1">
              <div className='bg-[#F0FAFF] p-4 sm:p-8 rounded-2xl'>
                <h3 className='font-semibold  text-xl lg:text-2xl mb-8 capitalize'>{textContent?.our_values_title}</h3>
                <p className='sm:text-md lg:text-lg text-[#222222] mb-5'>{textContent?.our_values_description}</p>
              </div>
              <div className='bg-[#F0FAFF] p-4 sm:p-8 rounded-2xl'>
                <h3 className='font-semibold  text-xl lg:text-2xl mb-8 capitalize'>{textContent?.contact_title}</h3>
                <p className='sm:text-md lg:text-lg text-[#222222] mb-10 xl:mb-20'>{textContent?.contact_description_1}</p>
                <p className='sm:text-md lg:text-lg text-[#222222]'>{textContent?.contact_description_2}</p>
              </div>
            </div>
          </div>
        </div>


        {/* Partner with Veloura Care */}
        <Link href={route("react.contact-us")}>
          <div className='my-16 lg:my-32 container'>
            <PartnerImages bigImg={images?.["partner_with_sky_business_desktop"+nameExtra]} smallImg={images?.["partner_with_sky_business_mobile"+nameExtra]} />
          </div>
        </Link>

        <div>
          <h3 className='text-xl sm:text-2xl lg:text-3xl 2xl:text-4xl font-semibold text-center mb-16'>{textContent?.why_best_title}</h3>
          <div className='space-y-10'>
            <div className='grid xl:grid-cols-2 gap-10'>
              <div className='bg-[#FFF8F8] flex max-sm:flex-col rounded-tr-3xl rounded-br-3xl sm:rounded-3xl'>
                <div className='sm:w-1/2 max-sm:p-4'>
                  <img src={images["competitive_price_image"+nameExtra]} className='w-full h-full ' />
                </div>
                <div className="flex-1 max-sm:hidden"></div>
                <div className='space-y-4 sm:w-80 p-4 sm:p-8 sm:self-center '>
                  <h3 className="text-xl lg:text-2xl font-semibold capitalize">{textContent?.competitive_price_title}</h3>
                  <p className='lg:text-lg 2xl:text-xl text-[#222222]'>{textContent?.competitive_price_description}</p>
                </div>
              </div>

              <div className='bg-[#FFF8F8] flex max-sm:flex-col-reverse rounded-tr-3xl rounded-br-3xl sm:rounded-3xl'>
                <div className='space-y-4 sm:w-80 p-4 sm:p-8 sm:self-center '>
                  <h3 className="text-xl lg:text-2xl font-semibold capitalize">{textContent?.bulk_ordering_title}</h3>
                  <p className='lg:text-lg 2xl:text-xl text-[#222222]'>{textContent?.bulk_ordering_description}</p>
                </div>
                <div className="flex-1 max-sm:hidden"></div>
                <div className='sm:w-1/2 max-sm:p-4'>
                  <img src={images["bulk_ordering_image"+nameExtra]} className='w-full h-full ' />
                </div>
              </div>
            </div>

            <div className='grid xl:grid-cols-2 gap-10'>
              <div className='bg-[#FFF8F8] flex max-sm:flex-col rounded-tr-3xl rounded-br-3xl sm:rounded-3xl'>
                <div className='sm:w-1/2 max-sm:p-4'>
                  <img src={images["efficient_logistics_image"+nameExtra]} className='w-full h-full ' />
                </div>
                <div className="flex-1 max-sm:hidden"></div>
                <div className='space-y-4 sm:w-80 p-4 sm:p-8 sm:self-center '>
                  <h3 className="text-xl lg:text-2xl font-semibold capitalize">{textContent?.efficient_logistics_title}</h3>
                  <p className='lg:text-lg 2xl:text-xl text-[#222222]'>{textContent?.efficient_logistics_description}</p>
                </div>
              </div>

              <div className='bg-[#FFF8F8] flex max-sm:flex-col-reverse rounded-tr-3xl rounded-br-3xl sm:rounded-3xl'>
                <div className='space-y-4 sm:w-80 p-4 sm:p-8 sm:self-center '>
                  <h3 className="text-xl lg:text-2xl font-semibold capitalize">{textContent?.exclusive_offers_title}</h3>
                  <p className='lg:text-lg 2xl:text-xl text-[#222222]'>{textContent?.exclusive_offers_description}</p>
                </div>
                <div className="flex-1 max-sm:hidden"></div>
                <div className='sm:w-1/2 max-sm:p-4'>
                  <img src={images["exclusive_offers_image"+nameExtra]} className='w-full h-full ' />
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </Layout>
  );
}
