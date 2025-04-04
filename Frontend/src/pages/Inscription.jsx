import React, { useState, useEffect } from "react";
import axios from "axios";
import Header from "../components/Header";
import "../assets/styles/Inscription.css";
import Swal from "sweetalert2";
import { Link, useNavigate } from "react-router-dom";
import enfantImage from "../assets/images/enfant-saut.png";

// ✅ Fonction de vérification avec debug et bonne clé 'member'
const verifierEmailExiste = async (email) => {
  try {
    console.log("📡 Appel API pour vérifier l'email :", email);

    const [parentsRes, profsRes] = await Promise.all([
      axios.get("https://127.0.0.1:8000/api/parents"),
      axios.get("https://127.0.0.1:8000/api/profs"),
    ]);

    const parents = parentsRes.data.member || [];
    const profs = profsRes.data.member || [];

    console.log("👨‍👩‍👧‍👦 Parents récupérés :", parents.length);
    console.log("👨‍🏫 Profs récupérés :", profs.length);

    const emailExisteChezParent = parents.some(p => p.emailParent === email);
    const emailExisteChezProf = profs.some(p => p.emailProf === email);

    console.log("📌 emailExisteChezParent:", emailExisteChezParent);
    console.log("📌 emailExisteChezProf:", emailExisteChezProf);

    return emailExisteChezParent || emailExisteChezProf;
  } catch (error) {
    console.error("❌ Erreur dans verifierEmailExiste :", error);
    return false;
  }
};

