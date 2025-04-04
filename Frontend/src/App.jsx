import React from 'react';
import { BrowserRouter, Routes, Route } from 'react-router-dom';
import Accueil from './pages/Accueil';
import PageCours from './pages/PageCours';
import PageStages from './pages/PageStages';
import Inscription from './pages/Inscription';
import Connexion from './pages/Connexion';
import EspaceParent from './pages/EspaceParent';
import EspaceProf from './pages/EspaceProf';
import ModifierProfilParent from "./pages/ModifierProfilParent";
import ModifierProfilProf from "./pages/ModifierProfilProf";
import ChangerMotDePasseParent from "./pages/ChangerMotDePasseParent";
import ChangerMotDePasseProf from "./pages/ChangerMotDePasseProf";
import Panier from './pages/PagePanier'; 
function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<Accueil />} />
        <Route path="/cours" element={<PageCours />} />
        <Route path="/stages" element={<PageStages />} />
        <Route path="/inscription" element={<Inscription />} />
        <Route path="/connexion" element={<Connexion />} />
        <Route path="/espace-parent" element={<EspaceParent />} />
        <Route path="/espace-prof" element={<EspaceProf />} />
        <Route path="/modifier-profil-parent" element={<ModifierProfilParent />} />
        <Route path="/modifier-profil-prof" element={<ModifierProfilProf />} />
        <Route path="/changer-mot-de-passe-parent" element={<ChangerMotDePasseParent />} />
        <Route path="/changer-mot-de-passe-prof" element={<ChangerMotDePasseProf />} />
        <Route path="/panier" element={<Panier />} /> 
      </Routes>
    </BrowserRouter>
  );
}

export default App;
