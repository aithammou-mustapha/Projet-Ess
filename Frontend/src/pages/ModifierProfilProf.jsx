import React, { useState, useEffect } from "react";
import Swal from "sweetalert2";
import axios from "axios";
import HeaderEspace from "../components/HeaderEspace";
import { useNavigate } from "react-router-dom";
import "../assets/styles/ModificationProfil.css";

const ModifierProfilProf = () => {
  const utilisateur = JSON.parse(localStorage.getItem("utilisateur"));
  const navigate = useNavigate();

  const [formData, setFormData] = useState({
    nomProf: "",
    prenomProf: "",
    telProf: "",
    adresseProf: "",
  });

  useEffect(() => {
    if (utilisateur) {
      setFormData({
        nomProf: utilisateur.nomProf || "",
        prenomProf: utilisateur.prenomProf || "",
        telProf: utilisateur.telProf || "",
        adresseProf: utilisateur.adresseProf || "",
      });
    }
  }, []);

  const handleChange = (e) => {
    setFormData({ ...formData, [e.target.name]: e.target.value });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();

    try {
      const response = await axios.patch(
        `https://localhost:8000/api/profs/${utilisateur.id}`,
        formData,
        {
          headers: { "Content-Type": "application/merge-patch+json" },
          withCredentials: true,
        }
      );

      if (response.status === 200 || response.status === 204) {
        Swal.fire({
          icon: "success",
          title: "Profil mis à jour",
          timer: 1500,
          showConfirmButton: false,
        }).then(() => {
          navigate("/espace-prof");
        });
      } else {
        Swal.fire("Erreur", "La mise à jour a échoué (réponse inattendue)", "error");
      }
    } catch (error) {
      console.error("Erreur API:", error);
      Swal.fire("Erreur", "Échec de la mise à jour", "error");
    }
  };

  const revenir = () => {
    navigate("/espace-prof");
  };

  return (
    <>
      <HeaderEspace />
      <div className="boite-modification">
        <form className="formulaire-modification" onSubmit={handleSubmit}>
          <button type="button" onClick={revenir} className="bouton-retour">
            ← Retour
          </button>

          <h1>Modifier mon profil</h1>

          <label>Nom</label>
          <input
            type="text"
            name="nomProf"
            value={formData.nomProf}
            onChange={handleChange}
            required
          />

          <label>Prénom</label>
          <input
            type="text"
            name="prenomProf"
            value={formData.prenomProf}
            onChange={handleChange}
            required
          />

          <label>Téléphone</label>
          <input
            type="text"
            name="telProf"
            value={formData.telProf}
            onChange={handleChange}
            required
          />

          <label>Adresse</label>
          <input
            type="text"
            name="adresseProf"
            value={formData.adresseProf}
            onChange={handleChange}
          />

          <button type="submit" className="bouton-modification">
            Valider
          </button>
        </form>
      </div>
    </>
  );
};

export default ModifierProfilProf;
