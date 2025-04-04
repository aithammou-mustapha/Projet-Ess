import React, { useEffect, useState } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';
import Swal from 'sweetalert2';
import '../assets/styles/Groupes.css';

const Groupes = ({ typeGroupeFiltre = 'stage' }) => {
  const [groupes, setGroupes] = useState([]);
  const [error, setError] = useState(null);
  const [index, setIndex] = useState(0);

  useEffect(() => {
    const fetchGroupes = async () => {
      try {
        const response = await axios.get('https://127.0.0.1:8000/api/groupes', {
          headers: { 'Accept': 'application/ld+json' },
        });
        console.log('📦 Données brutes API:', response.data);

        const groupesFiltres = response.data.member.filter(
          (groupe) => groupe.typeGroupe === typeGroupeFiltre
        );

        groupesFiltres.forEach((groupe, i) => {
          console.log(`🔎 Groupe ${i + 1} - ${groupe.nomGroupe}`);
          console.log("   → Centre :", groupe.centre);
          console.log("   → Inscriptions :", groupe.inscriptions);
        });

        setGroupes(groupesFiltres);
      } catch (err) {
        console.error('❌ Erreur API :', err);
        setError('Erreur lors du chargement des groupes');
      }
    };

    fetchGroupes();
  }, [typeGroupeFiltre]);

  const ajouterAuPanier = (id, nom) => {
    const panier = JSON.parse(localStorage.getItem('panier')) || [];
    if (!panier.includes(id)) {
      panier.push(id);
      localStorage.setItem('panier', JSON.stringify(panier));
      Swal.fire({
        icon: 'success',
        title: 'Ajouté au panier 🎉',
        text: `Le groupe "${nom}" a bien été ajouté !`,
        confirmButtonColor: '#ff6161',
      });
      window.dispatchEvent(new Event("panierUpdated"));
    } else {
      Swal.fire({
        icon: 'info',
        title: 'Déjà dans le panier',
        text: `Le groupe "${nom}" est déjà présent.`,
        confirmButtonColor: '#ff6161',
      });
    }
  };

  const groupesParPage = 4;
  const totalPages = Math.ceil(groupes.length / groupesParPage);

  const suivant = () => setIndex((prev) => (prev + 1) % totalPages);
  const precedent = () => setIndex((prev) => (prev - 1 + totalPages) % totalPages);

  const groupesAffiches = groupes.slice(
    index * groupesParPage,
    index * groupesParPage + groupesParPage
  );

  const titre = typeGroupeFiltre === 'stage' ? 'Nos Prochains Stages' : 'Nos Groupes';

  return (
    <section className="section-groupes">
      <h2>{titre}</h2>
      {error && <p className="erreur">{error}</p>}

      <div className="slider-groupes">
        <button className="btn-nav gauche" onClick={precedent}>←</button>

        <div className="container-cartes">
          {groupesAffiches.map((groupe) => {
            const heureDebut = new Date(groupe.heureDebut).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            const heureFin = new Date(groupe.heureFin).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

            const nomCentre = groupe.centre?.nomCentre || "Non défini";
            if (!groupe.centre) {
              console.warn(`⚠️ Centre non défini pour le groupe "${groupe.nomGroupe}"`);
            }

            const tarif = groupe.inscriptions?.[0]?.tarif;
            if (tarif === undefined) {
              console.warn(`⚠️ Tarif non défini pour le groupe "${groupe.nomGroupe}"`);
            }

            const tarifAffiche = tarif !== undefined ? `${tarif}€` : "Non défini";

            const imageUrl = groupe.avatarGroupe
              ? `https://127.0.0.1:8000/uploads/${groupe.avatarGroupe}`
              : '/default-image.jpg';

            return (
              <div className="carte-groupe" key={groupe.id}>
                <div className="groupe-image-wrapper">
                  <img
                    src={imageUrl}
                    alt={`Image ${groupe.nomGroupe}`}
                    onError={(e) => (e.target.src = '/default-image.jpg')}
                  />
                </div>
                <h3>{groupe.nomGroupe}</h3>

                <p><strong>Sujet :</strong> {groupe.matieresGroupe || "Non défini"}</p>
                <p><strong>Niveau :</strong> {groupe.niveauGroupe || "Non défini"}</p>
                <p><strong>Centre :</strong> {nomCentre}</p>
                <p><strong>Date :</strong> {new Date(groupe.dateDebut).toLocaleDateString()} - {new Date(groupe.dateFin).toLocaleDateString()}</p>
                <p><strong>Horaires :</strong> {heureDebut} - {heureFin}</p>
                <p><strong>Prof :</strong> {groupe.prof?.prenomProf} {groupe.prof?.nomProf}</p>
                <p><strong>Tarif :</strong> {tarifAffiche}</p>

                <button onClick={() => ajouterAuPanier(groupe.id, groupe.nomGroupe)}>
                  Ajouter au Panier
                </button>
              </div>
            );
          })}
        </div>

        <button className="btn-nav droite" onClick={suivant}>→</button>
      </div>
    </section>
  );
};

export default Groupes;