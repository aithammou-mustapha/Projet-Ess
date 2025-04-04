import React, { useEffect, useState } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCartShopping } from '@fortawesome/free-solid-svg-icons';

const PanierIcon = () => {
  const [count, setCount] = useState(0);

  // 🔁 Met à jour le compteur depuis le localStorage
  const updateCount = () => {
    const panier = JSON.parse(localStorage.getItem('panier')) || [];
    setCount(panier.length);
  };

  useEffect(() => {
    updateCount(); // Initial load

    // ✅ Met à jour dès qu’un groupe est ajouté ou supprimé
    window.addEventListener("panierUpdated", updateCount);

    return () => {
      window.removeEventListener("panierUpdated", updateCount);
    };
  }, []);

  return (
    <div className="icone-panier">
      <FontAwesomeIcon icon={faCartShopping} />
      {count > 0 && <span className="panier-compteur">{count}</span>}
    </div>
  );
};

export default PanierIcon;
