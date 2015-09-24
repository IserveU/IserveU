(function() {
	
	'use strict';

	angular
		.module('iserveu')
		.config(

	function($translateProvider){

		$translateProvider.preferredLanguage('en');

		// $translateProvider.determinePreferredLanguage(function(){
			// var preferredLangKey = '';
			// custom logic, probably grab from local storage/cookie storage
		// 	return preferredLangKey;
		// });

		$translateProvider.translations('en', {
			LANG_NAME: "Languages",
			MOTIONS: "Motions",
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
			//field names
			FIRST_NAME: "First Name",
			MIDDLE_NAME: "Middle Name",
			LAST_NAME: "Last Name",
			BIRTHDAY: "Birthday",
			EMAIL_ADDRESS: "Email Address",
			PUBLIC: "Public",
			IDENTITY_VERIFIED: "Identity Verified",
			PASSWORD: "Password",
			CHANGE_PASSWORD: "Change Password",
			ETHNIC_ORIGIN: "Ethnicity",
			SELECT_ROLE: "Select Role",
			BIRTHDAY_NOT_SET: "Birthday not set",
			//user state
			EDIT_YOUR_PUBLIC_PROFILE: "Edit your public profile",
			VOTING_HISTORY: "Voting History",
			//motion state
			INTRODUCTION: "Introduction",
			DETAILS: "Details",
			CLOSED: "Closed",
			OPEN: "Open",
			CLOSES_ON: "closes on ",
			DESCRIPTION: "Description",
			VOTING: "Voting",
			POST_COMMENT: "Post Comment",
			YOUR_COMMENT: "Your Comment",
			SUBMIT_COMMENT: "Submit Comment",
			SAVE_COMMENT: "Save Comment",
			COMMENT: "Comment",
			AGREE: "Agree",
			AGREE_WITH_COMMENT: "Agree With Comment",
			DISAGREE_WITH_COMMENT: "Disagree With Comment",
			DISAGREE_ABSTAIN: "Disagree / Abstain",
			WRITTEN: "written ",
			EDITED: "edited ",
			//motion sidebar
			NEUTRAL: "Neutral",
			QUICK_VOTE: "Quick Vote",
			NO_MOTIONS: "No Motions",
			CREATE_NEW_MOTION: "Create new motion",
			//department state
			EXISTING_DEPARTMENT: "Existing departments",

		});

		$translateProvider.translations('fr', {
			LANG_NAME: "Langue",
			MOTIONS: "Motions",
			BETA_HEADER: "IserveU est présentement en BETA. ",
			BETA_MESSAGE: "Les caractéristiques et les améliorations sont constamment ajoutées. Si vous désirez nous aider en testant notre programme, envoyez-nous un courriel à ",
			BETA_MESSAGE_MINI: "Si vous avez des problèmes, envoyez-nous un courriel ",
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
			//field names
			FIRST_NAME: "Prénom",
			MIDDLE_NAME: "Second nom",
			LAST_NAME: "Nom de famille",
			BIRTHDAY: "Date de naissance",
			EMAIL_ADDRESS: "Adresse courriel",
			PUBLIC: "Publique",
			IDENTITY_VERIFIED: "Identité vérifié",
			PASSWORD: "Mot de passe",
			CHANGE_PASSWORD: "Changer le mot de passe",
			ETHNIC_ORIGIN: "Ethnicité",
			SELECT_ROLE: "Sélectionner un rôle",
			BIRTHDAY_NOT_SET: "Date de naissance non-enregistrée",
			//user state
			EDIT_YOUR_PUBLIC_PROFILE: "Modifier votre profile publique",
			VOTING_HISTORY: "L'histoire de Vote",
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
			AGREE_WITH_COMMENT: "En accord avec le commentaire",
			DISAGREE_WITH_COMMENT: "En désaccord avec le commentaire",
			DISAGREE_ABSTAIN: "En désaccord/ S’abstenir",
			WRITTEN: "écris ",
			EDITED: "édité ",
			//motion sidebar
			NEUTRAL: "Neutre",
			QUICK_VOTE: "Vote rapide",
			NO_MOTIONS: "Aucune motion",
			CREATE_NEW_MOTION: "Créer une nouvelle motion",
			//department state
			EXISTING_DEPARTMENT: "Département existant",
		}).fallbackLanguage('en');

		//uses local storage to remember user's preferred language
		$translateProvider.useLocalStorage();

	});


})();