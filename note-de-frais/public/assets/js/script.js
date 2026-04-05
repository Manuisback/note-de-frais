const btnAjouterDepense = document.getElementById("btnAjouterDepense");
const listeDepenses = document.getElementById("listeDepenses");
const typeDemande = document.getElementById("typeDemande");
const formNote = document.getElementById("formNote");
const signaturePad = document.getElementById("signaturePad");
const btnEffacerSignature = document.getElementById("btnEffacerSignature");
const inputSignatureData = document.getElementById("signatureData");
const ctxSignature = signaturePad.getContext("2d");
let estEnTrainDeSigner = false;
let signatureFaite = false;

// les barèmes
const baremes = {
  note: [
    { valeur: "voiture_seul", label: "Voiture - conducteur seul", taux: 0.360 },
    { valeur: "voiture_covoiturage", label: "Voiture - covoiturage ou chargée", taux: 0.400 },
    { valeur: "moto_125_plus", label: "Moto supérieure ou égale à 125cc", taux: 0.140 }
  ],
  abandon: [
    { valeur: "voiture_3cv_moins", label: "Voiture 3 CV et moins", taux: 0.529 },
    { valeur: "voiture_4cv", label: "Voiture 4 CV", taux: 0.606 },
    { valeur: "voiture_5cv", label: "Voiture 5 CV", taux: 0.636 },
    { valeur: "voiture_6cv", label: "Voiture 6 CV", taux: 0.665 },
    { valeur: "voiture_7cv_plus", label: "Voiture 7 CV et plus", taux: 0.697 },
    { valeur: "moto_1_2cv", label: "Moto 1 & 2 CV", taux: 0.395 },
    { valeur: "moto_3_5cv", label: "Moto 3, 4 et 5 CV", taux: 0.468 },
    { valeur: "moto_6cv_plus", label: "Moto 6 CV et plus", taux: 0.606 }
  ]
};

// style du trait
ctxSignature.lineWidth = 2;
ctxSignature.lineCap = "round";
ctxSignature.strokeStyle = "#111";

// transforme une valeur en nombre
function lireNombre(valeur) {
  const nombre = parseFloat(valeur);
  return isNaN(nombre) ? 0 : nombre;
}

function getBaremesActifs() {
  return baremes[typeDemande.value] || [];
}

// remplit un select de barèmes
function remplirSelectBareme(select, valeurSelectionnee = "") {
  const liste = getBaremesActifs();

  select.innerHTML = `<option value=""></option>`;

  liste.forEach((item) => {
    const option = document.createElement("option");
    option.value = item.valeur;
    option.textContent = `${item.label} - ${item.taux.toFixed(3)} €/km`;

    if (item.valeur === valeurSelectionnee) {
      option.selected = true;
    }

    select.appendChild(option);
  });
}

// initialise tous les selects de barème
function initialiserBaremes() {
  const selects = listeDepenses.querySelectorAll(".select-bareme");

  selects.forEach((select) => {
    const ancienneValeur = select.value;
    remplirSelectBareme(select, ancienneValeur);
  });

  recalculerTout();
}

// récupère le taux d'un barème
function getTauxBareme(valeurBareme) {
  const liste = getBaremesActifs();
  const baremeTrouve = liste.find((item) => item.valeur === valeurBareme);

  return baremeTrouve ? baremeTrouve.taux : 0;
}

// calcule le total d'une ligne
function calculerTotalLigne(carte) {
  const inputKm = carte.querySelector(".input-km");
  const selectBareme = carte.querySelector(".select-bareme");
  const inputTauxKm = carte.querySelector(".input-taux-km");
  const inputMontantKmLigne = carte.querySelector(".montant-km-ligne");
  const inputTransport = carte.querySelector(".input-transport");
  const inputAutre = carte.querySelector(".input-autre");
  const inputTotalLigne = carte.querySelector(".total-ligne");

  const km = lireNombre(inputKm.value);
  const tauxKm = getTauxBareme(selectBareme.value);
  const transport = lireNombre(inputTransport.value);
  const autre = lireNombre(inputAutre.value);

  const montantKm = km * tauxKm;
  const totalLigne = montantKm + transport + autre;

  inputTauxKm.value = tauxKm.toFixed(3);
  inputMontantKmLigne.value = montantKm.toFixed(2);
  inputTotalLigne.value = totalLigne.toFixed(2);

  return {
    km,
    montantKm,
    transport,
    autre,
    totalLigne
  };
}

