
import { useEffect, useState } from 'react';
import { usePage } from '@inertiajs/react';
import PopUpMarketingModal from '@/components/Shared/PopUpMarketingModal';
import CookieVerificationModal from '@/components/Shared/CookieVerificationModal';
import Modal from '@/components/ui/Modal';

export default function Modals() {

  // State for modals
  const [showCookieModal, setShowCookieModal] = useState(false);
  const [showMarketingModal, setShowMarketingModal] = useState(false);
  const [isModalOpen, setModalOpen] = useState(false);

  const pageFlash = usePage().props.flash;

  // which modal to show
  useEffect(() => {
    const isVerified = localStorage.getItem("CookieVerification");
    if (!isVerified) {
      setShowCookieModal(true);
      setShowMarketingModal(false);
    } else {
      setShowCookieModal(false);
      if (!document.cookie.includes("popUpMarketingModal=true")) {
        setShowMarketingModal(true);
      }
    }
  }, []);

  // When cookie modal close, open marketing modal if needed
  const handleCookieModalClose = () => {
    setShowCookieModal(false);
    // Only show marketing modal if cookie not set
    if (!document.cookie.includes("popUpMarketingModal=true")) {
      setShowMarketingModal(true);
    }
  };

  // When marketing modal close
  const handleMarketingModalClose = () => {
    setShowMarketingModal(false);
  };

  // Flash modal logic
  useEffect(() => {
    if (pageFlash.title && pageFlash.message) {
      setModalOpen(true);
    }
  }, [pageFlash]);

  let icon = null;
  if (pageFlash.status === "error") {
    icon = (
      <>
        {/* Error SVG */}
        <svg width="80" height="80" viewBox="0 0 97 97" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M53.6273 15.5077L87.7068 75.4769C88.2264 76.3914 88.5 77.4287 88.5 78.4846C88.5 79.5405 88.2264 80.5778 87.7068 81.4923C87.1872 82.4067 86.4398 83.1661 85.5398 83.694C84.6397 84.222 83.6187 84.5 82.5795 84.5H14.4205C13.3812 84.5 12.3603 84.222 11.4602 83.694C10.5602 83.1661 9.81281 82.4067 9.29318 81.4923C8.77355 80.5778 8.49999 79.5405 8.5 78.4846C8.50001 77.4287 8.77358 76.3914 9.29322 75.4769L43.3727 15.5077C45.6502 11.4974 51.3459 11.4974 53.6273 15.5077ZM48.5 63.0372C47.4532 63.0372 46.4492 63.4597 45.709 64.2117C44.9687 64.9638 44.5529 65.9838 44.5529 67.0474C44.5529 68.111 44.9687 69.131 45.709 69.8831C46.4492 70.6351 47.4532 71.0577 48.5 71.0577C49.5468 71.0577 50.5508 70.6351 51.291 69.8831C52.0313 69.131 52.4471 68.111 52.4471 67.0474C52.4471 65.9838 52.0313 64.9638 51.291 64.2117C50.5508 63.4597 49.5468 63.0372 48.5 63.0372ZM48.5 34.9654C47.5332 34.9655 46.6001 35.3262 45.8776 35.9789C45.1552 36.6316 44.6936 37.531 44.5805 38.5065L44.5529 38.9757V55.0167C44.554 56.0388 44.9392 57.0219 45.6298 57.7652C46.3205 58.5084 47.2644 58.9557 48.2687 59.0156C49.273 59.0755 50.2619 58.7435 51.0334 58.0874C51.8049 57.4314 52.3007 56.5008 52.4195 55.4859L52.4471 55.0167V38.9757C52.4471 37.9121 52.0313 36.8921 51.291 36.14C50.5508 35.3879 49.5468 34.9654 48.5 34.9654Z" fill="#E40707" />
        </svg>
      </>
    );
  } else if (pageFlash.status !== "no-icon") {
    icon = (
      <>
        {/* Success SVG */}
        <svg width="70" height="70" viewBox="0 0 97 97" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M42.9 66.9L71.1 38.7L65.5 33.1L42.9 55.7L31.5 44.3L25.9 49.9L42.9 66.9ZM48.5 88.5C42.9666 88.5 37.7667 87.4493 32.9 85.348C28.0333 83.2466 23.8 80.3973 20.2 76.8C16.6 73.2027 13.7507 68.9693 11.652 64.1C9.55334 59.2307 8.50267 54.0307 8.50001 48.5C8.49734 42.9693 9.548 37.7693 11.652 32.9C13.756 28.0307 16.6053 23.7973 20.2 20.2C23.7947 16.6027 28.028 13.7533 32.9 11.652C37.772 9.55067 42.972 8.5 48.5 8.5C54.028 8.5 59.228 9.55067 64.1 11.652C68.972 13.7533 73.2053 16.6027 76.8 20.2C80.3946 23.7973 83.2453 28.0307 85.352 32.9C87.4586 37.7693 88.508 42.9693 88.5 48.5C88.492 54.0307 87.4413 59.2307 85.348 64.1C83.2546 68.9693 80.4053 73.2027 76.8 76.8C73.1946 80.3973 68.9613 83.248 64.1 85.352C59.2386 87.456 54.0386 88.5053 48.5 88.5Z" fill="#2BB900" />
        </svg>
      </>
    );
  }

  return (
    <>
      {/* Cookie Verification Modal */}
      <CookieVerificationModal isOpen={showCookieModal} onClose={handleCookieModalClose} />

      {/* Pop Up Marketing Modal */}
      <PopUpMarketingModal isOpen={showMarketingModal} onClose={handleMarketingModalClose} />

      <Modal
        isOpen={isModalOpen}
        onClose={() => setModalOpen(false)}
        status={pageFlash.status ? pageFlash.status : "success"}
        title={pageFlash.title ? pageFlash.title : "Changes Saved!"}
        description={pageFlash.message ? pageFlash.message : "Your changes have been saved successfully."}
        type={pageFlash.type ? pageFlash.type : ""}
      />
    </>
  );
}
