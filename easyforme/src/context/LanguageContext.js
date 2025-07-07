import React, { createContext, useState, useContext } from 'react';

const LanguageContext = createContext();

export const LanguageProvider = ({ children }) => {
  const [language, setLanguage] = useState('Korean');
  const [languageCode, setLanguageCode] = useState('ko');

  const updateLanguage = (newLanguage, newLanguageCode) => {
    setLanguage(newLanguage);
    setLanguageCode(newLanguageCode);
  };

  return (
    <LanguageContext.Provider value={{ language, languageCode, updateLanguage }}>
      {children}
    </LanguageContext.Provider>
  );
};

export const useLanguage = () => {
  const context = useContext(LanguageContext);
  if (!context) {
    throw new Error('useLanguage must be used within a LanguageProvider');
  }
  return context;
}; 