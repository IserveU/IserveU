(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.config(

	function($translateProvider){

		var jargon = localStorage.getItem('settings');

		if ( !jargon || jargon.length <= 2){

		    var initInjector = angular.injector(['ng']);
		    var $http = initInjector.get('$http');

			$http.get('settings').then(function(r){
				localStorage.setItem('settings', JSON.stringify(r.data));
				jargon = r.data.jargon;
			});

		} else 
			jargon = JSON.parse(jargon).jargon;


		$translateProvider.preferredLanguage('en');

		// $translateProvider.determinePreferredLanguage(function(){
			// var preferredLangKey = '';
			// custom logic, probably grab from local storage/cookie storage
		// 	return preferredLangKey;
		// });

		$translateProvider.translations('en', {
			LANG_NAME: "Languages",
			MOTION: jargon.en.motion ? jargon.en.motion : "Motion",
			MOTIONS: jargon.en.motions ? jargon.en.motions : "Motions", //depecrated
			BETA_HEADER: "IserveU is currently in BETA. ",
			BETA_MESSAGE: "Features and improvements are constantly being added. If you would like give feedback and help us test the software, please email ",
			BETA_MESSAGE_MINI: "If you encounter any issues, please email ",
			PHOTO_COURTESY: "Photo courtesy of ",
			LOGOUT: "Logout",
			YOUR_PROFILE: "Your Profile",
			USER_LIST: "User List",
			UPLOAD_BACKGROUND_IMG: "Upload Background Image",
			DEPARTMENT_MANAGER: "Department Manager",
			PROPERTY_MANAGER: "Property Manager",
			//UserBar Titles
			Home: "Home",
			Background_Images: "Background Images", 
			//home state
			WELCOME: "Welcome!",
			YOUR_VOTES: "Your Votes",
			YOUR_COMMENTS: "Your Comments",
			CURRENTLY_PASSING: "Currently Passing",
			TODAYS_TOP_COMMENTS: "Today's Top Comments",
			BY: "by ",
			BY_A: "by a Yellowknifer",
			//background state
			WHO_TOOK_THIS: "Who took this photo?",
			EXAMPLE_WEBSITE: "myphotographywebsite.ca",
			DAILY_CYCLE_TOOLTIP: "This will place your photo into the daily cycles immediately.",
			UPLOAD_PHOTO_ERROR: "There was an error uploading your photo. Make sure you've entered in all the fields correctly.",
			PREVIEW: "Preview",
			//buttons
			SUBMIT: "Submit",
			OK: "Okay",
			CANCEL: "Cancel",
			ACTIVE: "Active",
			ADD: "Add",
			EDIT: "Edit",
			DELETE: "Delete",
			SAVE: "Save",
			SAVE_CHANGES: "Save Changes",
			CLOSE: "Close",
			BACK: "Back",
			REMOVE: "Remove",
			//field names
			FIRST_NAME: "First Name",
			MIDDLE_NAME: "Middle Name",
			LAST_NAME: "Last Name",
			BIRTHDAY: "Birthday",
			EMAIL_ADDRESS: "Email Address",
			PUBLIC: "Public",
			PRIVATE: "Private",
			IDENTITY_VERIFIED: "Identity Verified",
			USER_VERIFIED: "User identity is verified.",
			USER_NOT_VERIFIED: "User identity is not verified",
			PASSWORD: "Password",
			CHANGE_PASSWORD: "Change Password",
			ETHNIC_ORIGIN: "Ethnicity",
			ADDRESS: "Address",
			SELECT_ROLE: "Select Role",
			BIRTHDAY_NOT_SET: "Birthday not set",
			//user state
			EDIT_YOUR_PUBLIC_PROFILE: "Edit your public profile",
			VOTING_HISTORY: "Voting History",
			//role state
			NAME: "Name",
			IDENTITY: "Identity",
			ROLES: "Roles",
			VERIFIED: "Verified",
			UNVERIFIED: "Unverified",
			USER_ROLES: "User roles",
			//motion state
			INTRODUCTION: "Introduction",
			DETAILS: "Details",
			CLOSED: "Closed",
			OPEN: "Open",
			CLOSES_ON: "closes on ",
			DESCRIPTION: "Description",
			VOTING: "Voting",
			POST: "Post",
			SUBMIT: "Submit",
			POST_COMMENT: "Post Comment",
			YOUR_COMMENT: "Your Comment",
			SUBMIT_COMMENT: "Submit Comment",
			SAVE_COMMENT: "Save Comment",
			COMMENT: "Comment",
			AGREE: "Agree",
			AGREED: "Agreed",
			DISAGREE: "Disagree",
			DISAGREED: "Disagreed",
			ABSTAIN: "Abstain",
			ABSTAINED: "Abstained",
			AGREE_DEFERRALS: "Agree deferrals",
			DISAGREE_DEFERRALS: "Disagree deferrals",
			ABSTAIN_DEFERRALS: "Abstain deferrals",
			AGREE_WITH_COMMENT: "Agree With Comment",
			DISAGREE_WITH_COMMENT: "Disagree With Comment",
			DISAGREE_ABSTAIN: "Disagree / Abstain",
			WRITTEN: "written ",
			EDITED: "edited ",
			DEPARTMENT: "Department",
			DISPLAY: "Display",
			ACTIVATE_MOTION: "Activate motion.",
			ATTACHMENTS: "Attachments",
			DRAG_AND_DROP: "or drag and drop your files here",
			//motion sidebar
			NEUTRAL: "Neutral",
			QUICK_VOTE: "Quick Vote",
			NO_MOTIONS: "No Motions",
			CREATE_NEW_MOTION: jargon.en.motion ? "Create new " + jargon.en.motion : "Create new motion",
			//department state
			EXISTING_DEPARTMENT: "Existing departments",
			//Password Reset
			PLEASE_RESET: "Please reset your password.",
			NEW_PASSWORD: "New Password",
			CONFIRM_PASSWORD: "Confirm Password",
			LEAST_CHAR: "Must be at least 8 characters.",
			PASS_NOT_MATCH: "Your password does not match.",
			//Address keys
			VERIFY_ADDRESS: "Verify your address",
			ADDRESS_CAPTION: "We will need to verify your identity for your Yellowknife vote to count.",
			APT_SUITE: "Apt./Suite",
			STREET_NUM: "Street Number",
			POSTAL_CODE: "Postal Code",
			ROLL_NUMBER: "Roll Number",
			SEARCH_RESULTS: "Search Results",
			SELECT_ADDRESS: "Select your address.",
			FIELD_SEARCH: "Please fill in the fields on the left to begin searching.",
			UNIT_NUMBER: "Unit Number",
			STREET_ADDRESS: "Street Address",
			STREET: "Street Name"


		});

		$translateProvider.translations('fr', {
			LANG_NAME: "Langue",
			MOTION: jargon.fr.motion ? jargon.fr.motion : "Motion",
			MOTIONS: jargon.fr.motions ? jargon.fr.motions : "Motions",
			BETA_HEADER: "IserveU est présentement en BETA. ",
			BETA_MESSAGE: "Les caractéristiques et les améliorations sont constamment ajoutées. Si vous désirez nous aider en testant notre programme, envoyez-nous un courriel à ",
			BETA_MESSAGE_MINI: "Pour tous problèmes, communiquez avec nous par courriel ",
			PHOTO_COURTESY: "Les photos sont une courtoisie de ",
			LOGOUT: "Se déconnecter",
			YOUR_PROFILE: "Votre profile",
			USER_LIST: "Liste d’utilisateurs",
			UPLOAD_BACKGROUND_IMG: "Charger l’image d’arrière-plan",
			DEPARTMENT_MANAGER: "Gérant de département",
			PROPERTY_MANAGER: "Gérant de propriété",
			//UserBar Titles
			Home: "Accueil",
			Background_Images: "Images d’arrière-plan", 
			//home state
			WELCOME: "Bienvenue!",
			YOUR_VOTES: "Votre vote",
			YOUR_COMMENTS: "Vos commentaires",
			CURRENTLY_PASSING: "Passant actuellement",
			TODAYS_TOP_COMMENTS: "Top commentaire du jour",
			BY_A: "par un Yellowknifer",
			//background state
			WHO_TOOK_THIS: "Qui a pris cette photo?",
			EXAMPLE_WEBSITE: "myphotographywebsite.ca",
			DAILY_CYCLE_TOOLTIP: "Cette option permettra d’ajouter votre photo au quotidien immédiatement.",
			UPLOAD_PHOTO_ERROR: "Une erreur de chargement est survenue  assurez-vous d’avoir remplis correctement tous les champs d’option.",
			PREVIEW: "Aperçus",
			//buttons
			SUBMIT: "Soumis",
			OK: "Okay",
			CANCEL: "Cancellé",
			ACTIVE: "Activé",
			ADD: "Ajouter",
			EDIT: "Édité",
			DELETE: "Effacé",
			SAVE: "Sauvegardé",
			SAVE_CHANGES: "Changement sauvegardé",
			CLOSE: "Fermé",
			BACK: "Retour",
			REMOVE: "Supprimer",
			//field names
			FIRST_NAME: "Prénom",
			MIDDLE_NAME: "Second nom",
			LAST_NAME: "Nom de famille",
			BIRTHDAY: "Date de naissance",
			EMAIL_ADDRESS: "Adresse courriel",
			PUBLIC: "Publique",
			PRIVATE: "Privé",
			IDENTITY_VERIFIED: "Identité vérifié",
			USER_VERIFIED: "Identité de l'utilisateur vérifié.",
			USER_NOT_VERIFIED: "Identité de l'utilisateur non vérifées",
			PASSWORD: "Mot de passe",
			CHANGE_PASSWORD: "Changer le mot de passe",
			ETHNIC_ORIGIN: "Ethnicité",
			ADDRESS: "Adresse",
			SELECT_ROLE: "Sélectionner un rôle",
			BIRTHDAY_NOT_SET: "Date de naissance non-enregistrée",
			//user state
			EDIT_YOUR_PUBLIC_PROFILE: "Modifier votre profile publique",
			VOTING_HISTORY: "L'histoire de Vote",
			// TODO: role state
			NAME: "Nom",
			IDENTITY: "Identité",
			ROLES: "Rôles",
			VERIFIED: "Vérifié",
			UNVERIFIED: "Non-vérifier",
			USER_ROLES: "Rôle de l’utilisateur",
			//motion state
			INTRODUCTION: "Introduction",
			DETAILS: "Détails",
			CLOSED: "Fermé",
			OPEN: "Ouvert",
			CLOSES_ON: "fermer ",
			DESCRIPTION: "Description",
			VOTING: "Voter",
			POST_COMMENT: "Afficher un commentaire",
			YOUR_COMMENT: "Votre commentaire",
			SUBMIT_COMMENT: "Envoyer votre commentaire",
			SAVE_COMMENT: "Sauvegarder le commentaire",
			COMMENT: "Commentaire",
			AGREE: "Accepter",
			AGREED: "",
			DISAGREE: "En désaccord",
			DISAGREED: "",
			ABSTAIN: "",
			ABSTAINED: "",
			AGREE_DEFERRALS: "",
			DISAGREE_DEFERRALS: "",
			ABSTAIN_DEFERRALS: "",
			AGREE_WITH_COMMENT: "En accord avec le commentaire",
			DISAGREE_WITH_COMMENT: "En désaccord avec le commentaire",
			DISAGREE_ABSTAIN: "En désaccord/ S’abstenir",
			WRITTEN: "écris ",
			EDITED: "édité ",
			DEPARTMENT: "Département",
			DISPLAY: "Affichage",
			ACTIVATE_MOTION: "Motion active.",
			ATTACHMENTS: "Attachments",
			DRAG_AND_DROP: "Attachez ou faites glisser votre document ici",
			//motion sidebar
			NEUTRAL: "Neutre",
			QUICK_VOTE: "Vote rapide",
			NO_MOTIONS: "Aucune motion",
			CREATE_NEW_MOTION: jargon.fr.motion ? "Créer une nouvelle" + jargon.fr.motion : "Créer une nouvelle motion",
			//department state
			EXISTING_DEPARTMENT: "Département existant",
			//Password Reset
			PLEASE_RESET: "SVP réinitialisez votre mot de passe.",
			NEW_PASSWORD: "Nouveau mot de passe",
			CONFIRM_PASSWORD: "Confirmer votre mot de passe",
			LEAST_CHAR: "Doit contenir au moins 8 caractères.",
			PASS_NOT_MATCH: "Votre  de passe ne correspond pas.",
			//Address keys
			VERIFY_ADDRESS: "Vérifier votre adresse",
			ADDRESS_CAPTION: "Nous auront besoin de vérifier votre identité pour que vote de Yellowknife compte.",
			APT_SUITE: "App./Suite",
			STREET_NUM: "Numéro civique",
			POSTAL_CODE: "Code postal",
			ROLL_NUMBER: "Numéro de lien",
			SEARCH_RESULTS: "Résultats de la recherche",
			SELECT_ADDRESS: "Sélectionnez votre adresse.",
			FIELD_SEARCH: "SVP remplir les champs sur la gauche pour commencer la recherche.",
			UNIT_NUMBER: "Numéro d’unité",
			STREET_ADDRESS: "Numéro de rue",
			STREET: "Nom de rue"
		}).fallbackLanguage('en');

		//uses local storage to remember user's preferred language
		$translateProvider.useLocalStorage();

	});


})();