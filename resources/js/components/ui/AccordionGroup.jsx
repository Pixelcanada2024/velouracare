import React, { useState } from 'react';

const AccordionGroup = ({ children }) => {
  const [openAccordion, setOpenAccordion] = useState(null);

  const handleAccordionToggle = (index) => {
    setOpenAccordion(openAccordion === index ? null : index);
  };

  return (
    <div className="space-y-4">
      {React.Children.map(children, (child, index) => {
        if (React.isValidElement(child)) {
          return React.cloneElement(child, {
            isOpen: openAccordion === index,
            onToggle: () => handleAccordionToggle(index),
            key: index
          });
        }
        return child;
      })}
    </div>
  );
};

export default AccordionGroup;
