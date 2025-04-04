import React, { useEffect, useState } from "react";
import FullCalendar from "@fullcalendar/react";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import frLocale from "@fullcalendar/core/locales/fr";
import axios from "axios";
import Swal from "sweetalert2";
import "../assets/styles/CalendrierEmplois.css";

const CalendrierProfesseur = () => {
  const [evenements, setEvenements] = useState([]);
  const utilisateur = JSON.parse(localStorage.getItem("utilisateur"));

  useEffect(() => {
    const chargerEvenements = async () => {
      try {
        console.log("📡 Récupération des données API (professeur)...");
        console.log("✅ Prof connecté :", utilisateur);

        const resGroupes = await axios.get("https://localhost:8000/api/groupes", {
          headers: { Accept: "application/json" },
          withCredentials: true,
        });

        const groupesData = Array.isArray(resGroupes.data)
          ? resGroupes.data
          : resGroupes.data.member || [];

        console.log("📊 Données brutes groupes :", groupesData);
        console.log(`📥 ${groupesData.length} groupes reçus`);

        if (!utilisateur || !utilisateur.id) {
          console.warn("⚠️ Aucun professeur trouvé ou ID manquant dans le localStorage !");
          return;
        }

        const groupesProf = groupesData.filter((groupe) => {
          const profId = typeof groupe.prof === "string"
            ? parseInt(groupe.prof.split("/").pop())
            : groupe.prof?.id;
          return profId === utilisateur.id;
        });

        groupesProf.forEach((g) =>
          console.log(`🎓 Groupe du prof : ${g.nomGroupe} - ${g.matieresGroupe} - ${g.niveauGroupe}`)
        );

        const events = groupesProf.map((g) => {
          const date = new Date(g.dateDebut);
          const heureDebut = g.heureDebut?.substring(11, 16);
          const heureFin = g.heureFin?.substring(11, 16);

          const [hStart, mStart] = heureDebut.split(":").map(Number);
          const [hEnd, mEnd] = heureFin.split(":").map(Number);

          const start = new Date(date);
          start.setHours(hStart, mStart, 0, 0);

          const end = new Date(date);
          end.setHours(hEnd, mEnd, 0, 0);

          // ✅ Correction : ajoute 1 min si fin pile
          if (mEnd === 0 && end.getMinutes() === 0 && end.getSeconds() === 0) {
            end.setMinutes(end.getMinutes() + 1);
          }

          return {
            id: g.id,
            title: `${g.nomGroupe} - ${g.matieresGroupe} (${g.niveauGroupe})`,
            start: start.toISOString(),
            end: end.toISOString(),
            backgroundColor: g.backgroundColor || "#dfe6e9",
            textColor: g.textColor || "#000",
            display: "block", // ✅ important pour que FullCalendar l’affiche correctement
            extendedProps: {
              matiere: g.matieresGroupe,
              nom: g.nomGroupe,
              niveau: g.niveauGroupe,
            },
          };
        });

        console.log("🗓️ Événements professeur :", events);
        setEvenements(events);
      } catch (err) {
        console.error("❌ Erreur API :", err);
        Swal.fire("Erreur", "Chargement du planning professeur impossible", "error");
      }
    };

    if (utilisateur) chargerEvenements();
  }, []);

  const afficherDetails = (info) => {
    Swal.fire({
      title: info.event.extendedProps.nom,
      html: `<strong>Matière :</strong> ${info.event.extendedProps.matiere}<br/>
             <strong>Niveau :</strong> ${info.event.extendedProps.niveau}<br/>
             <strong>Début :</strong> ${info.event.start.toLocaleTimeString([], {
               hour: "2-digit", minute: "2-digit"
             })}<br/>
             <strong>Fin :</strong> ${info.event.end.toLocaleTimeString([], {
               hour: "2-digit", minute: "2-digit"
             })}`,
      icon: "info",
    });
  };

  return (
    <div className="calendrier-parent-conteneur">
      <h2 className="calendrier-titre">📘 Planning de mes cours</h2>

      <FullCalendar
        plugins={[timeGridPlugin, interactionPlugin]}
        initialView="timeGridWeek"
        allDaySlot={false}
        slotMinTime="08:00:00"
        slotMaxTime="20:01:00"
        locale={frLocale}
        events={evenements}
        eventClick={afficherDetails}
        height="auto"
        nowIndicator={true}
        headerToolbar={{
          left: "prev,next today",
          center: "title",
          right: "timeGridWeek,timeGridDay",
        }}
        dayHeaderFormat={{ weekday: "long", day: "2-digit", month: "2-digit" }}
      />
    </div>
  );
};

export default CalendrierProfesseur;
