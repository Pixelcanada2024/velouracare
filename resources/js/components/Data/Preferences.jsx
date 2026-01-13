const gulfCountries = [
  { value: "sa", label: "Saudi Arabia", labelTrans: { en: "Saudi Arabia", ar: "المملكة العربية السعودية"}, currency: "SAR" },
  { value: "ae", label: "United Arab Emirates", labelTrans: { en: "United Arab Emirates", ar: "الإمارات العربية المتحدة" }, currency: "AED" },
  { value: "kw", label: "Kuwait", labelTrans: { en: "Kuwait", ar: "الكويت" }, currency: "KWD" },
  { value: "qa", label: "Qatar", labelTrans: { en: "Qatar", ar: "قطر" }, currency: "QAR" },
  { value: "om", label: "Oman", labelTrans: { en: "Oman", ar: "عمان" }, currency: "OMR" },
  { value: "bh", label: "Bahrain", labelTrans: { en: "Bahrain", ar: "البحرين" }, currency: "BHD" },
  // { value: 'us', label: 'United States', currency: 'USD' },
];

const languages = [
  { value: "ar", label: "العربية" , labelTrans: { en: "Arabic", ar: "العربية" }},
  { value: "en", label: "English" , labelTrans: { en: "English", ar: "الإنجليزية" }},
];

const currencies = [
  { value: "SAR", label: "SAR (ر.س)" },
  { value: "AED", label: "AED (د.إ)" },
  { value: "KWD", label: "KWD (د.ك)" },
  { value: "QAR", label: "QAR (ر.ق)" },
  { value: "OMR", label: "OMR (ر.ع)" },
  { value: "BHD", label: "BHD (ب.د)" },
  // { value: "USD", label: "USD (د.أ)" },
];

export default {
  gulfCountries,
  languages,
  currencies
}
