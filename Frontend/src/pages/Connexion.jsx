import React, { useState, useEffect } from "react";
import axios from "axios";
import "../assets/styles/Connexion.css";
import Swal from "sweetalert2";
import { useNavigate } from "react-router-dom";
import enfantImage from "../assets/images/enfant-cahier.png";
import Header from "../components/Header";
import bcrypt from "bcryptjs"; // ✅ Import bcryptjs

const Connexion = () => {
  const navigate = useNavigate();
  const [rôle, setRôle] = useState("parent");
  const [email, setEmail] = useState("");
  const [motDePasse, setMotDePasse] = useState("");

  // ✅ Redirection si déjà connecté
  useEffect(() => {
    const utilisateur = JSON.parse(localStorage.getItem("utilisateur"));
    const rôle = localStorage.getItem("rôle");

    if (utilisateur && rôle === "parent") {
      navigate("/espace-parent");
    } else if (utilisateur && rôle === "professeur") {
      navigate("/espace-prof");
    }
  }, [navigate]);

  const handleConnexion = async (e) => {
    e.preventDefault();

    if (!email.trim() || !motDePasse.trim()) {
      return Swal.fire("Erreur", "Tous les champs sont requis.", "error");
    }

    try {
      console.log("📨 Connexion tentative avec:", email, motDePasse);

      if (rôle === "parent") {
        const res = await axios.get("https://127.0.0.1:8000/api/parents");
        const parents = res.data.member || [];
        console.log("👨‍👩‍👧‍👦 Parents disponibles:", parents.length);

        console.table(
          parents.map((p) => ({
            email: p.emailParent,
            motDePasse: p.motDePasse,
          }))
        );

        const utilisateur = parents.find(
          (p) =>
            p.emailParent === email &&
            bcrypt.compareSync(motDePasse, p.motDePasse)
        );

        if (utilisateur) {
          console.log("✅ Connexion réussie parent:", utilisateur);
          localStorage.setItem("utilisateur", JSON.stringify(utilisateur));
          localStorage.setItem("rôle", "parent");

          Swal.fire("Succès", "Connexion réussie 🎉", "success").then(() =>
            navigate("/espace-parent")
          );
        } else {
          console.warn("❌ Email ou mot de passe incorrect (parent)");
          Swal.fire("Erreur", "Email ou mot de passe incorrect.", "error");
        }
      } else {
        const res = await axios.get("https://127.0.0.1:8000/api/profs");
        const profs = res.data.member || [];
        console.log("👨‍🏫 Profs disponibles:", profs.length);

        console.table(
          profs.map((p) => ({
            email: p.emailProf,
            motDePasse: p.motDePasse,
          }))
        );

        const utilisateur = profs.find(
          (p) =>
            p.emailProf === email &&
            bcrypt.compareSync(motDePasse, p.motDePasse)
        );

        if (utilisateur) {
          console.log("✅ Connexion réussie prof:", utilisateur);
          localStorage.setItem("utilisateur", JSON.stringify(utilisateur));
          localStorage.setItem("rôle", "professeur");

          Swal.fire("Succès", "Connexion réussie 🎉", "success").then(() =>
            navigate("/espace-prof")
          );
        } else {
          console.warn("❌ Email ou mot de passe incorrect (prof)");
          Swal.fire("Erreur", "Email ou mot de passe incorrect.", "error");
        }
      }
    } catch (err) {
      console.error("💥 Erreur API Connexion:", err);
      Swal.fire("Erreur", "Erreur serveur ou API.", "error");
    }
  };

  return (
    <>
      <Header />
      <div className="boite-connexion etape-1">
        <div className="formulaire-connexion">
          <h1>Connexion</h1>
          <p className="sous-titre-role">Qui êtes-vous ?</p>
          <div className="choix-role-radio">
            <label className={rôle === "parent" ? "actif" : ""}>
              <input
                type="radio"
                name="rôle"
                value="parent"
                checked={rôle === "parent"}
                onChange={() => setRôle("parent")}
              />
              Élève/Parent
            </label>
            <label className={rôle === "professeur" ? "actif" : ""}>
              <input
                type="radio"
                name="rôle"
                value="professeur"
                checked={rôle === "professeur"}
                onChange={() => setRôle("professeur")}
              />
              Professeur
            </label>
          </div>

          <form onSubmit={handleConnexion}>
            <input
              type="email"
              placeholder="Email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
            />
            <input
              type="password"
              placeholder="Mot de passe"
              value={motDePasse}
              onChange={(e) => setMotDePasse(e.target.value)}
            />
            <button className="bouton-connexion1" type="submit">
              CONNEXION
            </button>
          </form>

          <p className="lien-connexion">
            Vous n'avez pas de compte ? <a href="/inscription">S’inscrire</a>
          </p>
        </div>

        <div className="image-droite-connexion">
          <img
            src={enfantImage}
            alt="Enfant avec un cahier"
            className="image-droite"
          />
        </div>
      </div>
    </>
  );
};

export default Connexion;
