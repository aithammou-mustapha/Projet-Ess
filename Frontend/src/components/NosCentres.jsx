import React from 'react';
import { MapContainer, TileLayer, Marker, Popup, Tooltip } from 'react-leaflet';
import 'leaflet/dist/leaflet.css';
import L from 'leaflet';
import '../assets/styles/Accueil.css'; // Ton fichier CSS personnalisé

// Icône personnalisée
const iconeMarqueur = new L.Icon({
  iconUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png',
  iconSize: [30, 45],
  iconAnchor: [15, 45],
  popupAnchor: [0, -35],
  shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png',
  shadowSize: [50, 50],
});

// Données des centres
const centres = [
  {
    nom: "Centre SAINT-QUENTIN-EN-YVELINES",
    adresse: "78180 Montigny-le-Bretonneux",
    latitude: 48.7744,
    longitude: 2.0445,
    lienGoogleMaps: "https://www.google.com/maps/place/Excellence+Soutien+Scolaire/@48.7856794,2.0426478,17z/data=!3m1!4b1!4m6!3m5!1s0x47e6870bd00f7007:0xa5e096b1bccfc55f!8m2!3d48.7856794!4d2.0452227!16s%2Fg%2F11fw822dzq?entry=ttu&g_ep=EgoyMDI1MDMxMS4wIKXMDSoASAFQAw%3D%3D"
  },
  {
    nom: "Centre Saint-Ouen",
    adresse: "93400 Saint-Ouen",
    latitude: 48.9115,
    longitude: 2.3339,
    lienGoogleMaps: "https://www.google.com/maps/place/Excellence+Soutien+Scolaire/@48.9029264,2.3275635,16z/data=!3m1!4b1!4m6!3m5!1s0x47e66f9ff9496e53:0x66d8eb4abe818fc3!8m2!3d48.9029264!4d2.3301384!16s%2Fg%2F11lcbmsgsf?entry=ttu&g_ep=EgoyMDI1MDMxMS4wIKXMDSoASAFQAw%3D%3D"
  }
];

// Composant principal
const NosCentres = () => (
  <section className="section-nos-centres">
    <h2>Nos Centres</h2>
    <div className="carte-centres">
      <div className="conteneur-carte">
        <MapContainer center={[47.5, 2.5]} zoom={6} scrollWheelZoom={false} style={{ height: '100%', width: '100%' }}>
          <TileLayer
            url="https://{s}.basemaps.cartocdn.com/rastertiles/voyager_labels_under/{z}/{x}/{y}{r}.png"
            attribution='&copy; <a href="https://carto.com/">CartoDB</a>'
          />
          {centres.map((centre, idx) => (
            <Marker key={idx} position={[centre.latitude, centre.longitude]} icon={iconeMarqueur}>
              <Popup>
                <a href={centre.lienGoogleMaps} target="_blank" rel="noopener noreferrer">
                  {centre.nom}<br/>
                  <br/><strong>Voir sur Google Maps</strong>
                </a>
              </Popup>
              <Tooltip direction="top" offset={[0, -30]} opacity={0.9}>
                <span><strong>{centre.nom}</strong><br />{centre.adresse}</span>
              </Tooltip>
            </Marker>
          ))}
        </MapContainer>
      </div>
    </div>
  </section>
);

export default NosCentres;
