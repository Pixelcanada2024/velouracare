import { useState, useEffect, useMemo } from "react";
import ArabicContentMobile from "./partials/ArabicContentMobile";
import ArabicContentTablet from "./partials/ArabicContentTablet";
import EnglishContentMobile from "./partials/EnglishContentMobile";
import EnglishContentTablet from "./partials/EnglishContentTablet";
import Logo from "./partials/Logo";
import SocialMedia from "./partials/SocialMedia";
import bg_mb from '/public/website-assets/imgs/bg-mb.png';
import bg_tb from '/public/website-assets/imgs/bg-tb.png';
import bg_pc from '/public/website-assets/imgs/bg-pc.png';

export default function ComingSoon() {
  const [isArabic, setIsArabic] = useState(false);
  const [bgImage, setBgImage] = useState(bg_mb);

  const toggleLanguage = () => setIsArabic(prev => !prev);

  useEffect(() => {
    const updateBg = () => {
      const width = window.innerWidth;
      if (width >= 1024) setBgImage(bg_pc);
      else if (width >= 640) setBgImage(bg_tb);
      else setBgImage(bg_mb);
    };

    updateBg();
    window.addEventListener("resize", updateBg);
    return () => window.removeEventListener("resize", updateBg);
  }, []);

  const Content = useMemo(() => {
    return isArabic ? (
      <div id="ar-section-container">
        <ArabicContentMobile />
        <ArabicContentTablet />
      </div>
    ) : (
      <div id="en-section-container">
        <EnglishContentMobile />
        <EnglishContentTablet />
      </div>
    );
  }, [isArabic]);

  return (
    <div
      id="main"
      className=" min-w-screen min-h-screen grid place-items-center bg-no-repeat bg-cover"
      style={{ backgroundImage: `url(${bgImage})` }}
    >
      <button
        onClick={toggleLanguage}
        className="fixed top-[5vh] right-[10vw] bg-black/90 hover:bg-black text-white rounded-xl py-2 px-6 font-bold z-50 cursor-pointer"
        id="language-toggle"
        aria-label="Toggle Language"
      >
        {isArabic ? "English" : "العربية"}
      </button>

      <div className="w-[90%] max-w-[90%] sm:max-w-[80%] md:max-w-[75%] lg:max-w-[50%] mx-auto">
        <Logo />
        {Content}
        <div className="flex justify-center gap-2 sm:gap-4 w-[300px] mx-auto my-3 sm:my-6 xl:my-8">
          <SocialMedia />
        </div>
      </div>
    </div>
  );
}
