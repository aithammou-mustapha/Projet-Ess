import React, { useState } from "react";
import Swal from "sweetalert2";
import axios from "axios";
import HeaderEspace from "../components/HeaderEspace";
import { useNavigate } from "react-router-dom";
import '../assets/styles/GroupesList.css';

const ChangerMotDePasseProf = () => {
  const utilisateur = JSON.parse(localStorage.getItem("utilisateur"));
  const navigate = useNavigate();
  const [nouveauMotDePasse, setNouveauMotDePasse] = useState("");

  const handleSubmit = async (e) => {
    e.preventDefault();

    if (nouveauMotDePasse.length < 8) {
      Swal.fire("Erreur", "Le mot de passe doit contenir au moins 8 caractères", "error");
      return;
    }

    try {
      const response = await axios.patch( // PATCH au lieu de PUT
        `https://localhost:8000/api/profs/${utilisateur.id}`,
        { motDePasse: nouveauMotDePasse },
        {
          headers: { "Content-Type": "application/merge-patch+json" },
          withCredentials: true,
        }
      );

      if (response.status === 200 || response.status === 204) {
        Swal.fire({
          icon: "success",
          title: "Mot de passe modifié avec succès",
          timer: 1500,
          showConfirmButton: false,
        }).then(() => {
          navigate("/espace-prof");
        });
      } else {
        Swal.fire("Erreur", "Échec de la mise à jour (réponse inattendue)", "error");
      }
    } catch (error) {
      console.error("Erreur API :", error);
      Swal.fire("Erreur", "Impossible de modifier le mot de passe", "error");
    }
  };

  const revenir = () => {
    navigate("/espace-prof");
  };

  return (
    <>
      <HeaderEspace />
      <div className="boite-connexion">
        <form className="formulaire-connexion" onSubmit={handleSubmit}>
          <button type="button" onClick={revenir} className="bouton-retour">
            ← Retour à l’espace professeur
          </button>

          <h1>Changer mon mot de passe</h1>

          <label>Nouveau mot de passe</label>
          <input
            type="password"
            placeholder="Au moins 8 caractères"
            value={nouveauMotDePasse}
            onChange={(e) => setNouveauMotDePasse(e.target.value)}
            required
          />

          <button type="submit" className="bouton-connexion1">
            Valider
          </button>
        </form>
      </div>
    </>
  );
};

export default ChangerMotDePasseProf;