// calcule le total général
function calculerTotalGeneral() {
  const cartes = listeDepenses.querySelectorAll(".carte-depense");
  const inputTotalGeneral = document.getElementById("totalGeneral");

  let total = 0;

  cartes.forEach((carte) => {
    const resultat = calculerTotalLigne(carte);
    total += resultat.totalLigne;
  });

  inputTotalGeneral.value = total.toFixed(2);
}

// calcule le total des kilomètres
function calculerTotalKm() {
  const cartes = listeDepenses.querySelectorAll(".carte-depense");
  const inputTotalKm = document.getElementById("totalKm");

  let totalKm = 0;

  cartes.forEach((carte) => {
    const resultat = calculerTotalLigne(carte);
    totalKm += resultat.km;
  });

  inputTotalKm.value = totalKm.toFixed(1);
}

// calcule le montant kilométrique
function calculerMontantKm() {
  const cartes = listeDepenses.querySelectorAll(".carte-depense");
  const inputMontantKm = document.getElementById("montantKm");

  let totalMontantKm = 0;

  cartes.forEach((carte) => {
    const resultat = calculerTotalLigne(carte);
    totalMontantKm += resultat.montantKm;
  });

  inputMontantKm.value = totalMontantKm.toFixed(2);
}

// calcule le total transport
function calculerTotalTransport() {
  const cartes = listeDepenses.querySelectorAll(".carte-depense");
  const inputTotalTransport = document.getElementById("totalTransport");

  let totalTransport = 0;

  cartes.forEach((carte) => {
    const resultat = calculerTotalLigne(carte);
    totalTransport += resultat.transport;
  });

  inputTotalTransport.value = totalTransport.toFixed(2);
}

// calcule le total autre
function calculerTotalAutre() {
  const cartes = listeDepenses.querySelectorAll(".carte-depense");
  const inputTotalAutre = document.getElementById("totalAutre");

  let totalAutre = 0;

  cartes.forEach((carte) => {
    const resultat = calculerTotalLigne(carte);
    totalAutre += resultat.autre;
  });

  inputTotalAutre.value = totalAutre.toFixed(2);
}

// calcule le montant remboursé
function calculerMontantRembourse() {
  const inputTotalGeneral = document.getElementById("totalGeneral");
  const inputMontantAbandon = document.getElementById("montantAbandon");
  const inputMontantRembourse = document.getElementById("montantRembourse");

  const totalGeneral = lireNombre(inputTotalGeneral.value);
  const montantAbandon = lireNombre(inputMontantAbandon.value);

  let montantRembourse = totalGeneral - montantAbandon;

  if (montantRembourse < 0) {
    montantRembourse = 0;
  }

  inputMontantRembourse.value = montantRembourse.toFixed(2);
}

// recalcule tout
function recalculerTout() {
  const cartes = listeDepenses.querySelectorAll(".carte-depense");

  cartes.forEach((carte) => {
    calculerTotalLigne(carte);
  });

  calculerTotalGeneral();
  calculerTotalKm();
  calculerMontantKm();
  calculerTotalTransport();
  calculerTotalAutre();
  calculerMontantRembourse();
}

// ajoute une nouvelle dépense
function ajouterDepense() {
  const nbDepenses = listeDepenses.querySelectorAll(".carte-depense").length + 1;

  const carte = document.createElement("article");
  carte.className = "carte-depense";

  carte.innerHTML = `
    <div class="haut-carte">
      <h3 class="titre-carte">Dépense ${nbDepenses}</h3>
      <button type="button" class="btnSupprimer">Supprimer</button>
    </div>

    <div class="grille">
      <div class="champ">
        <label>Date de la dépense</label>
        <input type="date" name="dateDepense[]" />
      </div>

      <div class="champ">
        <label>Objet de la dépense</label>
        <input
          type="text"
          name="objetDepense[]"
          placeholder="Ex :..."
        />
      </div>

      <div class="champ">
        <label>Kilomètres</label>
        <input
          type="number"
          name="kmDepense[]"
          class="input-km"
          min="0"
          step="0.1"
          placeholder="Ex :"
        />
      </div>

      <div class="champ">
        <label>Type de barème km</label>
        <select name="typeBareme[]" class="select-bareme">
          <option value="">-- Aucun --</option>
        </select>
      </div>

      <div class="champ">
        <label>Taux km</label>
        <input
          type="text"
          name="tauxKm[]"
          class="input-taux-km"
          value="0.000"
          readonly
        />
      </div>

      <div class="champ">
        <label>Montant km</label>
        <input
          type="text"
          name="montantKmLigne[]"
          class="montant-km-ligne"
          value="0.00"
          readonly
        />
      </div>

      <div class="champ">
        <label>Péages / transports</label>
        <input
          type="number"
          name="transportDepense[]"
          class="input-transport"
          min="0"
          step="0.01"
          placeholder="Ex : "
        />
      </div>

      <div class="champ">
        <label>Autres</label>
        <input
          type="number"
          name="autreDepense[]"
          class="input-autre"
          min="0"
          step="0.01"
          placeholder="Ex : "
        />
      </div>

      <div class="champ">
        <label>Total de la ligne</label>
        <input
          type="text"
          name="totalLigne[]"
          class="total-ligne"
          value="0.00"
          readonly
        />
      </div>
    </div>
  `;

  listeDepenses.appendChild(carte);

  const selectBareme = carte.querySelector(".select-bareme");
  remplirSelectBareme(selectBareme);

  renumeroterDepenses();
  recalculerTout();
}

