import { useState } from "react";
import Layout from "@/components/Layout/Layout";
import Email from "./Partials/Email";
import EmailVerification from "./Partials/EmailVerification";
import ResetPassword from "./Partials/ResetPassword";
export default function ForgotPassword() {
  // State to track which screen is currently showing
  const [currentStep, setCurrentStep] = useState("init");


  // State to store user input for passing between pages
  const [userInput, setUserInput] = useState({
    email: "",
    phoneNumber: "",
    code: ""
  });

  // Render the appropriate screen based on current step
  const renderCurrentScreen = () => {
    switch (currentStep) {
      case "email":
        return (
          <Email
            onChangeStep={setCurrentStep}
            setUserInput={setUserInput}
          />
        );
      case "emailVerification":
        return (
          <EmailVerification
            onChangeStep={setCurrentStep}
            setUserInput={setUserInput}
            email={userInput.email}
          />
        );
      case "resetPassword":
        return (
          <ResetPassword
            onChangeStep={setCurrentStep}
            email={userInput.email}
            code={userInput.code}
          />
        );
      default:
        return <Email
          onChangeStep={setCurrentStep}
          setUserInput={setUserInput}
        />;
    }
  };

  return (
    <Layout ShowFooterSwiper={false} >
      <div className="mx-auto sm:container my-20">
        <div className="max-w-2xl mx-5 sm:mx-auto px-6 sm:px-14 py-10 rounded-2xl border  border-[#E5E7EB] bg-white  my-20 shadow-lg min-h-[300px]">
          {renderCurrentScreen()}
        </div>
      </div>
    </Layout>
  );
}
