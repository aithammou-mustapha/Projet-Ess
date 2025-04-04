import React, { useState, useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import '../assets/styles/Accueil.css';
import PanierIcon from './PanierIcon';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faBars } from '@fortawesome/free-solid-svg-icons';

const Header = () => {
  const navigate = useNavigate();
  const [menuOuvert, setMenuOuvert] = useState(false);
  const [isMobile, setIsMobile] = useState(false);

  const utilisateur = JSON.parse(localStorage.getItem("utilisateur"));
  const rôle = localStorage.getItem("rôle");

  // ✅ Détection mobile
  useEffect(() => {
    const handleResize = () => {
      setIsMobile(window.innerWidth <= 768);
    };

    handleResize(); // Initial
    window.addEventListener('resize', handleResize);
    return () => window.removeEventListener('resize', handleResize);
  }, []);

  const allerTableauDeBord = () => {
    if (rôle === "parent") navigate("/espace-parent");
    else if (rôle === "professeur") navigate("/espace-prof");
    else navigate("/connexion");
  };

  return (
    <nav className="barre-navigation">
      {/* ✅ Logo */}
      <div className="logo">
        <Link to="/"><img src="src/assets/images/logoess.png" alt="logo ess" /></Link>
      </div>

      {/* ✅ Burger */}
      <div className="menu-burger" onClick={() => setMenuOuvert(!menuOuvert)}>
        <FontAwesomeIcon icon={faBars} />
      </div>

      {/* ✅ Liens + bouton/panier uniquement sur mobile */}
      <ul className={`liens-navigation ${menuOuvert ? 'active' : ''}`}>
        <li><Link to="/" onClick={() => setMenuOuvert(false)}>Accueil</Link></li>
        <li><Link to="/cours" onClick={() => setMenuOuvert(false)}>Cours hebdomadaires</Link></li>
        <li><Link to="/stages" onClick={() => setMenuOuvert(false)}>Stages</Link></li>

        {/* ✅ Bouton + panier affichés seulement en MOBILE */}
        {isMobile && (
          <>
            <li>
              <button className="bouton-connexion" onClick={() => { setMenuOuvert(false); allerTableauDeBord(); }}>
                {utilisateur ? "TABLEAU DE BORD" : "CONNEXION"}
              </button>
            </li>
            <li>
              <Link to="/panier" onClick={() => setMenuOuvert(false)}>
                <div className="icone-panier">
                  <PanierIcon />
                </div>
              </Link>
            </li>
          </>
        )}
      </ul>

      {/* ✅ Affiché uniquement sur ordi/tablette */}
      {!isMobile && (
        <div className="actions-droite">
          <button className="bouton-connexion" onClick={allerTableauDeBord}>
            {utilisateur ? "TABLEAU DE BORD" : "CONNEXION"}
          </button>
          <Link to="/panier">
            <div className="icone-panier">
              <PanierIcon />
            </div>
          </Link>
        </div>
      )}
    </nav>
  );
};

export default Header;
