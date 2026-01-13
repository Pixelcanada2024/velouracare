import React from 'react';

export default function BusinessTypeCard({ value, label, description, icon, selected, onChange }) {
  return (
    <label
      className={`border rounded-md p-4 text-center cursor-pointer transition-all duration-200
        ${selected ? 'border-primary-300 bg-primary-50 text-black' : 'border-gray-300 text-gray-400'}
        hover:shadow-md`}
    >
      <input
        type="radio"
        value={value}
        checked={selected}
        onChange={() => onChange(value)}
        className="hidden"
      />
      <div className="flex flex-col justify-center items-center gap-2">
        <div className="w-8 h-8 text-center rtl:ml-3 ltr:mr-3">{icon}</div>
        <h3 className={`font-semibold ${selected ? 'text-black' : 'text-gray-500'}`}>{label}</h3>
        <p className="text-sm">{description}</p>
      </div>
    </label>
  );
}
