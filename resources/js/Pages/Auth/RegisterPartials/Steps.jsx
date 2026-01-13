// Steps.js
import React, { useState } from 'react';
import Button from "@/components/ui/Button";
import { usePage } from "@inertiajs/react";

// start lang
import { useTranslation } from '@/contexts/TranslationContext';
// end lang

export default function Steps({
  steps,
  currentStep,
  setCurrentStep,
  processing,
  data,
  setError,
  clearErrors,
  errorsReactive
}) {
  // Start language
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  // end lang

  const errors = errorsReactive ?? usePage().props.errors;

  const validateCurrentStep = (step) => {
    clearErrors();
    let isValid = true;

    switch (step) {
      case 1: // Personal Information
        if (!data.name) {
          setError("name", tr['name_required']);
          isValid = false;
        }
        if (!data.email) {
          setError("email", tr['email_required']);
          isValid = false;
        } else if (!/\S+@\S+\.\S+/.test(data.email)) {
          setError("email", tr['email_invalid']);
          isValid = false;
        }
        if (!data.phone) {
          setError("phone", tr['phone_required']);
          isValid = false;
        }
        if (!data.password) {
          setError("password", tr['password_required']);
          isValid = false;
        } else if (data.password.length < 8) {
          setError("password", tr['password_short']);
          isValid = false;
        }
        if (!data.country_id) {
          setError("country_id", tr['country_required']);
          isValid = false;
        }
        if (!data.city) {
          setError("city", tr['city_required']);
          isValid = false;
        }
        if (!data.agree) {
          setError("agree", tr['agree_required']);
          isValid = false;
        }
        break;

      case 2: // Business Information
        if (!data.company_name) {
          setError("company_name", tr['company_required']);
          isValid = false;
        }
        if (!data.store_link) {
          setError("store_link", tr['store_link_required']);
          isValid = false;
        }
        if (!data.business_id) {
          setError("business_id", tr['business_id_required']);
          isValid = false;
        }
        if (!data.business_proof) {
          setError("business_proof", tr['business_proof_required']);
          isValid = false;
        }
        if (!data.wholesale_agree) {
          setError("wholesale_agree", tr['wholesale_agree_required']);
          isValid = false;
        }
        break;

      default:
        break;
    }

    return isValid;
  };

  // Step navigation functions
  const nextStep = () => {
    if (currentStep < 2) {
      // Validate current step before proceeding
      const isValid = validateCurrentStep(currentStep);
      if (isValid) {
        setCurrentStep(currentStep + 1);
      }
    }
  };

  const prevStep = () => {
    if (currentStep > 1) {
      setCurrentStep(currentStep - 1);
    }
  };

  const goToStep = (step) => {
    if (step >= 1 && step <= 2) {
      // Allow freely going backward
      if (step < currentStep) {
        setCurrentStep(step);
      }
      // For forward navigation, validate all steps in between
      else if (step > currentStep) {
        let canProceed = true;
        for (let i = currentStep; i < step; i++) {
          if (!validateCurrentStep(i)) {
            canProceed = false;
            break;
          }
        }
        if (canProceed) {
          setCurrentStep(step);
        }
      }
    }
  };

  // Progress indicator component
  const ProgressIndicator = () => (
    <div className="flex items-center justify-center gap-20 mb-8">
      {/* Step 1 */}
      <div className="flex items-center">
        <div className='flex flex-col items-center'>
          <div
            className={`w-8 h-8 relative rounded-full flex items-center justify-center text-sm font-medium cursor-pointer transition-colors text-white ${currentStep === 1
              ? "bg-primary-500"
              : "bg-[#F0F0F0]"
              }`}
            onClick={() => goToStep(1)}
          >
            <div className='absolute w-44 h-[0.5px] top-1/2 rtl:right-[100%] ltr:left-[100%] bg-[#D4D7DD]'></div>
            1
          </div>
          <div className="mt-2 text-center">
            <span className={`text-sm ${currentStep === 1
              ? "text-primary-500 font-semibold"
              : "text-gray-500"
              }`}>
              {steps[0].title}
            </span>
          </div>
        </div>
        {/* Connector line */}
      </div>


      {/* Step 2 */}
      <div className="flex items-center">
        <div className='flex flex-col items-center'>
          <div
            className={`w-8 h-8 relative rounded-full flex items-center justify-center text-sm font-medium cursor-pointer transition-colors text-white ${currentStep === 2
              ? "bg-primary-500 "
              : "bg-[#F0F0F0]"
              }`}
            onClick={() => goToStep(2)}
          >
            2

          </div>
          <div className="mt-2 text-center">
            <span className={`text-sm ${currentStep === 2
              ? "text-primary-500 font-semibold"
              : "text-gray-500"
              }`}>
              {steps[1].title}
            </span>
          </div>
        </div>
      </div>
    </div>
  );

  // Navigation buttons component
  const NavigationButtons = () => (
    <div className="gap-4 mt-8">
      <div className="flex flex-col justify-between gap-4">
        {currentStep < 2 ? (
          <Button
            type="button"
            variant="primary"
            onClick={nextStep}
            className='w-full sm:min-w-[250px]'
          >
            {tr['continue']}
          </Button>
        ) : (
          <Button
            type="submit"
            variant="primary"
            fullWidth
            disabled={processing}
          >
            {processing ? tr['submitting'] : tr['submit_message']}
          </Button>
        )}
        {currentStep > 1 && (
          <Button
            type="button"
            variant="outline"
            onClick={prevStep}
            className='w-full sm:min-w-[250px]'
          >
            {tr['back_to_previous']}
          </Button>
        )}
      </div>
    </div>
  );

  return (
    <>
      <ProgressIndicator />
      {currentStep === 1 ? steps[0].component : steps[1].component}

      {/* Errors */}
      {Object.keys(errors).length > 0 && (
          <div className="my-5 bg-red-50 p-4 space-y-1">
            {Object.entries(errors).map(([key, value]) => (
              <div key={key} className="text-sm text-error">
                {value}
              </div>
            ))}
          </div>
        )}

      <NavigationButtons />
    </>
  );
}

export { Steps };
