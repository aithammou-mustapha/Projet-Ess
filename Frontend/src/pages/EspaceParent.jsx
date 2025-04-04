import React, { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import Swal from "sweetalert2";
import axios from "axios";
import "../assets/styles/Espace.css";
import HeaderEspace from "../components/HeaderEspace";
import CalendrierParent from "../components/CalendrierParent";

const EspaceParent = () => {
  const [nomParent, setNomParent] = useState("");
  const navigate = useNavigate();

  useEffect(() => {
    const utilisateur = JSON.parse(localStorage.getItem("utilisateur"));

    if (!utilisateur) {
      Swal.fire("Erreur", "Vous devez vous connecter", "error").then(() => {
        navigate("/connexion");
      });
      return;
    }

    console.log("✅ Utilisateur connecté :", utilisateur);
    setNomParent(`${utilisateur.prenomParent} ${utilisateur.nomParent}`);
  }, []);

  return (
    <>
      <HeaderEspace />
      <div className="espace-conteneur">
        <div className="espace-boite">
          <h1 className="titre-espace">Espace Parent</h1>
          <p className="texte-bienvenue">
            Bonjour <strong>{nomParent}</strong>, voici le planning de vos enfants :
          </p>
          <CalendrierParent />
        </div>
      </div>
    </>
  );
};

export default EspaceParent;