// supprime une dépense
function supprimerDepense(element) {
  const cartes = listeDepenses.querySelectorAll(".carte-depense");

  if (cartes.length <= 1) {
    alert("Il doit rester au moins une dépense.");
    return;
  }

  element.closest(".carte-depense").remove();
  renumeroterDepenses();
  recalculerTout();
}

// renumérote les dépenses
function renumeroterDepenses() {
  const cartes = listeDepenses.querySelectorAll(".carte-depense");

  cartes.forEach((carte, index) => {
    const titre = carte.querySelector(".titre-carte");
    titre.textContent = `Dépense ${index + 1}`;
  });
}

// récupère la position de la souris dans le canvas
function getPositionSignature(event) {
  const rect = signaturePad.getBoundingClientRect();

  return {
    x: event.clientX - rect.left,
    y: event.clientY - rect.top
  };
}

// commence le dessin
signaturePad.addEventListener("mousedown", function (event) {
  estEnTrainDeSigner = true;
  const position = getPositionSignature(event);

  ctxSignature.beginPath();
  ctxSignature.moveTo(position.x, position.y);
});

// dessine
signaturePad.addEventListener("mousemove", function (event) {
  if (!estEnTrainDeSigner) {
    return;
  }

  const position = getPositionSignature(event);
  ctxSignature.lineTo(position.x, position.y);
  ctxSignature.stroke();
  signatureFaite = true;
});

// arrête le dessin
signaturePad.addEventListener("mouseup", function () {
  estEnTrainDeSigner = false;
});

// arrête le dessin si on sort du canvas
signaturePad.addEventListener("mouseleave", function () {
  estEnTrainDeSigner = false;
});

// efface la signature
btnEffacerSignature.addEventListener("click", function () {
  ctxSignature.clearRect(0, 0, signaturePad.width, signaturePad.height);
  signatureFaite = false;
  inputSignatureData.value = "";
});

// recalcule quand on modifie une ligne
listeDepenses.addEventListener("input", function (event) {
  if (
    event.target.classList.contains("input-km") ||
    event.target.classList.contains("input-transport") ||
    event.target.classList.contains("input-autre")
  ) {
    recalculerTout();
  }
});

// recalcule quand on change un barème
listeDepenses.addEventListener("change", function (event) {
  if (event.target.classList.contains("select-bareme")) {
    recalculerTout();
  }
});

// clic sur ajouter une dépense
btnAjouterDepense.addEventListener("click", ajouterDepense);

// clic sur supprimer une dépense
listeDepenses.addEventListener("click", function (event) {
  if (event.target.classList.contains("btnSupprimer")) {
    supprimerDepense(event.target);
  }
});

// recalcule le remboursement quand on modifie le montant abandonné
document.getElementById("montantAbandon").addEventListener("input", function () {
  calculerMontantRembourse();
});

// change les barèmes si le type de demande change
typeDemande.addEventListener("change", function () {
  initialiserBaremes();
});

// bloque l'envoi si la signature est vide et envoie la signature en base64
formNote.addEventListener("submit", function (event) {
  if (!signatureFaite) {
    event.preventDefault();
    alert("Veuillez signer avant d’envoyer la note de frais.");
    return;
  }

  inputSignatureData.value = signaturePad.toDataURL("image/png");
});

initialiserBaremes();
recalculerTout();