// ✅ Fichier : src/pages/EspaceProf.jsx
import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import Swal from "sweetalert2";
import axios from "axios";
import "../assets/styles/Espace.css";
import HeaderEspace from "../components/HeaderEspace";
import CalendrierProfesseur from "../components/CalendrierProfesseur"; // ✅ nouveau composant prof

const EspaceProf = () => {
  const [nomProf, setNomProf] = useState("");
  const navigate = useNavigate();

  useEffect(() => {
    const utilisateur = JSON.parse(localStorage.getItem("utilisateur"));

    if (!utilisateur) {
      Swal.fire("Erreur", "Vous devez vous connecter", "error").then(() => {
        navigate("/connexion");
      });
      return;
    }

    console.log("✅ Professeur connecté :", utilisateur);
    setNomProf(`${utilisateur.prenomProf} ${utilisateur.nomProf}`);
  }, []);

  return (
    <>
      <HeaderEspace />
      <div className="espace-conteneur">
        <div className="espace-boite">
          <h1 className="titre-espace">Espace Professeur</h1>
          <p className="texte-bienvenue">
            Bonjour <strong>{nomProf}</strong>, voici le planning de vos cours :
          </p>
          <CalendrierProfesseur /> {/* ✅ composant qui affiche le planning du prof */}
        </div>
      </div>
    </>
  );
};

export default EspaceProf;
