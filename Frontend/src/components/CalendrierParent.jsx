import React, { useEffect, useState } from "react";
import FullCalendar from "@fullcalendar/react";
import timeGridPlugin from "@fullcalendar/timegrid";
import interactionPlugin from "@fullcalendar/interaction";
import frLocale from "@fullcalendar/core/locales/fr";
import axios from "axios";
import Swal from "sweetalert2";
import "../assets/styles/CalendrierEmplois.css";

const CalendrierParent = () => {
  const [evenements, setEvenements] = useState([]);
  const utilisateur = JSON.parse(localStorage.getItem("utilisateur"));

  useEffect(() => {
    const chargerEvenements = async () => {
      try {
        console.log("📡 Tentative de récupération des données API...");
        console.log("✅ Utilisateur connecté :", utilisateur);

        const resEleves = await axios.get("https://localhost:8000/api/eleves", {
          headers: { Accept: "application/json" },
          withCredentials: true,
        });
        const resGroupes = await axios.get("https://localhost:8000/api/groupes", {
          headers: { Accept: "application/json" },
          withCredentials: true,
        });

        const elevesData = Array.isArray(resEleves.data)
          ? resEleves.data
          : resEleves.data.member || [];

        const groupesData = Array.isArray(resGroupes.data)
          ? resGroupes.data
          : resGroupes.data.member || [];

        console.log("📊 Données brutes élèves:", resEleves.data);
        console.log("📊 Données brutes groupes:", resGroupes.data);
        console.log(`📥 ${elevesData.length} élèves reçus`);
        console.log(`📥 ${groupesData.length} groupes reçus`);

        if (!utilisateur || !utilisateur.id) {
          console.warn("⚠️ Aucun utilisateur trouvé ou ID manquant dans le localStorage !");
          return;
        }

        const mesEnfants = elevesData.filter((e) => {
          let idParent = null;
          if (typeof e.parent === "string") {
            idParent = parseInt(e.parent.split("/").pop());
          } else if (typeof e.parent === "object" && e.parent !== null) {
            idParent = e.parent.id;
          }
          return idParent === utilisateur.id;
        });

        console.log(`👦 ${mesEnfants.length} enfant(s) lié(s) à l'utilisateur connecté (ID ${utilisateur.id})`);
        mesEnfants.forEach((e) =>
          console.log(`👶 Enfant trouvé: ${e.prenomEleve || e.nomEleve} (ID ${e.id})`)
        );

        const groupesFiltres = groupesData.filter((groupe) => {
          const ids = (groupe.eleves || []).map((e) =>
            typeof e === "string" ? parseInt(e.split("/").pop()) : e?.id
          );
          return mesEnfants.some((enf) => ids.includes(enf.id));
        });

        groupesFiltres.forEach((g) =>
          console.log(`📚 Groupe gardé: ${g.nomGroupe} - élèves:`, g.eleves)
        );

        const events = groupesFiltres.map((g) => {
          const date = new Date(g.dateDebut);
          const heureDebut = g.heureDebut?.substring(11, 16);
          const heureFin = g.heureFin?.substring(11, 16);

          const [hStart, mStart] = heureDebut.split(":").map(Number);
          const [hEnd, mEnd] = heureFin.split(":").map(Number);

          const start = new Date(date);
          start.setHours(hStart, mStart, 0, 0);

          const end = new Date(date);
          end.setHours(hEnd, mEnd, 0, 0);

          console.log(`🕒 Groupe ${g.nomGroupe} : ${start.toISOString()} → ${end.toISOString()}`);

          return {
            id: g.id,
            title: `${g.nomGroupe} - ${g.matieresGroupe}`,
            start: start.toISOString(),
            end: end.toISOString(),
            backgroundColor: g.backgroundColor || "#e2f0d9",
            textColor: g.textColor || "#ffffxxx",
            display: "block", // pour forcer le rendu visuel complet
            extendedProps: {
              matiere: g.matieresGroupe,
              nom: g.nomGroupe,
            },
          };
        });

        console.log("🗓️ Aperçu des événements :", events);
        console.log(`✅ ${events.length} événement(s) généré(s) pour le calendrier`);
        setEvenements(events);
      } catch (err) {
        console.error("❌ Erreur API:", err);
        Swal.fire("Erreur", "Chargement du calendrier impossible", "error");
      }
    };

    if (utilisateur) chargerEvenements();
  }, []);

  const afficherDetails = (info) => {
    Swal.fire({
      title: info.event.extendedProps.nom,
      html: `<strong>Matière :</strong> ${info.event.extendedProps.matiere}<br/>
             <strong>Début :</strong> ${info.event.start.toLocaleTimeString([], {
               hour: "2-digit",
               minute: "2-digit",
             })}<br/>
             <strong>Fin :</strong> ${info.event.end.toLocaleTimeString([], {
               hour: "2-digit",
               minute: "2-digit",
             })}`,
      icon: "info",
    });
  };

  return (
    <div className="calendrier-parent-conteneur">
      <h2 className="calendrier-titre">📅 Emploi du temps</h2>

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

export default CalendrierParent;
