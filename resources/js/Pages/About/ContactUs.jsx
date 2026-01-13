import { Link, useForm, usePage } from "@inertiajs/react";
import Layout from "@/components/Layout/Layout";
import Button from "@/components/ui/Button";
import TextInput from "@/components/ui/TextInput";
import PhoneInput from "@/components/ui/PhoneInput";
import ContactUsForm from "@/components/Shared/ContactUsForm";
import FAQs from '/public/website-assets/home/FAQs.jpg'
// start lang
import { useTranslation } from "@/contexts/TranslationContext";
// end lang
export default function ContactUs() {

  const contact_info = usePage().props.contact_info;
  const links = usePage().props.footer_links;

  // Start language
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();

  const meta = usePage().props.meta;
  const footer_qr_code = meta.footer_qr_code;
  const map_iframe_src = meta.map_iframe_src;
  const footer_links = usePage().props.footer_links;
  const contact_us_locations = usePage().props.contact_us_locations || [];

  const contactUsLocationsList = Array.isArray(contact_us_locations)
    ? contact_us_locations
    : Object.values(contact_us_locations || {});


  return (
    <Layout pageTitle="Contact Us">
      <div className='bg-[#F2F4F7]'>
        <div className='container mx-auto py-3 sm:py-6 flex flex-col gap-10'>
          <div className="text-sm text-gray-600 mb-2">
            <span><Link href={route('react.home')}>Home</Link></span> / <span className="text-black"><Link href={route('react.contact-us')}>{tr['contact_us']}</Link></span>
          </div>
          <h1 className="text-[28px] sm:text-4xl font-semibold " style={{ fontFamily: 'Times New Roman' }}>{tr['contact_us']}</h1>
        </div>
      </div>

      <div className=" mx-auto my-20">


        {/* Contact Form */}
        <div className="sm-container relative mx-auto grid lg:grid-cols-2 gap-16 p-6 sm:rounded-2xl overflow-hidden min-h-240 lg:max-h-280 ">
          <img src={FAQs} alt="" className="absolute -z-50 w-full h-full object-cover opacity-15" />
          <div className="absolute -z-40 bg-gray-500 w-full h-full object-cover opacity-15"></div>
          <div className="lg:p-6 xl:p-12 space-y-8">
            <div>
              <h2 style={{ fontFamily: 'Times New Roman' }} className="text-[22px] sm:text-[28px] xl:text-[34px] font-bold mb-8">{tr['contact_info']}</h2>
              <p className="text-base lg:text-lg">
                {tr['contact_info_description']}
              </p>
            </div>

            {/* Info */}
            <div className="space-y-5">

              <div className="flex items-center gap-2">
                <div>
                  <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18.045 28.604C17.6035 28.4078 17.1688 28.1965 16.7416 27.9707C14.6138 26.7679 12.6598 25.2809 10.9333 23.5507C8.69228 21.4413 6.85163 18.9435 5.50039 16.1784C4.77755 14.6924 4.28916 13.1034 4.05256 11.468C3.73172 9.81614 3.91433 8.10604 4.57672 6.55921C4.98614 5.89337 5.47914 5.28421 6.04297 4.74729C6.27177 4.49065 6.5501 4.28292 6.86124 4.13661C7.17237 3.99029 7.50988 3.9084 7.85347 3.89587C8.56889 4.00354 9.21631 4.37754 9.66397 4.94704C10.6415 6.03362 11.6898 7.01112 12.7226 8.04387C13.1291 8.39237 13.3827 8.88679 13.4281 9.42087C13.4111 9.86996 13.2382 10.2992 12.9393 10.635C12.5965 11.0699 12.18 11.468 11.7819 11.8845C11.5419 12.1165 11.3583 12.4005 11.2453 12.7146C11.1324 13.0288 11.093 13.3646 11.1302 13.6964C11.3801 14.4721 11.8075 15.1788 12.3783 15.7605C13.0668 16.7025 13.7539 17.5738 14.5316 18.496C15.9305 20.1117 17.5885 21.4833 19.4376 22.5548C19.6933 22.7476 19.9956 22.8692 20.3136 22.9073C20.6316 22.9454 20.954 22.8985 21.2481 22.7715C21.8636 22.4288 22.4099 21.9743 22.8588 21.4314C23.2484 20.9568 23.8066 20.6522 24.4157 20.58C24.9583 20.6083 25.4697 20.8406 25.8466 21.2316C26.3339 21.6481 26.7504 22.1383 27.2037 22.5902C27.6556 23.0435 28.0183 23.3694 28.398 23.7859C28.8532 24.1873 29.2815 24.616 29.6829 25.0722C29.9946 25.4745 30.1419 25.9789 30.0994 26.4846C29.9379 27.09 29.5893 27.6289 29.1035 28.0245C28.4204 28.7354 27.5903 29.2886 26.6712 29.6453C25.7522 30.0021 24.7663 30.1539 23.7825 30.09C21.7908 29.977 19.8411 29.472 18.045 28.604Z" stroke="black" strokeWidth="2" strokeMiterlimit="10" strokeLinecap="round" />
                  </svg>
                </div>
                <div className="font-inter">{contact_info.contact_phone}</div>
              </div>

              <div className="flex items-center gap-2">
                <div>
                  <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clipPath="url(#clip0_312_9086)">
                      <path d="M30.224 5.66663H3.77951C3.27855 5.66663 2.7981 5.86563 2.44387 6.21987C2.08963 6.5741 1.89063 7.05455 1.89062 7.55551V26.4444C1.89063 26.9454 2.08963 27.4258 2.44387 27.78C2.7981 28.1343 3.27855 28.3333 3.77951 28.3333H30.224C30.7249 28.3333 31.2054 28.1343 31.5596 27.78C31.9138 27.4258 32.1128 26.9454 32.1128 26.4444V7.55551C32.1128 7.05455 31.9138 6.5741 31.5596 6.21987C31.2054 5.86563 30.7249 5.66663 30.224 5.66663ZM28.7695 26.4444H5.34729L11.9584 19.6066L10.5984 18.2938L3.77951 25.3488V8.99107L15.519 20.6738C15.8729 21.0257 16.3516 21.2231 16.8506 21.2231C17.3496 21.2231 17.8284 21.0257 18.1823 20.6738L30.224 8.69829V25.2261L23.2728 18.275L21.9412 19.6066L28.7695 26.4444ZM5.01674 7.55551H28.694L16.8506 19.3327L5.01674 7.55551Z" fill="black" />
                    </g>
                    <defs>
                      <clipPath id="clip0_312_9086">
                        <rect width="34" height="34" fill="white" />
                      </clipPath>
                    </defs>
                  </svg>

                </div>
                <div className="font-inter">{contact_info.contact_email}</div>
              </div>

              <div className="flex items-center gap-2">
                <div>
                  <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17 18.4167C19.3472 18.4167 21.25 16.514 21.25 14.1667C21.25 11.8195 19.3472 9.91675 17 9.91675C14.6528 9.91675 12.75 11.8195 12.75 14.1667C12.75 16.514 14.6528 18.4167 17 18.4167Z" stroke="black" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
                    <path d="M16.9974 2.83337C13.9916 2.83337 11.1089 4.02742 8.98352 6.15283C6.85811 8.27824 5.66406 11.1609 5.66406 14.1667C5.66406 16.847 6.23356 18.6009 7.78906 20.5417L16.9974 31.1667L26.2057 20.5417C27.7612 18.6009 28.3307 16.847 28.3307 14.1667C28.3307 11.1609 27.1367 8.27824 25.0113 6.15283C22.8859 4.02742 20.0032 2.83337 16.9974 2.83337Z" stroke="black" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
                  </svg>


                </div>
                <div className="font-inter">{contact_info.contact_address}</div>
              </div>

            </div>

            <div className="flex flex-col sm:flex-row lg:flex-col gap-6 lg:gap-12 xl:gap-6">
              <div className="">
                <h2 style={{ fontFamily: 'Times New Roman' }} className="text-[22px] sm:text-[28px] xl:text-[34px] font-bold mb-5 xl:mb-2">{tr['social_accounts']}</h2>
                <p className="text-base lg:text-lg">
                  {tr['social_accounts_contact_us_desc']}
                </p>
                <div className="flex items-center gap-4 mt-6">
                  <a href={links.facebook_link}>
                    <svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <rect width="56" height="56" rx="28" fill="white" />
                      <path d="M30.8307 30.1249H34.3724L35.7891 24.4583H30.8307V21.6249C30.8307 20.1658 30.8307 18.7916 33.6641 18.7916H35.7891V14.0316C35.3272 13.9707 33.5833 13.8333 31.7416 13.8333C27.8954 13.8333 25.1641 16.1807 25.1641 20.4916V24.4583H20.9141V30.1249H25.1641V42.1666H30.8307V30.1249Z" fill="black" />
                    </svg>
                  </a>
                  <a href={links.twitter_link}>
                    <svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <rect width="56" height="56" rx="28" fill="white" />
                      <path d="M36.049 15.3394L28.9713 23.4299L22.8513 15.3394H13.9844L24.5768 29.1887L14.5383 40.6623H18.8365L26.5842 31.8081L33.3559 40.6623H42.0004L30.9589 26.0649L40.3443 15.3394H36.049ZM34.5416 38.091L19.0022 17.7746H21.5565L36.9216 38.0896L34.5416 38.091Z" fill="black" />
                    </svg>
                  </a>
                  <a href={links.instagram_link}>
                    <svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <rect width="56" height="56" rx="28" fill="white" />
                      <path d="M22.0526 13.8333H33.9526C38.4859 13.8333 42.1693 17.5166 42.1693 22.0499V33.9499C42.1693 36.1291 41.3036 38.2191 39.7627 39.76C38.2217 41.3009 36.1318 42.1666 33.9526 42.1666H22.0526C17.5193 42.1666 13.8359 38.4833 13.8359 33.9499V22.0499C13.8359 19.8707 14.7016 17.7808 16.2425 16.2399C17.7835 14.6989 19.8734 13.8333 22.0526 13.8333ZM21.7693 16.6666C20.4167 16.6666 19.1195 17.2039 18.163 18.1603C17.2066 19.1168 16.6693 20.414 16.6693 21.7666V34.2333C16.6693 37.0524 18.9501 39.3333 21.7693 39.3333H34.2359C35.5885 39.3333 36.8857 38.7959 37.8422 37.8395C38.7986 36.8831 39.3359 35.5859 39.3359 34.2333V21.7666C39.3359 18.9474 37.0551 16.6666 34.2359 16.6666H21.7693ZM35.4401 18.7916C35.9098 18.7916 36.3602 18.9782 36.6923 19.3103C37.0244 19.6423 37.2109 20.0928 37.2109 20.5624C37.2109 21.0321 37.0244 21.4825 36.6923 21.8146C36.3602 22.1467 35.9098 22.3333 35.4401 22.3333C34.9704 22.3333 34.52 22.1467 34.1879 21.8146C33.8558 21.4825 33.6693 21.0321 33.6693 20.5624C33.6693 20.0928 33.8558 19.6423 34.1879 19.3103C34.52 18.9782 34.9704 18.7916 35.4401 18.7916ZM28.0026 20.9166C29.8812 20.9166 31.6829 21.6629 33.0113 22.9912C34.3397 24.3196 35.0859 26.1213 35.0859 27.9999C35.0859 29.8785 34.3397 31.6802 33.0113 33.0086C31.6829 34.337 29.8812 35.0833 28.0026 35.0833C26.124 35.0833 24.3223 34.337 22.9939 33.0086C21.6655 31.6802 20.9193 29.8785 20.9193 27.9999C20.9193 26.1213 21.6655 24.3196 22.9939 22.9912C24.3223 21.6629 26.124 20.9166 28.0026 20.9166ZM28.0026 23.7499C26.8754 23.7499 25.7944 24.1977 24.9974 24.9947C24.2004 25.7917 23.7526 26.8727 23.7526 27.9999C23.7526 29.1271 24.2004 30.2081 24.9974 31.0051C25.7944 31.8022 26.8754 32.2499 28.0026 32.2499C29.1298 32.2499 30.2108 31.8022 31.0078 31.0051C31.8048 30.2081 32.2526 29.1271 32.2526 27.9999C32.2526 26.8727 31.8048 25.7917 31.0078 24.9947C30.2108 24.1977 29.1298 23.7499 28.0026 23.7499Z" fill="black" />
                    </svg>
                  </a>

                  <a href={links.linkedin_link}>
                    <svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <rect width="56" height="56" rx="28" fill="white" />
                      <path d="M17.7292 13.6562C16.9308 13.6562 16.165 13.9734 15.6005 14.538C15.0359 15.1025 14.7188 15.8683 14.7188 16.6667C14.7188 17.4651 15.0359 18.2308 15.6005 18.7954C16.165 19.3599 16.9308 19.6771 17.7292 19.6771C18.5276 19.6771 19.2933 19.3599 19.8579 18.7954C20.4224 18.2308 20.7396 17.4651 20.7396 16.6667C20.7396 15.8683 20.4224 15.1025 19.8579 14.538C19.2933 13.9734 18.5276 13.6562 17.7292 13.6562ZM14.8958 22.1562C14.8489 22.1562 14.8038 22.1749 14.7706 22.2081C14.7374 22.2413 14.7188 22.2864 14.7188 22.3333V40.75C14.7188 40.8477 14.7981 40.9271 14.8958 40.9271H20.5625C20.6095 40.9271 20.6545 40.9084 20.6877 40.8752C20.7209 40.842 20.7396 40.797 20.7396 40.75V22.3333C20.7396 22.2864 20.7209 22.2413 20.6877 22.2081C20.6545 22.1749 20.6095 22.1562 20.5625 22.1562H14.8958ZM24.1042 22.1562C24.0572 22.1562 24.0122 22.1749 23.9789 22.2081C23.9457 22.2413 23.9271 22.2864 23.9271 22.3333V40.75C23.9271 40.8477 24.0064 40.9271 24.1042 40.9271H29.7708C29.8178 40.9271 29.8628 40.9084 29.896 40.8752C29.9293 40.842 29.9479 40.797 29.9479 40.75V30.8333C29.9479 30.1289 30.2278 29.4532 30.7259 28.9551C31.2241 28.4569 31.8997 28.1771 32.6042 28.1771C33.3086 28.1771 33.9843 28.4569 34.4824 28.9551C34.9806 29.4532 35.2604 30.1289 35.2604 30.8333V40.75C35.2604 40.8477 35.3398 40.9271 35.4375 40.9271H41.1042C41.1511 40.9271 41.1962 40.9084 41.2294 40.8752C41.2626 40.842 41.2812 40.797 41.2812 40.75V28.5383C41.2812 25.1001 38.2921 22.4113 34.8708 22.7215C33.8124 22.8186 32.7756 23.0805 31.7981 23.4978L29.9479 24.2912V22.3333C29.9479 22.2864 29.9293 22.2413 29.896 22.2081C29.8628 22.1749 29.8178 22.1562 29.7708 22.1562H24.1042Z" fill="black" />
                    </svg>
                  </a>

                </div>
              </div >

              <div className="min-w-48 mt-8 sm:mt-0 lg:mt-2">
                <h2 className='text-[22px] sm:text-[28px] xl:text-[34px] font-bold mb-5'> {tr["contact_us"]}: {" "}</h2>
                <div className="mt-2 -mx-1 ">
                  <a href={footer_links.whatsapp_link} className="w-[227px] h-[227px] sm:w-full  sm:h-full sm:aspect-[1/1] " target="_blank" rel="noopener noreferrer">
                    <img src={footer_qr_code} alt="QR Code" className="w-48 h-48" />
                  </a>
                </div>
              </div>

            </div>


          </div>
          <ContactUsForm page="contact-us" />
        </div>

        {contactUsLocationsList && contactUsLocationsList.length > 0 ? (
          contactUsLocationsList.map((loc, idx) => {
            const iframeSrc = (loc && loc.iframe) ? loc.iframe : map_iframe_src;
            const address = (loc && loc.address) ? (loc.address[lang] || loc.address['en'] || '') : contact_info.contact_address;
            return (
              <div key={idx} className="container rounded-3xl overflow-hidden mt-20 relative">
                <div className="absolute z-40 bg-gray-500 w-full h-full opacity-30 pointer-events-none"></div>

                <iframe src={iframeSrc ?? "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3986.8031352227445!2d-73.90537430980453!3d40.77452241274852!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2z2YbZitmI2YrZiNix2YPYjCDYp9mE2YjZhNin2YrYp9iqINin2YTZhdiq2K3Yr9ip!5e0!3m2!1sar!2seg!4v1751453688922!5m2!1sar!2seg"}
                  width="100%" height="770" style={{ border: 0 }} allowFullScreen loading="lazy" referrerPolicy="no-referrer-when-downgrade"
                  className=" ">
                </iframe>


                <div className="absolute rounded-3xl bg-[#E5E7EB] w-full lg:h-34 bottom-0 left-0 z-50 flex gap-5 max-lg:flex-col lg:items-center justify-between p-10">
                  <div className="lg:w-1/2">
                    <div className="flex items-center gap-4">
                      <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17 18.417C19.3472 18.417 21.25 16.5142 21.25 14.167C21.25 11.8198 19.3472 9.91699 17 9.91699C14.6528 9.91699 12.75 11.8198 12.75 14.167C12.75 16.5142 14.6528 18.417 17 18.417Z" stroke="black" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
                        <path d="M16.9974 2.8335C13.9916 2.8335 11.1089 4.02754 8.98352 6.15295C6.85811 8.27836 5.66406 11.161 5.66406 14.1668C5.66406 16.8472 6.23356 18.601 7.78906 20.5418L16.9974 31.1668L26.2057 20.5418C27.7612 18.601 28.3307 16.8472 28.3307 14.1668C28.3307 11.161 27.1367 8.27836 25.0113 6.15295C22.8859 4.02754 20.0032 2.8335 16.9974 2.8335Z" stroke="black" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
                      </svg>
                      <p dangerouslySetInnerHTML={{ __html: address.replace(/\\n/g, "<br />") }} />
                    </div>
                  </div>
                </div>

              </div>
            )
          })
        ) : (
          <div className="container rounded-3xl overflow-hidden mt-20 relative">
            <div className="absolute z-40 bg-gray-500 w-full h-full opacity-30 pointer-events-none"></div>

            <iframe src={map_iframe_src ?? "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3986.8031352227445!2d-73.90537430980453!3d40.77452241274852!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2z2YbZitmI2YrZiNix2YPYjCDYp9mE2YjZhNin2YrYp9iqINin2YTZhdiq2K3Yr9ip!5e0!3m2!1sar!2seg!4v1751453688922!5m2!1sar!2seg"}
              width="100%" height="770" style={{ border: 0 }} allowFullScreen loading="lazy" referrerPolicy="no-referrer-when-downgrade"
              className=" ">
            </iframe>


            <div className="absolute rounded-3xl bg-[#E5E7EB] w-full lg:h-34 bottom-0 left-0 z-50 flex gap-5 max-lg:flex-col lg:items-center justify-between p-10">
              <div className="lg:w-1/2">
                <div className="flex items-center gap-4">
                  <svg width="34" height="34" viewBox="0 0 34 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17 18.417C19.3472 18.417 21.25 16.5142 21.25 14.167C21.25 11.8198 19.3472 9.91699 17 9.91699C14.6528 9.91699 12.75 11.8198 12.75 14.167C12.75 16.5142 14.6528 18.417 17 18.417Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M16.9974 2.8335C13.9916 2.8335 11.1089 4.02754 8.98352 6.15295C6.85811 8.27836 5.66406 11.161 5.66406 14.1668C5.66406 16.8472 6.23356 18.601 7.78906 20.5418L16.9974 31.1668L26.2057 20.5418C27.7612 18.601 28.3307 16.8472 28.3307 14.1668C28.3307 11.161 27.1367 8.27836 25.0113 6.15295C22.8859 4.02754 20.0032 2.8335 16.9974 2.8335Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  <p>{contact_info.contact_address}</p>
                </div>
              </div>
            </div>

          </div>
        )}
      </div>

    </Layout>
  );
}
