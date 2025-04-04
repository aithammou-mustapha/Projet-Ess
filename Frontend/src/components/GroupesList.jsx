import React, { useEffect, useState } from 'react';
import axios from 'axios';
import Swal from 'sweetalert2';
import '../assets/styles/GroupesList.css';

const GroupesList = ({ type = 'stage' }) => {
  const [groupes, setGroupes] = useState([]);
  const [groupesFiltres, setGroupesFiltres] = useState([]);
  const [error, setError] = useState(null);
  const [visibleCount, setVisibleCount] = useState(4);

  const [filtre, setFiltre] = useState({
    matiere: '',
    niveau: '',
    centre: '',
  });

  useEffect(() => {
    const fetchGroupes = async () => {
      try {
        const response = await axios.get('https://127.0.0.1:8000/api/groupes', {
          headers: { Accept: 'application/ld+json' },
        });

        const tous = response.data.member.filter(
          (groupe) => groupe.typeGroupe === type
        );

        console.log("✅ Groupes récupérés :", tous);
        setGroupes(tous);
        setGroupesFiltres(tous);
      } catch (err) {
        console.error('❌ Erreur API :', err);
        setError("Erreur lors du chargement des groupes");
      }
    };

    fetchGroupes();
  }, [type]);

  const appliquerFiltres = (newFiltre) => {
    console.log("🌟 Filtres appliqués :", newFiltre);

    const resultats = groupes.filter((groupe) => {
      const matiereOK = !newFiltre.matiere || groupe.matieresGroupe === newFiltre.matiere;
      const niveauOK = !newFiltre.niveau || groupe.niveauGroupe === newFiltre.niveau;
      const centreOK = !newFiltre.centre || groupe.centre?.nomCentre === newFiltre.centre;

      return matiereOK && niveauOK && centreOK;
    });

    setGroupesFiltres(resultats);
    setVisibleCount(4);
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    const nouveauFiltre = { ...filtre, [name]: value };
    setFiltre(nouveauFiltre);
    appliquerFiltres(nouveauFiltre);
  };

  const matieres = [...new Set(groupes.map((g) => g.matieresGroupe).filter(Boolean))];
  const niveaux = [...new Set(groupes.map((g) => g.niveauGroupe).filter(Boolean))];
  const centres = [...new Set(groupes.map((g) => g.centre?.nomCentre).filter(Boolean))];

  const titre = type === 'normal' ? 'Nos Cours Hebdomadaires' : 'Nos Prochains Stages';

  const ajouterAuPanier = (id, nom) => {
    const panierActuel = JSON.parse(localStorage.getItem('panier')) || [];
    if (!panierActuel.includes(id)) {
      panierActuel.push(id);
      localStorage.setItem('panier', JSON.stringify(panierActuel));
      window.dispatchEvent(new Event("panierUpdated"));

      Swal.fire({
        icon: 'success',
        title: 'Ajouté au panier 🎉',
        text: `Le groupe "${nom}" a bien été ajouté !`,
        confirmButtonColor: '#ff6161',
      });
    } else {
      Swal.fire({
        icon: 'info',
        title: 'Déjà dans le panier',
        text: `Le groupe "${nom}" est déjà présent dans votre panier.`,
        confirmButtonColor: '#ff6161',
      });
    }
  };

  return (
    <section className="groupes-section">
      <h2 className="groupes-titre">{titre}</h2>

      <div className="filtres">
        <select name="matiere" value={filtre.matiere} onChange={handleChange}>
          <option value="">Toutes les matières</option>
          {matieres.map((m, i) => <option key={i} value={m}>{m}</option>)}
        </select>

        <select name="niveau" value={filtre.niveau} onChange={handleChange}>
          <option value="">Tous les niveaux</option>
          {niveaux.map((n, i) => <option key={i} value={n}>{n}</option>)}
        </select>

        <select name="centre" value={filtre.centre} onChange={handleChange}>
          <option value="">Tous les centres</option>
          {centres.map((c, i) => <option key={i} value={c}>{c}</option>)}
        </select>
      </div>

      {error && <p className="groupes-erreur">{error}</p>}

      <div className="groupes-cartes-container">
        {groupesFiltres.slice(0, visibleCount).map((groupe) => {
          const heureDebut = new Date(groupe.heureDebut).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
          const heureFin = new Date(groupe.heureFin).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

          const nomCentre = groupe.centre?.nomCentre || "Non défini";
          const tarif = groupe.inscriptions?.[0]?.tarif;

          return (
            <div className="groupe-carte" key={groupe.id}>
              <div className="groupe-image-wrapper">
                <img
                  src={groupe.avatarGroupe ? `https://127.0.0.1:8000/uploads/${groupe.avatarGroupe}` : '/default-image.jpg'}
                  alt={`Image ${groupe.nomGroupe}`}
                  onError={(e) => (e.target.src = '/default-image.jpg')}
                />
              </div>

              <div className="groupe-carte-content">
                <h3>{groupe.nomGroupe}</h3>
                <p><strong>Sujet :</strong> {groupe.matieresGroupe}</p>
                <p><strong>Niveau :</strong> {groupe.niveauGroupe}</p>
                <p><strong>Centre :</strong> {nomCentre}</p>
                <p><strong>Date :</strong> {new Date(groupe.dateDebut).toLocaleDateString()} - {new Date(groupe.dateFin).toLocaleDateString()}</p>
                <p><strong>Horaires :</strong> {heureDebut} - {heureFin}</p>
                <p><strong>Prof :</strong> {groupe.prof?.prenomProf} {groupe.prof?.nomProf}</p>
                <p><strong>Tarif :</strong> {tarif !== undefined ? `${tarif}€` : "Non défini"}</p>
                <button onClick={() => ajouterAuPanier(groupe.id, groupe.nomGroupe)} className="groupe-btn-ajouter">
                  Ajouter au Panier
                </button>
              </div>
            </div>
          );
        })}
      </div>

      {visibleCount < groupesFiltres.length && (
        <button className="btn-voir-plus" onClick={() => setVisibleCount(visibleCount + 4)}>
          Voir plus de groupes
        </button>
      )}
    </section>
  );
};

export default GroupesList;
