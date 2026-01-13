import React, { createContext, useContext, useEffect, useState } from 'react';

const TranslationContext = createContext();

export const useTranslation = () => useContext(TranslationContext);

export const TranslationProvider = ({ children }) => {
  const translationState = useState({
    lang: "en",
    currency: "SAR",
    tr: {},
  });

  return (
    <TranslationContext.Provider value={ translationState }>
      {children}
    </TranslationContext.Provider>
  );
};
