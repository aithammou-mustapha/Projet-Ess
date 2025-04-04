import React, { useEffect, useState } from 'react';
import axios from 'axios';
import { useNavigate } from 'react-router-dom';
import Swal from 'sweetalert2';
import '../assets/styles/Panier.css';
import Header from '../components/Header';

const PagePanier = () => {
  const [groupes, setGroupes] = useState([]);
  const navigate = useNavigate();

  const chargerGroupes = async () => {
    const idsPanier = JSON.parse(localStorage.getItem('panier')) || [];
    try {
      const promises = idsPanier.map((id) =>
        axios.get(`https://127.0.0.1:8000/api/groupes/${id}`, {
          headers: { Accept: 'application/ld+json' },
        })
      );
      const responses = await Promise.all(promises);
      setGroupes(responses.map((res) => res.data));
    } catch (error) {
      console.error('❌ Erreur lors du chargement du panier :', error);
    }
  };

  useEffect(() => {
    chargerGroupes();
    window.addEventListener('panierUpdated', chargerGroupes);
    return () => window.removeEventListener('panierUpdated', chargerGroupes);
  }, []);

  const supprimerDuPanier = (id) => {
    Swal.fire({
      title: 'Supprimer ce groupe ?',
      text: "Il sera retiré de votre panier.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Oui, supprimer',
    }).then((result) => {
      if (result.isConfirmed) {
        const nouveauPanier = groupes.filter((g) => g.id !== id);
        setGroupes(nouveauPanier);
        localStorage.setItem('panier', JSON.stringify(nouveauPanier.map((g) => g.id)));

        Swal.fire('Supprimé !', 'Le groupe a été retiré.', 'success');
        window.dispatchEvent(new Event('panierUpdated'));
      }
    });
  };

  const allerAuxConditions = () => {
    navigate('/conditions');
  };

  const total = groupes.reduce(
    (acc, g) => acc + parseFloat(g.inscriptions?.[0]?.tarif || 0),
    0
  ).toFixed(2);

  return (
    <>
      <Header />
      <section className="panier-section">
        <h2 className="panier-titre">Votre Panier</h2>
        {groupes.length === 0 ? (
          <p className="panier-vide">Votre panier est vide 🛒</p>
        ) : (
          <div className="panier-cartes">
            {groupes.map((groupe) => {
              const heureDebut = groupe.heureDebut
                ? new Date(groupe.heureDebut).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                : '—';
              const heureFin = groupe.heureFin
                ? new Date(groupe.heureFin).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })
                : '—';
              const tarif = groupe.inscriptions?.[0]?.tarif;

              const imageUrl = groupe.avatarGroupe
                ? `https://127.0.0.1:8000/uploads/${groupe.avatarGroupe}`
                : '/default-image.jpg';

              return (
                <div className="panier-carte" key={groupe.id}>
                  <div className="groupe-image-wrapper">
                    <img
                      src={imageUrl}
                      alt={`Image ${groupe.nomGroupe}`}
                      onError={(e) => (e.target.src = '/default-image.jpg')}
                    />
                  </div>

                  <div className="panier-carte-content">
                    <h3>{groupe.nomGroupe}</h3>
                    <p><strong>Matière :</strong> {groupe.matieresGroupe}</p>
                    <p><strong>Niveau :</strong> {groupe.niveauGroupe}</p>
                    <p><strong>Centre :</strong> {groupe.centre?.nomCentre || 'Non défini'}</p>
                    <p><strong>Dates :</strong> {new Date(groupe.dateDebut).toLocaleDateString()} - {new Date(groupe.dateFin).toLocaleDateString()}</p>
                    <p><strong>Horaires :</strong> {heureDebut} - {heureFin}</p>
                    <p><strong>Tarif :</strong> {tarif !== undefined ? `${tarif}€` : 'Non défini'}</p>
                    <button className="btn-supprimer" onClick={() => supprimerDuPanier(groupe.id)}>❌ Supprimer</button>
                  </div>
                </div>
              );
            })}

            <p className="panier-total">Total : {total} €</p>

            <button className="btn-payer" onClick={allerAuxConditions}>
              Passer au paiement
            </button>
          </div>
        )}
      </section>
    </>
  );
};

export default PagePanier;
