import React, { useState } from 'react'; // ✅ Ajouter useState ici
import { Link } from 'react-router-dom';
import '../assets/styles/Accueil.css';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faCirclePlay, faTimes } from '@fortawesome/free-solid-svg-icons';
import { faUsers, faBookOpen, faLightbulb, faGraduationCap } from '@fortawesome/free-solid-svg-icons';
import Header from '../components/Header';
import Groupes from '../components/Groupes';
import NosCentres from '../components/NosCentres';
import { faFacebook, faInstagram } from '@fortawesome/free-brands-svg-icons';



const Accueil = () => {
  const [showVideo, setShowVideo] = useState(false); // ✅ Gestion ouverture/fermeture

  return (
    <div className="page-accueil">

      {/* NAVIGATION */}
      <Header />

      {/* BANNIÈRE */}
      <section className="banniere">
        <div className="contenu-banniere">
          <h1>Brille Scolairement</h1>
          <p className="sous-titre">Apprends Facilement, Réussis Rapidement !</p>
          <p>
            Tu as du mal à suivre en cours ? Tu veux améliorer tes notes et gagner en confiance ? 
            Chez <strong>Excellence Soutien Scolaire</strong>, nous t’aidons à apprendre autrement, 
            avec une méthode efficace et adaptée à toi.
          </p>
          <div className="boutons-actions">
          <Link to="/inscription" className="bouton-inscription">S'INSCRIRE</Link>
            <button onClick={() => setShowVideo(true)} className="lien-demo">
              <FontAwesomeIcon icon={faCirclePlay} /> Démo en direct...
            </button>
            {showVideo && (
              <div className="popup-video" onClick={() => setShowVideo(false)}>
                <div className="popup-content" onClick={(e) => e.stopPropagation()}>
                  <button className="btn-fermer" onClick={() => setShowVideo(false)}>
                    <FontAwesomeIcon icon={faTimes} />
                  </button>
                  <video src="src/assets/images/videoess.mp4" controls autoPlay />
                </div>
              </div>
            )}
          </div>
        </div>
        <img src="src/assets/images/section 1.png" alt="section1 ess" className="img-section1" />
      </section>

      {/* GROUPES DYNAMIQUES */}
      <Groupes typeGroupeFiltre="normal" />

      {/* CHOIX */}
      <section className="section-choix">
        <h2>Pourquoi nous choisir ?</h2>
        <div className="container-choix">
          <div className="image-choix">
            <img src="src/assets/images/imagefille.png" alt="Fille qui réfléchit" />
          </div>
          <div className="texte-choix">
            <h3>L’Excellence Soutien Scolaire c’est :</h3>
            <ul>
              <li><FontAwesomeIcon icon={faUsers} /> Des cours par groupe de 4 maximum</li>
              <li><FontAwesomeIcon icon={faBookOpen} /> Maths, Physique-Chimie, Anglais et Français</li>
              <li><FontAwesomeIcon icon={faLightbulb} /> Une méthode d’enseignement innovante qui motive les élèves</li>
              <li><FontAwesomeIcon icon={faGraduationCap} /> Des stages complémentaires pour les préparer aux études supérieures</li>
            </ul>
          </div>
        </div>
      </section>

      {/* GROUPES DYNAMIQUES */}
      <Groupes typeGroupeFiltre="stage" />

      {/* TÉMOIGNAGES */}
      <section className="section-temoignages">
        <h2>Témoignages</h2>
        <div className="cartes-temoignages">
          <div className="temoignage">
            <div className="temoignage-header">
              <img src="src/assets/images/temoignage1.jpg" alt="témoignage 1" />
              <strong>Maxi Raval</strong>
            </div>
            <p>"Super cours et enseignants! Grâce à eux, j'ai enfin compris mes cours de mathématiques et j’ai repris confiance en moi. L'accompagnement personnalisé m’a beaucoup aidé."</p>
          </div>
          <div className="temoignage">
            <div className="temoignage-header">
              <img src="src/assets/images/temoignage2.jpg" alt="témoignage 2" />
              <strong>Venely K</strong>
            </div>
            <p>"J'ai beaucoup progressé en physique-chimie. Les explications sont claires et les professeurs sont très patients. Je recommande à tous ceux qui ont des difficultés."</p>
          </div>
          <div className="temoignage">
            <div className="temoignage-header">
              <img src="src/assets/images/temoignage3.jpg" alt="témoignage 3" />
              <strong>Lii Thakur</strong>
            </div>
            <p>"Méthode efficace et adaptée. En plus d'améliorer mes notes, j’ai appris à mieux m’organiser dans mes révisions. Les stages intensifs sont top !"</p>
          </div>
        </div>
      </section>

      {/* RÉALISATIONS */}
      <section className="section-realisations">
        <h2>Nos réalisations</h2>
        <div className="contenu-realisations">
          <div className="texte-realisations">
            <p>
              Depuis 2018, nous aidons les élèves à réussir. Au-delà des notes et des examens comme le bac et le brevet, ce qui compte vraiment, c'est leur confiance et leur motivation. Un vrai déclic se produit. <strong>Excellence Soutien Scolaire</strong>, c'est bien plus qu'une aide aux devoirs, c'est une nouvelle façon d'apprendre.
            </p>
            <ul>
              <li><FontAwesomeIcon icon={faUsers} /> <strong>Plus de 1200 élèves accompagnés</strong></li>
              <li><FontAwesomeIcon icon={faBookOpen} /> <strong>Plus de 4000 heures de cours dispensées</strong></li>
              <li><FontAwesomeIcon icon={faGraduationCap} /> <strong>100% de réussite au Bac</strong></li>
            </ul>
          </div>
          <div className="image-realisations">
            <img src="src/assets/images/personnage-main.png" alt="personne présentant les réalisations" />
          </div>
        </div>
      </section>

      {/* NOS CENTRES */}
      <NosCentres />

      {/* FOOTER */}
      <footer className="pied-page">
        {/* ✅ Logo & Contact */}
        <div className="logo-footer">
          <img src="src/assets/images/logoess.png" alt="Logo ESS" />
          {/* <h2>EXCELLENCE SOUTIEN SCOLAIRE</h2> */}
          <div className="contact-footer">
            <div><i className="fas fa-phone-alt"></i> 0624836581</div>
            <div><i className="fas fa-envelope"></i> contact@excellencesoutienscolaire.com</div>
          </div>
        </div>

        {/* ✅ Liens rapides */}
        <div className="liens-rapides">
          <h3>Liens rapides</h3>
          <Link to="/">Accueil</Link>
          <Link to="/cours">Cours hebdomadaires</Link>
          <Link to="/stages">Stages</Link>
        </div>

        {/* ✅ Réseaux sociaux */}
        <div className="reseaux-sociaux">
          <a href="https://facebook.com" target="_blank" rel="noreferrer">
            <FontAwesomeIcon icon={faFacebook} />
          </a>
          <a href="https://instagram.com" target="_blank" rel="noreferrer">
            <FontAwesomeIcon icon={faInstagram} />
          </a>
        </div>


        {/* ✅ Baseline */}
        <div className="baseline">Excellence Soutien Scolaire © tous droits réservés</div>
      </footer>

    </div>
  );
};

export default Accueil;