const Inscription = () => {
  const [étape, setÉtape] = useState(1);
  const [rôle, setRôle] = useState("parent");
  const [centres, setCentres] = useState([]);
  const navigate = useNavigate();

  const niveauxScolaires = ["6ème", "5ème", "4ème", "3ème", "2nde", "1ère", "Terminale"];

  const [formulaires, setFormulaires] = useState({
    authentification: { email: "", motDePasse: "" },
    parent: { prenom: "", nom: "", telephone: "", adresse: "" },
    enfant: { prenom: "", nom: "", telephone: "", niveau: "", etablissementScolaire: "", centre: "" }, 
    professeur: { prenom: "", nom: "", telephone: "", adresse: "" }
  });

  useEffect(() => {
    const utilisateur = JSON.parse(localStorage.getItem("utilisateur"));
    const rôle = localStorage.getItem("rôle");
  
    if (utilisateur && rôle === "parent") {
      navigate("/espace-parent");
    } else if (utilisateur && rôle === "professeur") {
      navigate("/espace-prof");
    }
  }, [navigate]);
  

  useEffect(() => {
    axios.get("https://127.0.0.1:8000/api/centres")
      .then(res => setCentres(res.data.member || []))
      .catch(err => console.error("Erreur chargement centres:", err));
  }, []);

  const gérerChamps = (e, type) => {
    const { name, value } = e.target;
    setFormulaires(prev => ({
      ...prev,
      [type]: { ...prev[type], [name]: value }
    }));
  };

  const champsRemplis = (obj) => Object.values(obj).every(v => v.trim() !== "");

  const soumettreAuthentification = async (e) => {
    e.preventDefault();
    const { email, motDePasse } = formulaires.authentification;

    if (!email.trim() || !motDePasse.trim()) {
      return Swal.fire("Erreur", "Tous les champs sont requis.", "error");
    }

    if (motDePasse.length < 8) {
      return Swal.fire("Erreur", "Le mot de passe doit contenir au moins 8 caractères.", "error");
    }

    const existe = await verifierEmailExiste(email);
    if (existe) {
      return Swal.fire("Erreur", "Cet email est déjà utilisé.", "error");
    }

    setÉtape(2);
  };

  const soumettreParent = (e) => {
    e.preventDefault();
    if (!champsRemplis(formulaires.parent)) return Swal.fire("Erreur", "Tous les champs sont requis.", "error");
    setÉtape(3);
  };

  const soumettreParentEtEnfant = async (e) => {
    e.preventDefault();
    const { parent, enfant, authentification } = formulaires;

    if (!champsRemplis(enfant)) return Swal.fire("Erreur", "Tous les champs sont requis.", "error");

    const dataParent = {
      prenomParent: parent.prenom,
      nomParent: parent.nom,
      emailParent: authentification.email,
      telParent: parent.telephone,
      adresseParent: parent.adresse,
      motDePasse: authentification.motDePasse
    };

    try {
      const resParent = await axios.post("https://127.0.0.1:8000/api/parents", dataParent, {
        headers: { "Content-Type": "application/ld+json" }
      });
      const parentId = resParent.data.id;

      const dataEnfant = {
        prenomEleve: enfant.prenom,
        nomEleve: enfant.nom,
        telEleve: enfant.telephone,
        niveau: enfant.niveau,
        etablissementScolaire: enfant.etablissementScolaire, 
        centre: enfant.centre,
        parent: `/api/parents/${parentId}`
      };

      await axios.post("https://127.0.0.1:8000/api/eleves", dataEnfant, {
        headers: { "Content-Type": "application/ld+json" }
      });

      Swal.fire("Succès", "Compte parent/élève créé.", "success").then(() => navigate("/connexion"));
    } catch (err) {
      const message = err.response?.data?.detail;
      if (message && message.includes("emailParent")) {
        Swal.fire("Erreur", "Cet email parent existe déjà.", "error");
      } else {
        console.error(err);
        Swal.fire("Erreur", "Erreur lors de la création.", "error");
      }
    }
  };

  const soumettreProfesseur = async (e) => {
    e.preventDefault();
    const { professeur, authentification } = formulaires;

    if (!champsRemplis(professeur) || !champsRemplis(authentification)) {
      return Swal.fire("Erreur", "Tous les champs sont requis.", "error");
    }

    if (authentification.motDePasse.length < 8) {
      return Swal.fire("Erreur", "Le mot de passe doit contenir au moins 8 caractères.", "error");
    }

    const existe = await verifierEmailExiste(authentification.email);
    if (existe) {
      return Swal.fire("Erreur", "Cet email est déjà utilisé.", "error");
    }

    const data = {
      prenomProf: professeur.prenom,
      nomProf: professeur.nom,
      emailProf: authentification.email,
      telProf: professeur.telephone,
      adresseProf: professeur.adresse,
      motDePasse: authentification.motDePasse
    };

    try {
      await axios.post("https://127.0.0.1:8000/api/profs", data, {
        headers: { "Content-Type": "application/ld+json" }
      });
      Swal.fire("Succès", "Compte professeur créé.", "success").then(() => navigate("/connexion"));
    } catch (err) {
      const message = err.response?.data?.detail;
      if (message && message.includes("emailProf")) {
        Swal.fire("Erreur", "Cet email professeur existe déjà.", "error");
      } else {
        console.error(err);
        Swal.fire("Erreur", "Erreur création professeur.", "error");
      }
    }
  };

  return (
    <>
      <Header />
      <div className={`boite-inscription etape-${étape}`}>
        <div className="formulaire-inscription">
          {étape > 1 && <button onClick={() => setÉtape(étape - 1)} className="bouton-retour">← Retour</button>}

          {étape === 1 && (
            <>
              <h1>Inscription</h1>
              <p className="sous-titre-role">Qui êtes-vous ?</p>
              <div className="choix-role-radio">
                {["parent", "professeur"].map((role) => (
                  <label key={role} className={rôle === role ? "actif" : ""}>
                    <input
                      type="radio"
                      name="rôle"
                      value={role}
                      checked={rôle === role}
                      onChange={() => setRôle(role)}
                    />
                    {role === "parent" ? "Élève/Parent" : "Professeur"}
                  </label>
                ))}
              </div>
              <form onSubmit={soumettreAuthentification}>
                <input
                  type="email"
                  name="email"
                  placeholder="Email"
                  value={formulaires.authentification.email}
                  onChange={(e) => gérerChamps(e, "authentification")}
                />
                <input
                  type="password"
                  name="motDePasse"
                  placeholder="Mot de passe"
                  value={formulaires.authentification.motDePasse}
                  onChange={(e) => gérerChamps(e, "authentification")}
                />
                <button className="bouton-inscription1">S'INSCRIRE</button>
              </form>
            </>
          )}

          {étape === 2 && rôle === "parent" && (
            <form onSubmit={soumettreParent}>
              <h1>Parent</h1>
              {["prenom", "nom", "telephone", "adresse"].map((c) => (
                <input
                  key={c}
                  name={c}
                  placeholder={c}
                  value={formulaires.parent[c]}
                  onChange={(e) => gérerChamps(e, "parent")}
                />
              ))}
              <button className="bouton-inscription1">Suivant</button>
            </form>
          )}

          {étape === 3 && rôle === "parent" && (
            <form onSubmit={soumettreParentEtEnfant}>
              <h1>Enfant</h1>
              {["prenom", "nom", "telephone"].map((c) => (
                <input
                  key={c}
                  name={c}
                  placeholder={c}
                  value={formulaires.enfant[c]}
                  onChange={(e) => gérerChamps(e, "enfant")}
                />
              ))}
              <select
                name="niveau"
                value={formulaires.enfant.niveau}
                onChange={(e) => gérerChamps(e, "enfant")}
              >
                <option value="">Niveau scolaire</option>
                {niveauxScolaires.map((n) => (
                  <option key={n} value={n}>{n}</option>
                ))}
              </select>
              <select
                name="centre"
                value={formulaires.enfant.centre}
                onChange={(e) => gérerChamps(e, "enfant")}
              >
                <option value="">Centre</option>
                {centres.map((c) => (
                  <option key={c.id} value={`/api/centres/${c.id}`}>{c.nomCentre}</option>
                ))}
              </select>
              <input
                name="etablissementScolaire" // ✅ corrigé ici
                placeholder="Établissement scolaire"
                value={formulaires.enfant.etablissementScolaire}
                onChange={(e) => gérerChamps(e, "enfant")}
              />
              <button className="bouton-inscription1" type="submit">
                Créer le compte
              </button>
            </form>
          )}

          {étape === 2 && rôle === "professeur" && (
            <form onSubmit={soumettreProfesseur}>
              <h1>Professeur</h1>
              {["prenom", "nom", "telephone", "adresse"].map((c) => (
                <input
                  key={c}
                  name={c}
                  placeholder={c.charAt(0).toUpperCase() + c.slice(1)}
                  value={formulaires.professeur[c]}
                  onChange={(e) => gérerChamps(e, "professeur")}
                />
              ))}
              <button className="bouton-inscription1" type="submit">
                Créer un compte
              </button>
            </form>
          )}

          <p className="lien-connexion">
            Vous n'avez pas de compte ? <Link to="/connexion">Connexion</Link>
          </p>
        </div>

        <div className="image-droite-inscription">
          <img src={enfantImage} alt="Enfant qui saute" className="image-droite" />
        </div>
      </div>
    </>
  );
};

export default Inscription;
