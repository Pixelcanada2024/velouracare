import React, { useEffect, useRef, useState } from "react";
import Button from "@/components/ui/Button";
import { useForm, router } from "@inertiajs/react";
import axios from "axios";
// start lang
import { useTranslation } from "@/contexts/TranslationContext";
// end lang

export default function EmailVerification({ onChangeStep, email, setUserInput }) {
  // Start language
  const [{ lang, currency, tr }, _setTranslation] = useTranslation();
  // end lang

  const [code, setCode] = useState(["", "", "", "", "", ""]);
  const [isVerifying, setIsVerifying] = useState(false);
  const [validationError, setValidationError] = useState("");
  const inputsRef = useRef([]);
  const verificationTimeoutRef = useRef(null);

  const { post, processing, setData } = useForm({
    email: email,
    code: ""
  });

  // Live verification when code is complete
  useEffect(() => {
    const fullCode = code.join("");
    setData('code', fullCode);

    // Clear any existing timeout
    if (verificationTimeoutRef.current) {
      clearTimeout(verificationTimeoutRef.current);
    }

    // Only verify if all 6 digits are entered
    if (fullCode.length === 6) {
      setValidationError(""); // Clear previous errors

      // Debounce the verification by 500ms
      verificationTimeoutRef.current = setTimeout(() => {
        verifyCodeLive(fullCode);
      }, 500);
    } else {
      setValidationError(""); // Clear errors if incomplete
    }

    return () => {
      if (verificationTimeoutRef.current) {
        clearTimeout(verificationTimeoutRef.current);
      }
    };
  }, [code]);

  // Live verification function
  const verifyCodeLive = async (fullCode) => {
    setIsVerifying(true);

    try {
      const response = await axios.post(route("password.verify-code-live"), {
        email: email,
        code: fullCode
      });

      if (response.data.success) {
        // Code is valid, proceed to next step
        setValidationError("");
        setUserInput((prev) => ({ ...prev, code: fullCode }));
        onChangeStep("resetPassword");
      }
    } catch (error) {
      if (error.response?.data) {
        // Show validation error
        setValidationError(error.response.data.message || tr['invalid_code']);
      }
    } finally {
      setIsVerifying(false);
    }
  };

  const handleChange = (value, index) => {
    if (!/^\d*$/.test(value)) return;

    const newCode = [...code];
    newCode[index] = value;
    setCode(newCode);

    if (value && index < 5) {
      inputsRef.current[index + 1].focus();
    }
  };

  const handleKeyDown = (e, index) => {
    if (e.key === "Backspace" && !code[index] && index > 0) {
      inputsRef.current[index - 1].focus();
    }
  };

  const handlePaste = (e) => {
    e.preventDefault();
    const pasteData = e.clipboardData.getData("Text").replace(/\D/g, '').slice(0, 6);
    const newCode = [...Array(6)].map((_, i) => pasteData[i] || "");
    setCode(newCode);

    if (pasteData.length > 0) {
      const focusIndex = Math.min(pasteData.length, 5);
      inputsRef.current[focusIndex].focus();
    }
  };


  // Handle resend code
  const handleResendCode = () => {
    setValidationError("");
    setCode(["", "", "", "", "", ""]);

    post(route("password.email"), { email }, {
      preserveState: true,
      preserveScroll: true,
    });
  };

  // Determine input border color
  const getInputClassName = () => {
    const baseClass = "text-xl text-center bg-white border rounded-lg h-12 w-12 focus:outline-none transition-colors";

    if (validationError) {
      return `${baseClass} border-red-500 focus:ring-2 focus:ring-red-500`;
    }

    if (isVerifying) {
      return `${baseClass} border-yellow-400 focus:ring-2 focus:ring-yellow-400`;
    }

    return `${baseClass} border-gray-300 focus:ring-2 focus:ring-primary-500`;
  };

  return (
    <div className="w-full mx-auto min-h-[300px] flex flex-col">
      <div className="flex items-center mb-6">
        <h2 className="flex-grow text-lg font-medium text-center sm:text-2xl text-primary-500">
          {tr['enter_verification_code']}
        </h2>
      </div>

      <div className="mb-8 text-center">
        <p className="text-sm text-gray-600 sm:text-base">
          {tr['enter_verification_code_desc']}
          <strong> {email} </strong>
        </p>
      </div>

      <div className="flex flex-col flex-grow">
        <div
          className="flex justify-center mb-2 space-x-3"
          onPaste={handlePaste}
          dir="ltr"
        >
          {code.map((digit, idx) => (
            <input
              key={idx}
              type="text"
              inputMode="numeric"
              maxLength={1}
              value={digit}
              ref={(el) => (inputsRef.current[idx] = el)}
              onChange={(e) => handleChange(e.target.value, idx)}
              onKeyDown={(e) => handleKeyDown(e, idx)}
              className={getInputClassName()}
            />
          ))}
        </div>

        {/* Error message */}
        {validationError && (
          <div className="mb-4 text-center">
            <p className="text-sm text-red-500 font-medium">
              {validationError}
            </p>
          </div>
        )}

        {/* Verifying indicator */}
        {isVerifying && (
          <div className="mb-4 text-center">
            <p className="text-sm text-yellow-600">
              {tr['verifying'] || 'Verifying...'}
            </p>
          </div>
        )}

        <div className="mb-8 text-center">
          <p className="text-xs sm:text-sm text-primary-500">
            {tr['code_not_received']}{" "}
            <button
              type="button"
              onClick={handleResendCode}
              disabled={processing}
              className="font-medium text-blue-400 underline underline-offset-4 cursor-pointer uppercase"
            >
              {processing ? tr['sending'] : tr['resend_code']}
            </button>
          </p>
        </div>
      </div>
    </div>
  );
}
