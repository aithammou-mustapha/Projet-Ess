import React, { useState } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import '../assets/styles/HeaderEspace.css';
import logo from '../assets/images/logoess.png';
import profilIcon from '../assets/images/profil.png';

const HeaderEspace = () => {
  const navigate = useNavigate();
  const [menuOuvert, setMenuOuvert] = useState(false);

  const utilisateur = JSON.parse(localStorage.getItem("utilisateur"));
  const rôle = localStorage.getItem("rôle");

  const handleLogout = () => {
    localStorage.removeItem("utilisateur");
    localStorage.removeItem("rôle");
    navigate("/connexion");
  };

  const toggleMenu = () => {
    setMenuOuvert(!menuOuvert);
  };

  const allerTableauDeBord = () => {
    if (rôle === "parent") navigate("/espace-parent");
    else if (rôle === "professeur") navigate("/espace-prof");
    else navigate("/connexion");
  };

  const lienModifierProfil = rôle === "parent"
    ? "/modifier-profil-parent"
    : "/modifier-profil-prof";

  const lienChangerMotDePasse = rôle === "parent"
    ? "/changer-mot-de-passe-parent"
    : "/changer-mot-de-passe-prof";

  return (
    <nav className="barre-navigation">
      {/* ✅ Logo */}
      <div className="logo">
        <Link to="/"><img src={logo} alt="logo ess" /></Link>
      </div>

      {/* ✅ Liens de navigation */}
       <ul className="liens-navigation">
          <li><Link to="/">Accueil</Link></li>
          <li><Link to="/cours">Cours hebdomadaires</Link></li>
          <li><Link to="/stages">Stages</Link></li>
        </ul>

      {/* ✅ Bouton tableau de bord + icône profil */}
      <div className="actions-droite">
        <button className="bouton-connexion" onClick={allerTableauDeBord}>
          Tableau de bord
        </button>

        <div className="profil-container" onClick={toggleMenu}>
          <img src={profilIcon} alt="Profil" className="profil-icone" />
          {menuOuvert && (
            <div className="menu-profil">
              <Link to={lienModifierProfil}>Modifier le profil</Link>
              <Link to={lienChangerMotDePasse}>Changer le mot de passe</Link>
              <button onClick={handleLogout}>Déconnexion</button>
            </div>
          )}
        </div>
      </div>
    </nav>
  );
};

export default HeaderEspace;
